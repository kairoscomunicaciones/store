/**
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License version 3.0
* that is bundled with this package in the file LICENSE.txt
* It is also available through the world-wide-web at this URL:
* https://opensource.org/licenses/AFL-3.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to a newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright Since 2010 Webkul
* @license https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
*/

$(document).ready(function() {
    $(".uploadimg_container").filer({
        limit: null,
        maxSize: null,
        extensions: null,
        changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="seller-dragdrop">' + wk_drag_drop_img + '</div></div></div><div class="seller-photo-or">' + wk_or_img + '</div><button class="button seller-pickphoto" type="button">' + wk_pick_img + '</button>',
        showThumbs: true,
        theme: "dragdropbox",
        templates: {
            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
            item: '<li class="jFiler-item">\
						<div class="jFiler-item-container">\
							<div class="jFiler-item-inner">\
								<div class="jFiler-item-thumb">\
									{{fi-image}}\
								</div>\
								<div class="jFiler-item-assets jFiler-row">\
									<ul class="list-inline pull-left">\
										<li>{{fi-progressBar}}</li>\
									</ul>\
									<ul class="list-inline pull-right">\
										<li><a class="icon-jfi-trash jFiler-item-trash-action wk_delete_img" data-id_seller="' + actionIdForUpload + '" data-imgtype=""></a></li>\
									</ul>\
								</div>\
							</div>\
						</div>\
					</li>',
            itemAppend: '<li class="jFiler-item">\
							<div class="jFiler-item-container">\
								<div class="jFiler-item-inner">\
									<div class="jFiler-item-thumb">\
										{{fi-image}}\
									</div>\
									<div class="jFiler-item-assets jFiler-row">\
										<ul class="list-inline pull-left">\
											<li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
										</ul>\
										<ul class="list-inline pull-right">\
											<li><a class="icon-jfi-trash jFiler-item-trash-action wk_delete_img" data-id_seller="' + actionIdForUpload + '" data-imgtype=""></a></li>\
										</ul>\
									</div>\
								</div>\
							</div>\
						</li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: true, //append in last
            canvasImage: true,
            removeConfirmation: true, //deleteaction is available for product page
            _selectors: {
                list: '.jFiler-items-list',
                item: '.jFiler-item',
                progressBar: '.bar',
                remove: '.jFiler-item-trash-action'
            }
        },
        dragDrop: {
            dragEnter: null,
            dragLeave: null,
            drop: null,
            dragContainer: null,
        },
        uploadFile: {
            url: imageUploader,
            data: { action: 'uploadimage', actionIdForUpload: actionIdForUpload, ajax: '1' },
            type: 'POST',
            enctype: 'multipart/form-data',
            synchron: true,
            beforeSend: function() {
                $('#file_size_reached').html('');
            },
            success: function(data, itemEl, listEl, boxEl, newInputEl, inputEl, id) {
                if (IsJsonString(data)) {
                    var newdata = JSON.parse(data);
                    var parent = itemEl.find(".jFiler-jProgressBar").parent(),
                        new_file_name = newdata.file_name,
                        new_id_image = newdata.id_image,
                        filerKit = inputEl.prop("jFiler");

                    filerKit.files_list[id].name = new_file_name;
                    filerKit.files_list[id].id_image = new_id_image;

                    if (newdata.status != 'fail') {
                        itemEl.find(".jFiler-jProgressBar").fadeOut("slow", function() {
                            $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
                        });
                    } else {
                        itemEl.find(".jFiler-jProgressBar").fadeOut("slow", function() {
                            $("<div class=\"jFiler-item-others text-failed\"><i class=\"icon-jfi-exclamation-circle\"></i> " + newdata.error_message + "</div>").hide().appendTo(parent).fadeIn("slow");
                        });
                    }
                } else {
                    $('#file_size_reached').html(upload_file_error);
                }
            },
            error: function(el) {
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function() {
                    $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
                });
            },
            statusCode: null,
            onProgress: null,
            onComplete: null
        },
        files: null,
        addMore: false,
        allowDuplicates: true,
        clipBoardPaste: true,
        excludeName: null,
        beforeRender: null,
        afterRender: null,
        beforeShow: null,
        beforeSelect: null,
        onSelect: null,
        afterShow: null,
        onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl) {
            var filerKit = inputEl.prop("jFiler"),
                file_name = filerKit.files_list[id].name,
                id_image = filerKit.files_list[id].id_image;

            $.post(imageUploader, { action: 'deleteimage', actionpage: actionpage, image_name: file_name, id_image: id_image, ajax: '1' });
        },
        onEmpty: null,
        options: null,
        dialogs: {
            alert: function(text) {
                return alert(text);
            },
            confirm: function(text, callback) {
                confirm(confirm_delete_msg) ? callback() : null;
            }
        },
        captions: {
            button: choosefile,
            feedback: choosefiletoupload,
            feedback2: imagechoosen,
            drop: dragdropupload,
            removeConfirmation: confirm_delete_msg,
            errors: {
                filesLimit: only + " {{fi-limit}} " + imagesallowed,
                filesType: onlyimagesallowed,
                filesSize: "{{fi-name}} " + imagetoolarge + " {{fi-maxSize}} MB.",
                filesSizeAll: imagetoolargeall + " {{fi-maxSize}} MB."
            }
        }
    });

    function IsJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    $(document).on('click', '#uploadprofileimg', function() { // upload profile logo
        if ($('#profileuploader').css('display') == 'none') {
            $('#profileuploader').show('slow');
        } else {
            $('#profileuploader').hide('slow');
        }
    });

    $(document).on('click', '#uploadshoplogo', function() { // upload shop logo
        if ($('#shopuploader').css('display') == 'none') {
            $('#shopuploader').show('slow');
        } else {
            $('#shopuploader').hide('slow');
        }
    });

    $(document).on('click', '#uploadsellerbanner', function() { // Upload profile banner
        if ($('#profilebanneruploader').css('display') == 'none') {
            $('#profilebanneruploader').show('slow');
        } else {
            $('#profilebanneruploader').hide('slow');
        }
    });

    $(document).on('click', '#uploadshopbanner', function() { // upload shop banner
        if ($('#shopbanneruploader').css('display') == 'none') {
            $('#shopbanneruploader').show('slow');
        } else {
            $('#shopbanneruploader').hide('slow');
        }
    });

    $(document).on('click', '#uploadcollectionbanner', function() { //upload collection banner
        if ($('#collectionbanneruploader').css('display') == 'none') {
            $('#collectionbanneruploader').show('slow');
        } else {
            $('#collectionbanneruploader').hide('slow');
        }
    });
})