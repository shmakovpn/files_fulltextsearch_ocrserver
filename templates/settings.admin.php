<?php
declare(strict_types=1);
/**
 * Files_FullTextSearch_OCRServer - OCR your files before index using Django_ocr_server
 * https://github.com/shmakovpn/django_ocr_server
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author shmakovpn <shmakovpn@shmakovpn.ru>
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
use OCA\Files_FullTextSearch_OCRServer\AppInfo\Application;
use OCP\Util;
Util::addScript(Application::APP_NAME, 'admin.elements');
Util::addScript(Application::APP_NAME, 'admin.settings');
Util::addScript(Application::APP_NAME, 'admin');
?>

<div id="files_ocr-ocrserver" class="section">
    <h2><?php p($l->t('Files - Django OCR Server')) ?></h2>

    <div class="div-table">
        <div class="div-table-row">
            <div class="div-table-col div-table-col-left">
                <span class="leftcol">Enable Django OCR Server:</span>
                <br/>
                <em>OCR your document with <i>Django OCR Server</i>.</em>
            </div>
            <div class="div-table-col">
                <input type="checkbox" id="ocrserver_ocr" value="1"/>
            </div>
        </div>

        <div class="div-table-row ocrserver_ocr_enabled">
            <div class="div-table-col div-table-col-left">
                <span class="leftcol">Django OCR Server URL</span>
                <br/>
                <em>Set URL of your OCR Server</em>
            </div>
            <div class="div-table-col">
                <input type="text" class="big" id="ocrserver_url" value=""/>
            </div>
        </div>

        <div class="div-table-row ocrserver_ocr_enabled">
            <div class="div-table-col div-table-col-left">
                <span class="leftcol">Auth Token</span>
                <br/>
                <em>Set Auth Token to get access to your OCR Server</em>
            </div>
            <div class="div-table-col">
                <input type="text" class="big" id="ocrserver_auth_token" value=""/>
            </div>
        </div>

    </div>


</div>
