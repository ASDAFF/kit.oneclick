/*
 * Copyright (c) 4/10/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

function kitOneClickComponentApp() {

    $('.kit-oneclick__container').each(function(){
        const dialog = $(this);
        /**
         * Close action
         */
        dialog.find('.js-kit-oneclick__dialog__close, .js-kit-oneclick__dialog__cancel-button')
            .off('click')
            .on('click', function () {
                dialog.hide();
            });
    });
    /**
     * open dialog
     */
    $('.kit-one-click-buy').on('click', function () {
        const buttonEl = $(this);

        const productId = buttonEl.data('productid');
        const dialog = $(`#kit-oneclick__container-${productId}`);

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
        dialog.find('.js-kit-oneclick__dialog__close, .js-kit-oneclick__dialog__cancel-button')
            .off('click')
            .on('click', function () {
                dialog.hide();
            });

        /**
         * Save action
         */
       /* dialog.find('.js-kit-oneclick__dialog__send-button')
            .off('click')
            .on('click', function () {
                const btn = $(this);
                let formData = new FormData(dialog.find('form').get(0));


                if (buttonEl.data('productid')) {
                    formData.append('PRODUCT_ID', buttonEl.data('productid'));
                }

                btn.hide();
                $.ajax({
                    type: 'POST',
                    //contentType: "application/json; charset=utf-8",
                    //dataType: "json",
                    //url: '/ajax/ajax.kit.oneclick.save.php',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (data) {
                        dialog.find('.errors').html('');
                        if (data.errors) {
                            // show errors
                            for (const error of data.errors) {
                                if (typeof error.field !== "undefined") {
                                    dialog.find(`.error-${error.field}`).html(error.message);
                                } else {
                                    dialog.find(`.errors`).append(`<div>${error.message}</div>`);
                                }
                            }
                            btn.show();
                        } else {
                            //data.data
                            dialog.find('.js-step-1').hide();
                            dialog.find('.js-step-2').show();
                            dialog.find('.js-kit-oneclick__result').html(data.data.message);
                        }

                    },
                    fail: function () {
                        callback(true, null);
                    }

                });
            });*/

        dialog.show();


    });
}

$(document).ready(function () {
    kitOneClickComponentApp();
});