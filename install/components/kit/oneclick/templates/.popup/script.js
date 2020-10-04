/*
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

'use strict';

function kitOneClickComponentApp() {

    $('.kit-oneclick__container').each(function () {
        var dialog = $(this);
        /**
         * Close action
         */
        dialog.find('.js-kit-oneclick__dialog__close, .js-kit-oneclick__dialog__cancel-button').off('click').on('click', function () {
            dialog.hide();
        });
    });
    /**
     * open dialog
     */
    $('.kit-one-click-buy').on('click', function () {
        var buttonEl = $(this);

        var productId = buttonEl.data('productid');
        var dialog = $('#kit-oneclick__container-' + productId);

        dialog.find('.js-kit-oneclick__dialog__send-button').show();
        dialog.find('.js-kit-oneclick__result').html('');
        dialog.find('.error').html('');
        dialog.find('.js-step-1 textarea[name="comment"]').val('');
        dialog.find('.js-step-1').show();
        dialog.find('.js-step-2').hide();
        dialog.find('.js-kit-oneclick__dialog__send-button').show();

        /**
         * Close action
         */
        dialog.find('.js-kit-oneclick__dialog__close, .js-kit-oneclick__dialog__cancel-button').off('click').on('click', function () {
            dialog.hide();
        });
        dialog.show();
    });
}

$(document).ready(function () {
    kitOneClickComponentApp();
});
//# sourceMappingURL=script.js.map
