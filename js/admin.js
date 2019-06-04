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
/** global: fts_ocrserver_elements */
/** global: fts_ocrserver_settings */


$(document).ready(function () {


    /**
     * @constructs Fts_deck
     */
    var Fts_ocrserver = function () {
        $.extend(Fts_ocrserver.prototype, fts_ocrserver_elements);
        $.extend(Fts_ocrserver.prototype, fts_ocrserver_settings);

        fts_ocrserver_elements.init();
        fts_ocrserver_settings.refreshSettingPage();
    };
console.log(Fts_ocrserver);
    OCA.FullTextSearchAdmin.fts_ocrserver = Fts_ocrserver;
    OCA.FullTextSearchAdmin.fts_ocrserver.settings = new Fts_ocrserver();

});