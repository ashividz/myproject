/*
 * SimpleModal modal Form
 * http://simplemodal.com
 *
 * Copyright (c) 2013 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */

jQuery(function ($) {    
    var modal = {
        message: null,
        init: function () {
            $('#addHerb').click(function (e) {
                e.preventDefault();

                var id = $(this).attr('value');

                // load the modal form using ajax
                $.get("/modal/" + id + "/herb", function(data){
                    // create a modal dialog with the data
                    $(data).modal({
                        closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                        position: ["15%",],
                        overlayId: 'modal-overlay',
                        containerId: 'modal-container',
                        onOpen: modal.open,
                        onShow: modal.show,
                        onClose: modal.close
                    });
                });
            });
        },
        open: function (dialog) {
            // dynamically determine height
            var h = 280;
            if ($('#modal-subject').length) {
                h += 26;
            }
            if ($('#modal-cc').length) {
                h += 22;
            }

            var title = $('#modal-container .modal-title').html();
            $('#modal-container .modal-title').html('Loading...');
            dialog.overlay.fadeIn(200, function () {
                dialog.container.fadeIn(200, function () {
                    dialog.data.fadeIn(200, function () {
                        $('#modal-container .modal-form').animate({
                            height: h
                        }, function () {
                            $('#modal-container .modal-title').html(title);
                            $('#modal-container form').fadeIn(200, function () {
                                $('#modal-container #modal-name').focus();

                                $('#modal-container .modal-cc').click(function () {
                                    var cc = $('#modal-container #modal-cc');
                                    cc.is(':checked') ? cc.attr('checked', '') : cc.attr('checked', 'checked');
                                });
                            });
                        });
                    });
                });
            });
        },
        show: function (dialog) {
            $('#modal-container .modal-send').click(function (e) {
                e.preventDefault();

                // validate form
                if (modal.validate()) {
                    var msg = $('#modal-container .modal-message');
                    msg.fadeOut(function () {
                        msg.removeClass('modal-error').empty();
                    });
                    $('#modal-container .modal-title').html('Sending...');
                    $('#modal-container form').fadeOut(200);
                    $('#modal-container .modal-form').animate({
                        height: '80px'
                    }, function () {
                        $('#modal-container .modal-loading').fadeIn(200, function () {
                            $.ajax({
                                url: '/patient/saveHerb',
                                data: $('#modal-container form').serialize() + '&action=send',
                                type: 'post',
                                cache: false,
                                dataType: 'html',
                                success: function (data) {
                                    $('#modal-container .modal-loading').fadeOut(200, function () {
                                        $('#modal-container .modal-title').html('Thank you!');
                                        msg.html(data).fadeIn(200);
                                    });
                                },
                                error: modal.error
                            });
                        });
                    });
                }
                else {
                    if ($('#modal-container .modal-message:visible').length > 0) {
                        var msg = $('#modal-container .modal-message div');
                        msg.fadeOut(200, function () {
                            msg.empty();
                            modal.showError();
                            msg.fadeIn(200);
                        });
                    }
                    else {
                        $('#modal-container .modal-message').animate({
                            height: '30px'
                        }, modal.showError);
                    }
                    
                }
            });
        },
        close: function (dialog) {
            $('.modal-message').fadeOut();
            $('form').fadeOut(200);
            $('.modal-form').animate({
                height: 40
            }, function () {
                dialog.data.fadeOut(200, function () {
                    dialog.container.fadeOut(200, function () {
                        dialog.overlay.fadeOut(200, function () {
                            $.modal.close();
                            location.reload();
                        });
                    });
                });

                
            });
        },
        error: function (xhr) {
            alert(xhr.statusText);
        },
        validate: function () {
            modal.message = '';
            return true;
        },
        showError: function () {
            $('.modal-message')
                .html($('<div class="modal-error"></div>').append(modal.message))
                .fadeIn(200);
        }
    };

    modal.init();

});