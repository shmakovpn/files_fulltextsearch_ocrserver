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
use OCP\ILogger;

/**
 * Class MiscService
 *
 * @package OCA\Files_FullTextSearch_OCRServer\Service
 */
class MiscService
{
    /** @var ILogger */
    private $logger;

    /**
     * MiscService constructor.
     *
     * @param ILogger $logger
     */
    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $message
     * @param int $level
     */
    public function log(string $message, int $level = 2)
    {
        $data = array(
            'app' => Application::APP_NAME,
            'level' => $level
        );
        $this->logger->log($level, $message, $data);
    }
}
