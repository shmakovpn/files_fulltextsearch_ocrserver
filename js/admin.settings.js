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

/** global: OC */
/** global: fts_ocrserver_elements */
/** global: fts_admin_settings */



var fts_ocrserver_settings = {

    config: null,

    refreshSettingPage: function () {

        $.ajax({
            method: 'GET',
            url: OC.generateUrl('/apps/files_fulltextsearch_ocrserver/admin/settings')
        }).done(function (res) {
            fts_ocrserver_settings.updateSettingPage(res);
        });

    },


    updateSettingPage: function (result) {
        fts_ocrserver_elements.ocrserver_ocr.prop('checked', (result.ocrserver_enabled === '1'));
        fts_ocrserver_elements.ocrserver_url.val(result.ocrserver_url);
        fts_ocrserver_elements.ocrserver_auth_token.val(result.ocrserver_auth_token);

        fts_admin_settings.tagSettingsAsSaved(fts_ocrserver_elements.ocrserver_div);

        if (result.ocrserver_enabled === '1') {
            fts_ocrserver_elements.ocrserver_div.find('.ocrserver_ocr_enabled').fadeTo(300, 1);
            fts_ocrserver_elements.ocrserver_div.find('.ocrserver_ocr_enabled').find('*').prop(
                'disabled', false);
        } else {
            fts_ocrserver_elements.ocrserver_div.find('.ocrserver_ocr_enabled').fadeTo(300, 0.6);
            fts_ocrserver_elements.ocrserver_div.find('.ocrserver_ocr_enabled').find('*').prop(
                'disabled', true);
        }
    },


    saveSettings: function () {

        var data = {
            ocrserver_enabled: (fts_ocrserver_elements.ocrserver_ocr.is(':checked')) ? 1 : 0,
            ocrserver_url: fts_ocrserver_elements.ocrserver_url.val(),
            ocrserver_auth_token: fts_ocrserver_elements.ocrserver_auth_token.val(),
        };

        $.ajax({
            method: 'POST',
            url: OC.generateUrl('/apps/files_fulltextsearch_ocrserver/admin/settings'),
            data: {
                data: data
            }
        }).done(function (res) {
            fts_ocrserver_settings.updateSettingPage(res);
        });

    }


};