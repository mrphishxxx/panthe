function nc_form(url, backurl, target, modalWindowSize, httpMethod, httpData) {
    if ( ! target && window.event) {
        target = window.event.target || window.event.srcElement;
    }

    var $target = target ? $nc(target) : false;
    if ($target) {
        if ($target.hasClass('nc--disabled')) {
            return;
        }
        $target.addClass('nc--disabled');
    }

    if (!backurl) backurl = '';


    nc.process_start('nc_form()');

    if (!modalWindowSize) {
        modalWindowSize = null;
    }

    if (!httpMethod) {
        httpMethod = 'GET';
    }

    if (!httpData) {
        httpData = {};
    }

    $nc.ajax({
        'type' : httpMethod,
        'url': url + '&isNaked=1',
        'data': httpData,
        'success' : function(response) {

            nc.process_stop('nc_form()');
            if ($target) $target.removeClass('nc--disabled');

            nc_remove_content_for_modal();
            $nc('body').append('<div style="display: none;" id="nc_form_result"></div>');
            $nc('#nc_form_result').html(response).modal({
                onShow: function () {

                    $nc('#nc_form_result').children().not('.nc_admin_form_menu, .nc_admin_form_body, .nc_admin_form_buttons').hide();

                    var container = $nc('#simplemodal-container');

                    if (modalWindowSize) {
                        var currentLeft = parseInt(container.css('left'));
                        var currentWidth = container.width();

                        var currentTop = parseInt(container.css('top'));
                        var currentHeight = container.height();

                        container.css({
                            width: modalWindowSize.width,
                            height: modalWindowSize.height,
                            left: currentLeft + (currentWidth - modalWindowSize.width) / 2,
                            top: currentTop + (currentHeight - modalWindowSize.height) / 2,
                        });
                    }

                    $nc('div.nc_admin_form_body').css({
                        width:container.width()  + 'px',
                        height:container.height() + 'px',
                        overflow:'auto'
                    });

                    $nc('#nc_form_result').css({
                        width:container.width()  - 25 + 'px',
                        height:container.height() + 'px',
                        overflow:'hidden'
                    });


                    $nc('#nc_form_result').css({
                        width:container.width() + 'px'
                        });

                    $nc('#nc_form_result #adminForm').append("<input type='hidden' name='nc_token' value='" + nc_token + "' />");

                    $nc(document).bind('keydown.simplemodal', function (e) {
                        if (e.keyCode !== 27) {
                            return;
                        }
                        if ($nc(e.target).is(':input')) { // ESC
                            return;
                        }
                        if ($nc('.cm_fullscreen').length > 0) {
                            return;
                        }
                        e.preventDefault();
                        $nc.modal.close();
                    });
                },
                //closeHTML: "<div class='modalCloseText'>Закрыть</div><a class='modalCloseImg' title='Закрыть'></a>",
                closeHTML: "<a class='modalCloseImg'></a>",
                escClose:false,
                onClose: function (e) {
                    if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances) {
                        for (var instance_name in CKEDITOR.instances) {
                            if (!/_edit_inline$/i.test(instance_name)) {
                                CKEDITOR.instances[instance_name].destroy();
                            } else {
                                var $element = $('#' + instance_name);
                                var oldValue = $element.attr('data-oldvalue');
                                $element.html(oldValue);
                            }
                        }

                        if (typeof(CKEDITOR.nc_active_inline) != 'undefined') {
                            CKEDITOR.nc_active_inline = false;
                        }
                    }
                    $nc.modal.close();
                    $nc(document).unbind('keydown.simplemodal');
                    nc_remove_content_for_modal();
                }
            });
        $nc('#nc_form_result #adminForm').ajaxForm({
            beforeSerialize: function() {
            	if (typeof CKEDITOR != 'undefined' && CKEDITOR.instances) {
                    for (var instance_name in CKEDITOR.instances) {
                        var $textarea = $nc('textarea[name=' + instance_name + ']');
                        if ($textarea.length) {
                            $textarea.get(0).innerHTML = CKEDITOR.instances[instance_name].getData();
                        }
                    }
                }
                if (window.FCKeditorAPI) {
					for (fckeditorName in FCKeditorAPI.Instances) {
						var editor = FCKeditorAPI.GetInstance(fckeditorName);
						if ( editor.IsDirty() ) {
							$nc('#' + fckeditorName).val( editor.GetHTML() );
						}
					}
				}
                //$nc('#nc_form_result #adminForm textarea.has_codemirror').each(function() {
                //    $nc(this).data('codemirror').save();
                //});
                CMSaveAll();
            },

			// modal layer button submit
            success: function(response) {
                var error = nc_check_error(response);
                if (error) {
                    $nc('.nc_admin_form_buttons').append(
                        "<div id='nc_modal_error' style='position: absolute; z-index: 3000; width: 300px; border: 2px solid red;background-color: white; bottom: 20px; text-align: left; padding: 10px;'>"
                        + "<div class='simplemodal_error_close'></div>"
                        + error
                        + "</div>");
                    return false;
                }

                //if (response == 'OK') {
                //    window.location.reload(true);
                //    return false;
                //}
                var loc = window.location,
                    newUrlMatch = (/^NewHiddenURL=(.+?)$/m).exec(response), // в ответе есть строка "NewHiddenUrl=something"
                    newUrl = newUrlMatch ? $nc.trim(newUrlMatch[1]) : null; // новый HiddenURL страницы

                if ((/^ReloadPage=1$/m).test(response)) { // в ответе есть строка "ReloadPage=1"
                  // не режим "редактирование", изменился путь страницы
                  if (newUrl && !(/\.php/.test(window.location.pathname))) {
                    // сохранить имя страницы, если оно было (изменение свойств раздела со страницы объекта)
                    var pageNameMatch = /\/([^\/]+)$/.exec(loc.pathname);
                    if (pageNameMatch) { newUrl += pageNameMatch[1]; }
                    loc.pathname = newUrl;
                  }
                  else {
                    loc.reload(true);
                  }
                  return false;
                }
                else {
                  $nc.ajax({
                      'type' : 'GET',
                      'url': (backurl ? backurl : nc_page_url()) + '&isNaked=1&admin_modal=1',
                      success: function(response) {
                          nc_update_admin_mode_content(response);
                          $nc.modal.close();
                      }
                  });
                }
        }
        });
    return false;
    }
});
}

function nc_action_message(url, httpMethod, httpData) {
    var ajax_url = url + '&isNaked=1&posting=1' + '&nc_token=' + nc_token;

    if (!httpMethod) {
        httpMethod = 'GET';
    }

    if (!httpData) {
        httpData = {};
    }

    $nc.ajax({
        'type' : httpMethod,
        'data': httpData,
        'url': ajax_url,
        'success' : function(response) {
            response = $nc.trim(response);
            if (response == 'deleted') {
                $nc('body', nc_get_current_document()).append("<div id='formAsyncSaveStatus'>Объект помещен в корзину</div>");
                $nc('div#formAsyncSaveStatus', nc_get_current_document()).css({
                    backgroundColor: '#39B54A'
                });
                setTimeout(function () {
                    $nc('div#formAsyncSaveStatus', nc_get_current_document()).remove();
                },
                1000);
            }

            if (response == 'trashbin_disabled') {

                nc_print_custom_modal();

                $nc('div#nc_cart_confirm_footer button.nc_admin_metro_button').click(function() {
                    $nc.modal.close();
                    nc_action_message(url + '&force_delete=1')
                });

                return null;
            }

            var $status_message = $nc('<div />').html(response).find('#statusMessage');

            $nc.ajax({
                'type': 'GET',
                'url' : nc_page_url() + '&isNaked=1',
                'success' : function(response) {
                    response ? nc_update_admin_mode_content(response, $status_message)
                    : nc_page_url(nc_get_back_page_url());
                }
            });
    }
    });
}

function nc_is_frame() {
    return typeof mainView != "undefined";
}

function nc_get_back_page_url() {
    return NETCAT_PATH + '?' + nc_page_url().match(/sub=[0-9]+/) + (nc_is_frame() ? '&inside_admin=1' : '');
}

function nc_page_url(url) {
    return nc_correct_page_url(url ? nc_get_location().href = url : nc_get_location().href);
}

function nc_correct_page_url(url) {
    return url.indexOf('?') == -1 ? url + '?' : url ;
}

function nc_update_admin_mode_content(content, $status_message) {
    var current_document = nc_get_current_document();
    var $nc_admin_mode_content = $nc('#nc_admin_mode_content', current_document);
    $nc_admin_mode_content.prev('#statusMessage').remove();
    $nc_admin_mode_content.html(content);
    if (typeof($status_message) != 'undefined' && $status_message) {
        $nc_admin_mode_content.before($status_message);
    }
}

function nc_get_current_document() {
    return nc_is_frame() ? mainView.oIframe.contentDocument : document;
}

function nc_get_location() {
    return nc_is_frame() ? mainView.oIframe.contentWindow.location : location;
}

function nc_remove_content_for_modal() {
    $nc('#nc_form_result').remove();
}

function nc_password_change() {
    var $password_change = $nc('#nc_password_change');
    $password_change.modal({
        closeHTML: "",
        containerId: 'nc_small_modal_container',
        onShow: function () {
            $nc('div.simplemodal-wrap').css({padding:0, overflow:'inherit'});
            var $form = $password_change.find('form');
            $nc('#nc_small_modal_container').addClass('nc-shadow-large').css({width:$form.width(), height:$form.height()});
            $nc(window).resize();
        }
    });

    // $nc('.password_change_simplemodal_container').css({
    //     backgroundColor: 'white',
    // });

    //FIXME: проверка формы изменения пароля перед отправкой
    if (false) {
        var $submit = $password_change.find('button[type=submit]');
        // var button = $nc('div#nc_password_change_footer button.nc_admin_metro_button');
        $submit.unbind();
        $submit.click(function() {
            if ($nc('input[name=Password1]').val() != $nc('input[name=Password2]').val()) {
                $nc('div#nc_password_change_footer').append(
                    "<div id='nc_modal_error' style='position: absolute; z-index: 3000; width: 200px; border: 2px solid red;background-color: white; bottom: 190px; text-align: left; padding: 10px;'>"
                    + "<div class='simplemodal_error_close'></div>"
                    + ncLang.UserPasswordsMismatch
                    + "</div>");
                return false;
            }
            $nc('div#nc_password_change_body form').submit();
        });
    }

    $nc('div#nc_password_change form').ajaxForm({
        success : function() {
            $nc.modal.close();
        }
    });
}

$nc('button.nc_admin_metro_button_cancel').click(function() {
    $nc.modal.close();
});

function nc_check_error(response) {
    var div = document.createElement('div');
    div.innerHTML = response;
    return $nc(div).find('#nc_error').html();
}

$nc('.simplemodal_error_close').click(function() {
    $nc('#nc_modal_error').remove();
});

function CMSaveAll() {
	/* // pre method
	var editors = null;

	if ( nc_is_frame() ) {
		editors = mainView.oIframe.contentWindow.CMEditors;
	}
	else {
		editors = window.CMEditors;
	}
    if ( typeof(editors) != 'undefined' ) {
        for(var key in editors) {
            editors[key].save();
        }
    }*/

    $nc('textarea.has_codemirror').each(function() {
        $nc(this).data('codemirror').save();
    });
}

function nc_print_custom_modal() {
    $nc('body').append("<div id='nc_cart_confirm' style='display: none;'></div>");

    var cart_confirm = $nc('#nc_cart_confirm');

    cart_confirm.append("<div id='nc_cart_confirm_header'></div>");
    cart_confirm.append("<div id='nc_cart_confirm_body'></div>");
    cart_confirm.append("<div id='nc_cart_confirm_footer'></div>");

    $nc('#nc_cart_confirm_header').append("<div><h2 style='padding: 0px;'>" + ncLang.DropHard + "</h2></div>");
    $nc('#nc_cart_confirm_footer').append("<button type='button' class='nc_admin_metro_button nc-btn nc--blue'>" + ncLang.Drop + "</button>");
    $nc('#nc_cart_confirm_footer').append("<button type='button' class='nc_admin_metro_button_cancel nc-btn nc--red nc--bordered nc--right'>" + ncLang.Cancel + "</button>");

    cart_confirm.modal({
        closeHTML: "",
        containerId: 'cart_confirm_simplemodal_container',
        onShow: function () {
            $nc('.simplemodal-wrap').css({
                backgroundColor: 'white'
            });
        },
        onClose : function () {
            $nc.modal.close();
            $nc('#nc_cart_confirm').remove();
        }
    });

    $nc('div#nc_cart_confirm_footer button.nc_admin_metro_button_cancel').click(function() {
        $nc.modal.close();
    });
}

function prepare_message_form() {
	$nc(function() {
		$nc('#adminForm').html('<div class="nc_admin_form_main">' + $nc('#adminForm').html() + '</div>');
		$nc('#adminForm').append($nc('#nc_seo_append').html());
		$nc('#adminForm').append('<input type="hidden" name="isNaked" value="1" />');
		$nc('#nc_seo_append').remove();
	});

	//var nc_admin_form_values = $nc('#adminForm').serialize();

	$nc('#nc_show_main').click(function() {
		$nc('.nc_admin_form_main').show();
		$nc('.nc_admin_form_seo').hide();
	});

	$nc('#nc_show_seo').click(function() {
		$nc('.nc_admin_form_main').hide();
		$nc('.nc_admin_form_seo').show();
	});

	$nc('#nc_object_slider_menu li').click(function(){
		$nc('#nc_object_slider_menu li').removeClass('button_on');
		$nc(this).addClass('button_on');
	});

	$nc('.nc_admin_metro_button_cancel').click(function() {
		$nc.modal.close();
	});

	$nc('.nc_admin_metro_button').click(function() {
        if ( $nc(this).attr('disabled') === 'disabled' ) return;
        $nc(this).attr('disabled', 'disabled')
            .addClass('nc--loading');
		$nc('#adminForm').submit();
	});
}
