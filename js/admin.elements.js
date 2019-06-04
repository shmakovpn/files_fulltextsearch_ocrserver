/*
 * Files_FullTextSearch_OCRServer - OCR your documents before index using Django_ocr_server
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

/** global: OCA */
/** global: fts_admin_settings */
/** global: fts_ocrserver_settings */



var fts_ocrserver_elements = {
    ocrserver_div: null,
    ocrserver_ocr: null,
    ocrserver_url: null,
    ocrserver_auth_token: null,

    init: function () {
        fts_ocrserver_elements.ocrserver_div = $('#files_ocr-ocrserver');
        fts_ocrserver_elements.ocrserver_ocr = $('#ocrserver_ocr');
        fts_ocrserver_elements.ocrserver_url = $('#ocrserver_url');
        fts_ocrserver_elements.ocrserver_auth_token = $('#ocrserver_auth_token');

        fts_ocrserver_elements.ocrserver_ocr.on('change', fts_ocrserver_elements.updateSettings);
        fts_ocrserver_elements.ocrserver_url.on('change', fts_ocrserver_elements.updateSettings);
        fts_ocrserver_elements.ocrserver_auth_token.on('change', fts_ocrserver_elements.updateSettings);
    },


    updateSettings: function () {
        fts_admin_settings.tagSettingsAsNotSaved($(this));
        fts_ocrserver_settings.saveSettings();
    }


};
