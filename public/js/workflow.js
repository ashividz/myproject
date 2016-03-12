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
			$('a.action').click(function (e) {
				e.preventDefault();

				var id = $(this).attr('id');
				var workflow = $("#workflow").attr('value');

				// load the modal form using ajax
				$.get("/workflow/" + id + "/viewModal/?workflow=" + workflow, function(data){
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
								url: '/updateModal/workflow/',
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
			if (!$('#status').val()) {
				modal.message += 'Status is required. ';
			}
			/*
			var email = $('#modal-container #modal-email').val();
			if (!email) {
				modal.message += 'Email is required. ';
			}
			else {
				if (!modal.validateEmail(email)) {
					modal.message += 'Email is invalid. ';
				}
			}

			if (!$('#modal-container #modal-message').val()) {
				modal.message += 'Message is required.';
			}
			
			*/
			if (modal.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
		},
		validateEmail: function (email) {
			var at = email.lastIndexOf("@");

			// Make sure the at (@) sybmol exists and  
			// it is not the first or last character
			if (at < 1 || (at + 1) === email.length)
				return false;

			// Make sure there aren't multiple periods together
			if (/(\.{2,})/.test(email))
				return false;

			// Break up the local and domain portions
			var local = email.substring(0, at);
			var domain = email.substring(at + 1);

			// Check lengths
			if (local.length < 1 || local.length > 64 || domain.length < 4 || domain.length > 255)
				return false;

			// Make sure local and domain don't start with or end with a period
			if (/(^\.|\.$)/.test(local) || /(^\.|\.$)/.test(domain))
				return false;

			// Check for quoted-string addresses
			// Since almost anything is allowed in a quoted-string address,
			// we're just going to let them go through
			if (!/^"(.+)"$/.test(local)) {
				// It's a dot-string address...check for valid characters
				if (!/^[-a-zA-Z0-9!#$%*\/?|^{}`~&'+=_\.]*$/.test(local))
					return false;
			}

			// Make sure domain contains only valid characters and at least one period
			if (!/^[-a-zA-Z0-9\.]*$/.test(domain) || domain.indexOf(".") === -1)
				return false;	

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