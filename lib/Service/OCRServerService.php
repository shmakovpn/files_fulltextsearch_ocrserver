<?php
declare(strict_types=1);
/**
 * Files_FullTextSearch_OCRServer - OCR your files before index using Django_ocr_server
 * https://github.com/shmakovpn/django_ocr_server
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author shmakovpn <shmakovpn@yandex.ru>
 * @copyright 2019
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Files_FullTextSearch_OCRServer\Service;
use Exception;
use OC\Files\View;
use OCP\Files\File;
use OCP\Files\Node;
use OCP\Files\NotFoundException;
#use OCA\Files_FullTextSearch\Model\FilesDocument;
use OCP\Files_FullTextSearch\Model\AFilesDocument;
#use OCA\FullTextSearch\Model\IndexDocument;
use OCP\FullTextSearch\Model\IIndexDocument;
#use OCA\FullTextSearch\Model\SearchRequest;
use OCP\FullTextSearch\Model\ISearchRequest;
use Symfony\Component\EventDispatcher\GenericEvent;



/**
 * Class OCRService
 *
 * @package OCA\Files_FullTextSearch_OCRServer\Service
 */
class OCRServerService {
    /** @var ConfigService */
    private $configService;
    /** @var MiscService */
    private $miscService;
    /**
     * OCRServerService constructor.
     *
     * @param ConfigService $configService
     * @param MiscService $miscService
     */
    public function __construct(ConfigService $configService, MiscService $miscService) {
        $this->configService = $configService;
        $this->miscService = $miscService;
    }
    /**
     * @param string $mimeType
     * @param string $extension
     *
     * @return bool
     */
    public function parsedMimeType(string $mimeType, string $extension): bool {
        $ocrMimes = [
            'image/png',
            'image/jpeg',
            'image/tiff',
            'application/pdf'
        ]; //
        foreach ($ocrMimes as $mime) {
            if (strpos($mimeType, $mime) === 0) {
                return true;
            }
        }
        return false;
    }
    /**
     * @param GenericEvent $e
     */
    public function onFileIndexing(GenericEvent $e) {
        /** @var Node $file */
        $file = $e->getArgument('file');
        if (!$file instanceof File) {
            return;
        }
        /** @var \OCP\Files_FullTextSearch\Model\AFilesDocument $document */
        $document = $e->getArgument('document');
        $this->extractContentUsingOCRServer($document, $file);
    }
    /**
     * @param GenericEvent $e
     */
    public function onSearchRequest(GenericEvent $e) {
        /** @var ISearchRequest $file */
        $request = $e->getArgument('request');
        $request->addPart('ocr');
    }
    /**
     * @param AFilesDocument $document
     * @param File $file
     */
    private function extractContentUsingOCRServer(AFilesDocument &$document, File $file) {
        try {
            if(preg_match("/^_RECOGNIZED_/i", $file->getName())) {
                return;
            }
            if ($this->configService->getAppValue(ConfigService::OCRSERVER_ENABLED) !== '1') {
                return;
            }
            $url = $this->configService->getAppValue(ConfigService::OCRSERVER_URL);
            if(!$url) {
                return;
            }
            $auth_token = $this->configService->getAppValue(ConfigService::OCRSERVER_AUTH_TOKEN);
            if(!$auth_token) {
                return;
            }
            $extension = pathinfo($document->getPath(), PATHINFO_EXTENSION);
            if (!$this->parsedMimeType($document->getMimetype(), $extension)) {
                return;
            }

            try {
                $path = $this->getAbsolutePath($file);
            } catch (Exception $e) {
                $this->miscService->log('Exception while ocr file: ' . $e->getMessage(), 1);
                throw new NotFoundException();
            }
            try {
                //Initialise the cURL var
                $ch = curl_init();
            } catch (Exception $e) {
                $this->miscService->log('Exception while ocr file. can not curl_init(): ' . $e->getMessage(), 1);
                throw new BadFunctionCallException();
            }

            //Get the response from cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            //Set the Url
            curl_setopt($ch, CURLOPT_URL, $url."upload/");

            //Set authorization token header
            $header = array();
            $header[] = 'Authorization: Token '.$auth_token;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            $postData = array(
                'file' => new \CURLFile($path, $document->getMimetype(), $file->getName()),
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($http_code!=200 && $http_code!=201) {
                $this->miscService->log('Exception while ocr file: http_code=' . $http_code . ' response=' . $response, 1);
                return;
            }

            try {
                $response_json = json_decode($response);
            } catch (Exception $e) {
                $this->miscService->log('Exception while ocr file. can not json_decode(\$response): ' . $e->getMessage(), 1);
                throw new BadFunctionCallException();
            }

            if(!$response_json->{'error'}) {
                $data = $response_json->{'data'};
                if(!$data) {
                    $this->miscService->log('Exception while ocr file: data does not exist response=' . $response, 1);
                    return;
                }
                $content = $data->{'text'};
                $ocred_pdf_url = $data->{'download_ocred_pdf'};
                if($ocred_pdf_url) {
                    try {
                        $ocred_pdf_url = $url.$ocred_pdf_url;
                        $ocred_pdf_url = str_replace("//","/", $ocred_pdf_url);

                        //Initialise the cURL var
                        $ch = curl_init();
                        //Get the response from cURL
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        //Set the Url
                        curl_setopt($ch, CURLOPT_URL, $ocred_pdf_url);
                        //Set authorization token header
                        $header = array();
                        $header[] = 'Authorization: Token '.$auth_token;
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        $response_pdf = curl_exec($ch);
                        $http_code_pdf = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);

                        if($http_code_pdf==200) {
                            if($response_pdf) {
                                $folder = $file->getParent();
                                $newFileName = "_RECOGNIZED_".preg_replace("/\..+$/", "", $file->getName()).".pdf";
                                $newFile = $folder->newFile($newFileName);
                                $newFile->putContent($response_pdf);
                            }
                        } else {
                            $this->miscService->log('Exception while ocr file. Downloading (curl) OCRed PDF error http_code=: ' . $http_code_pdf, 1);
                        }

                    } catch (Exception $e) {
                        $this->miscService->log('Exception while ocr file. Downloading OCRed PDF error: ' . $e->getMessage(), 1);
                    }
                }
            } else {
                $this->miscService->log('Exception while ocr file: error response=' . $response, 1);
                return;
            }
        } catch (Exception $e) {
            return;
        }
        $document->setContent(base64_encode($content), IIndexDocument::ENCODED_BASE64);
    }

    /**
     * @param File $file
     *
     * @return string
     * @throws Exception
     */
    private function getAbsolutePath(File $file): string {
        $view = new View('');
        $absolutePath = $view->getLocalFile($file->getPath());
        return $absolutePath;
    }
}