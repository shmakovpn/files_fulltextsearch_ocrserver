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
use OCA\Files_FullTextSearch_OCRServer\AppInfo\Application;
use OCP\IConfig;
/**
 * Class ConfigService
 *
 * @package OCA\Files_FullTextSearch_OCRServer\Service
 */
class ConfigService {
    const OCRSERVER_ENABLED = 'ocrserver_enabled';
    const OCRSERVER_URL = 'ocrserver_url';
    const OCRSERVER_AUTH_TOKEN = 'ocrserver_auth_token';
    public $defaults = [
        self::OCRSERVER_ENABLED => '0',
        self::OCRSERVER_URL     => '',
        self::OCRSERVER_AUTH_TOKEN    => '',
    ];
    /** @var IConfig */
    private $config;
    /** @var string */
    private $userId;
    /** @var MiscService */
    private $miscService;
    /**
     * ConfigService constructor.
     *
     * @param IConfig $config
     * @param string $userId
     * @param MiscService $miscService
     */
    public function __construct(IConfig $config, $userId, MiscService $miscService) {
        $this->config = $config;
        $this->userId = $userId;
        $this->miscService = $miscService;
    }
    /**
     * @return array
     */
    public function getConfig(): array {
        $keys = array_keys($this->defaults);
        $data = [];
        foreach ($keys as $k) {
            $data[$k] = $this->getAppValue($k);
        }
        return $data;
    }
    /**
     * @param array $save
     */
    public function setConfig(array $save) {
        $keys = array_keys($this->defaults);
        foreach ($keys as $k) {
            if (array_key_exists($k, $save)) {
                $this->setAppValue($k, $save[$k]);
            }
        }
    }
    /**
     * Get a value by key
     *
     * @param string $key
     *
     * @return string
     */
    public function getAppValue(string $key): string {
        $defaultValue = null;
        if (array_key_exists($key, $this->defaults)) {
            $defaultValue = $this->defaults[$key];
        }
        return $this->config->getAppValue(Application::APP_NAME, $key, $defaultValue);
    }
    /**
     * Set a value by key
     *
     * @param string $key
     * @param string $value
     */
    public function setAppValue(string $key, string $value) {
        $this->config->setAppValue(Application::APP_NAME, $key, $value);
    }
    /**
     * remove a key
     *
     * @param string $key
     *
     * @return string
     */
    public function deleteAppValue(string $key): string {
        return $this->config->deleteAppValue(Application::APP_NAME, $key);
    }
    /**
     * return if option is enabled.
     *
     * @param string $key
     *
     * @return bool
     */
    public function optionIsSelected(string $key): bool {
        return ($this->getAppValue($key) === '1');
    }
}
