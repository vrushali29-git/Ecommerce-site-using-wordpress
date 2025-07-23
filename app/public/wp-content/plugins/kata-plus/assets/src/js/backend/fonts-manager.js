/**
 * FontsManager.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus Pro
 * @since	1.0.0
 */
'use strict';

jQuery(document).ready(function ($) {
    var $css_selector_row = '';
    var $t_s_counter = 0;
    var $variants_options = '';

    if ($('#kata_plus_pro_fonts_manager_custom_font_id_box').length) {
        $('#kata_plus_pro_fonts_manager_custom_font_id_box').on('input', function () {
            $.ajax({
                type: "post",
                url: ajaxurl,
                data: {
                    "action": "kata_plus_pro_get_font_data_by_custom_font_id",
                    "url": $(this).val()
                },
                success: function (response) {
                    if (response.length) {
                        $('#font_preview_wrap').html(response);
                        setTimeout(function () {
                            $('#font_preview_wrap iframe').each(function () {
                                $(this).contents().find('.copy-styles').on('click', function () {
                                    var VInput = $('<textarea style="position:fixed;opacity:0;top:-100;"></textarea>');
                                    VInput.text($(this).data('copy'));
                                    $('body').append(VInput);
                                    VInput[0].select();
                                    document.execCommand("copy");
                                    VInput.remove();
                                })
                            });
                        }, 2000);
                    }
                }
            });
        })
    }

    if ($('#kata_plus_pro_fonts_manager_typekit_id_box').length) {
        $('#kata_plus_pro_fonts_manager_typekit_id_box').on('input', function () {
            $.ajax({
                type: "post",
                url: ajaxurl,
                data: {
                    "action": "kata_plus_pro_get_font_data_by_typekit_id",
                    "typekit-id": $(this).val()
                },
                success: function (response) {
                    if (response.length) {
                        $('#font_preview_wrap').html(response);
                        setTimeout(function () {
                            $('#font_preview_wrap iframe').each(function () {
                                $(this).contents().find('.copy-styles').on('click', function () {
                                    var VInput = $('<textarea style="position:fixed;opacity:0;top:-100;"></textarea>');
                                    VInput.text($(this).data('copy'));
                                    $('body').append(VInput);
                                    VInput[0].select();
                                    document.execCommand("copy");
                                    VInput.remove();
                                })
                            });
                        }, 2000);
                    }
                }
            });
        })
    }

    if ($('#kata_plus_pro_change_fonts_view_type > span[data-id]').length) {
        $('#kata_plus_pro_change_fonts_view_type > span[data-id]').on('click', function () {
            var a = $('#kata_plus_pro_change_fonts_view_type > span.active');
            a.removeClass('active');
            $(this).addClass('active');
            $('#kata-plus-fonts-manager-add-font-table').removeClass(a.data('id'));
            $('#kata-plus-fonts-manager-add-font-table').addClass($(this).data('id'));
        })
    }

    if ($('#kata_plus_pro_alphabet_filter_select').length) {
        var list_page = 1;
        $('#kata_plus_pro_alphabet_filter_select').on('input', function () {
            var search = $(this).val().trim().toLowerCase();
            var fontsHtml = '';
            if (search.length == 0) {
                $('.search-result').fadeOut();
                $('.search-result').html('')
                $('.search-results-count').html('');
                if ($('#kata-plus-fonts-manager-add-font-table .font-results').css('display') == 'none') {
                    $('#kata-plus-fonts-manager-add-font-table .font-results').fadeIn(1000);
                }
                return false;
            }
            if (search != $(this).data('search')) {
                $('.search-result').html('');
                $(this).data('search', search);
            }
            var counter = 0,
                count = 0;
            $.each(kataGoogleFontsList, function (index, font) {
                var fontfamily = font.family.toLowerCase();
                if (fontfamily.indexOf(search) === 0) {
                    counter++;
                    if (counter <= 10 * list_page && counter > list_page * 10 - 10) {
                        count++;
                        var cloned = $('#fonts_box_html').clone();
                        cloned.find('.font-name-category b').html(font.family);
                        cloned.find('.font-name-category strong').html(font.category);
                        cloned.find('input[name="fontname"]').val(font.family);
                        cloned.find('.font-box-footer').before('<div class="kata-show-font-preview" data-src="' + cloned.find('.font-box').data('src') + font.family + '"></div>');
                        cloned.find('.font-styles-count').html(font.variants.length + ' Styles');
                        fontsHtml += cloned.html();
                        cloned.remove();
                    }
                }
            });

            var prevList = '',
                nextList = '';
            if (list_page > 1) {
                prevList = '<span class="prev-list filter hidden"></span>';
            }

            if (count + list_page * 10 - 10 !== counter) {
                nextList = '<span class="next-list filter hidden"></span>';
            }

            $('.search-results-count').html('Filter Results: ' + (count + list_page * 10 - 10) + ' of ' + counter + prevList + nextList);
            $('.search-result').append(fontsHtml);
            $('.search-result').fadeIn();

            if ($('#kata-plus-fonts-manager-add-font-table .font-results').css('display') != 'none') {
                $('#kata-plus-fonts-manager-add-font-table .font-results').fadeOut();
            }

        });
        $(document).on('click', '.next-list', function () {
            list_page++;
            $('#kata_plus_pro_alphabet_filter_select').trigger('input');
        });
        $(document).on('click', '.prev-list', function () {
            list_page--;
            $('#kata_plus_pro_alphabet_filter_select').trigger('input');
        });
    }

    if ($('#kata_plus_pro_fonts_manager_search_box').length) {
        var search_list_page = 1;
        $('#kata_plus_pro_fonts_manager_search_box').on('input', function () {
            var search = $(this).val().trim().toLowerCase();
            var fontsHtml = '';
            if (search.length == 0) {
                $('.search-result').fadeOut();
                $('.search-result').html('')
                $('.search-results-count').html('');
                if ($('#kata-plus-fonts-manager-add-font-table .font-results').css('display') == 'none') {
                    $('#kata-plus-fonts-manager-add-font-table .font-results').fadeIn(1000);
                }
                return false;
            }
            if (search != $(this).data('search')) {
                $('.search-result').html('');
                $(this).data('search', search);
            }

            var counter = 0,
                count = 0;
            $.each(kataGoogleFontsList, function (index, font) {
                var fontfamily = font.family.toLowerCase();
                var searchOk = false;
                $.each(font.variants, function (index, v) {
                    var variant = v.toLowerCase();
                    if (variant.indexOf(search) !== -1) {
                        searchOk = true;
                    }
                });
                $.each(font.subsets, function (index, s) {
                    var subset = s.toLowerCase();
                    if (subset.indexOf(search) !== -1) {
                        searchOk = true;
                    }
                });

                if (fontfamily.indexOf(search) !== -1 || searchOk === true) {
                    counter++;
                    if (counter <= 10 * search_list_page && counter > search_list_page * 10 - 10) {
                        count++;
                        var cloned = $('#fonts_box_html').clone();
                        cloned.find('.font-name-category b').html(font.family);
                        cloned.find('.font-name-category strong').html(font.category);
                        cloned.find('input[name="fontname"]').val(font.family);
                        cloned.find('.font-box-footer').before('<div class="kata-show-font-preview" data-src="' + cloned.find('.font-box').data('src') + font.family + '"></div>');
                        cloned.find('.font-styles-count').html(font.variants.length + ' Styles');
                        fontsHtml += cloned.html();
                        cloned.remove();
                    }
                }
            });

            var prevList = '',
                nextList = '';
            if (search_list_page > 1) {
                prevList = '<span class="prev-list search hidden"></span>';
            }

            if (count + search_list_page * 10 - 10 !== counter) {
                nextList = '<span class="next-list search hidden"></span>';
            }

            $('.search-results-count').html('Search Results: ' + (count + search_list_page * 10 - 10) + ' of ' + counter + prevList + nextList);
            $('.search-result').append(fontsHtml);
            $('.search-result').fadeIn();

            if ($('#kata-plus-fonts-manager-add-font-table .font-results').css('display') != 'none') {
                $('#kata-plus-fonts-manager-add-font-table .font-results').fadeOut();
            }

        });

        $(document).on('click', '.next-list.search', function () {
            search_list_page++;
            $('#kata_plus_pro_fonts_manager_search_box').trigger('input');
        });
        $(document).on('click', '.prev-list.search', function () {
            search_list_page--;
            $('#kata_plus_pro_fonts_manager_search_box').trigger('input');
        });
    }

    $(document).ready(function () {
        if ($('.fonts-manager-sticky').length) {
            if ($(window).scrollTop() < 300) {
                $('.fonts-manager-sticky').css({
                    'position': 'relative',
                    'top': 'auto',
                })
            }
            $(window).scroll(function (event) {
                $('.fonts-manager-sticky').getNiceScroll().resize();
                if ($(window).scrollTop() < 300) {
                    $('.fonts-manager-sticky').css({
                        'position': 'relative',
                        'top': 'auto',
                    })
                } else {
                    $('.fonts-manager-sticky').css({
                        'position': 'fixed',
                        'top': '60px'
                    })
                }
            });
        }
        if ($('.kata-plus-page.next').length) {
            var u = false,
                pg_list_page = 1;
            $(window).scroll(function (event) {
                if (!$('.kata-plus-page.next').length) {
                    return;
                }

                var scrollTop = $(this).scrollTop(),
                    offset = $('.kata-container').offset(),
                    top = offset.top;
                var bottom = $('.kata-container').height();
                bottom = top + bottom - $(window).height();
                if (scrollTop > bottom) {
                    if (u != true) {
                        jQuery('.kata-plus-fonts-manager-loading').css('display', 'block');
                        u = true;
                        pg_list_page++;
                        // $('.kata-plus-page.next').remove();
                        setTimeout(function () {

                            var fontsHtml = '<div class="kata-plus-fonts-manager-inner-page" data-page="' + pg_list_page + '">';

                            var counter = 0;
                            $.each(kataGoogleFontsList, function (index, font) {
                                counter++;
                                if (counter <= 10 * pg_list_page && counter > pg_list_page * 10 - 10) {
                                    var cloned = $('#fonts_box_html').clone();
                                    cloned.find('.font-name-category b').html(font.family);
                                    cloned.find('.font-name-category strong').html(font.category);
                                    cloned.find('input[name="fontname"]').val(font.family);
                                    cloned.find('.font-box-footer').before('<div class="kata-show-font-preview" data-src="' + cloned.find('.font-box').data('src') + font.family + '"></div>');
                                    cloned.find('.font-styles-count').html(font.variants.length + ' Styles');
                                    fontsHtml += cloned.html();
                                    cloned.remove();
                                } else if (counter > pg_list_page * 10 - 10) {
                                    return;
                                }
                            });

                            $('.fonts-pagination-result').append(fontsHtml + '<div class="clear"></div></div>');
                            jQuery('.kata-plus-fonts-manager-loading').css('display', 'none');
                            u = false;
                        }, 500);
                    }

                }
            });
        }
        if ($('.search-result').length) {
            var searchDelay = false;
            $(window).scroll(function (event) {
                if (!$('.next-list.search').length && !$('.next-list.filter').length) {
                    return;
                }
                var scrollTop = $(this).scrollTop(),
                    offset = $('.kata-container').offset(),
                    top = offset.top;
                var bottom = $('.kata-container').height();
                bottom = top + bottom - $(window).height();
                if (scrollTop > bottom) {
                    if (searchDelay != true) {
                        searchDelay = true;
                        jQuery('.kata-plus-fonts-manager-loading').css('display', 'block');
                        setTimeout(function () {
                            if ($('.next-list.search').length) {
                                $('.next-list.search').trigger('click');
                            }

                            if ($('.next-list.filter').length) {
                                $('.next-list.filter').trigger('click');
                            }

                            jQuery('.kata-plus-fonts-manager-loading').css('display', 'none');
                            searchDelay = false;
                        }, 500);
                    }

                }
            });
        }
    });

    $(document).on('click', '.font-pack.has-iframe .remove', function () {
        var c = confirm('Are you sure?');
        if (c) {
            $(this).parent().remove();
        }
        return;
    })
    $(document).on('click', '.kata-show-font-preview', function () {
        if (typeof ($(this).data('src')) != 'undefined') {
            var $this = $(this),
                $wrap = $this.parent('.font-box'),
                $input = $wrap.closest('.kata-plus-fonts-manager-wrap').find('.kata-plus-preview-text-box');
            $this.after('<iframe src="' + $this.data('src') + '"></iframe>');
            $this.remove();
            $wrap.append('<div class="preloader-parent"><div class="lds-ripple kata-plus-fonts-manager-loading"> <div></div> <div></div> </div></div>');

            $.ajax({
                action: 'kata_plus_pro_fonts_manager_font_preview',
                complete: function () {
                    console.log($wrap.find('.preloader-parent'));
                    $('#kata-plus-fonts-manager-add-font-table iframe').each(function () {
                        if ($input.val() == '') {
                            $(this).contents().find('.font-preview-text').html($input.attr('placeholder'));
                        } else {
                            $(this).contents().find('.font-preview-text').html($input.val());
                        }
                    });
                    $wrap.find('.preloader-parent').remove();
                }
            });
        }
    });

    if ($('#kata_plus_pro_fonts_manager_google_categories').length) {
        $('#kata_plus_pro_fonts_manager_google_categories li.kata-plus-google-categories-go').on('click', function () {
            var categories = [];
            $('#kata_plus_pro_fonts_manager_google_categories input[type="checkbox"]').each(function () {
                if ($(this).is(':checked')) {
                    categories.push($(this).val());
                }
            });
            window.location.href = $(this).data('url') + '&cat=' + categories.join(',');
        });

        $(document).click(function (e) {
            if (e.target.id != 'kata_plus_pro_fonts_manager_google_categories' && $(e.target).parents('#kata_plus_pro_fonts_manager_google_categories').length == 0) {
                $('#kata_plus_pro_fonts_manager_google_categories').find('ul').removeClass('show');
                $('#kata_plus_pro_fonts_manager_google_categories').find('strong').removeClass('active');
            }
        });

        $('#kata_plus_pro_fonts_manager_google_categories strong').on('click', function () {
            var $categoriesWrap = $('#kata_plus_pro_fonts_manager_google_categories');
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $categoriesWrap.find('ul').removeClass('show');

            } else {
                $(this).addClass('active');
                $categoriesWrap.find('ul').addClass('show');
            }
        });

    }

    if ($('.kata-plus-preview-font-size-wrap input[type="range"]').length) {
        $('.kata-plus-preview-font-size-wrap input[type="range"]').on('input', function () {
            var $val = $(this).val();
            $('#kata-plus-fonts-manager-add-font-table iframe').each(function () {
                $(this).contents().find('.font-preview-text').css('font-size', $val + 'px');
                $('#kata_plus_pro_fonts_manager_preview_text_font_size').html($val + 'px');
                $(this).height($(this).contents().find("html").height());
            });
        })

        $('.kata-plus-preview-font-size-wrap input[type="range"]').on('change', function () {
            var $val = $(this).val();
            $.ajax({
                type: "post",
                url: ajaxurl,
                data: {
                    action: 'fonts-manager-change-font-preview-font-size',
                    fontSize: $val
                },
            });
        })
    }

    if ($('#kata_plus_pro_fonts_manager_preview_text').length) {
        $('#kata_plus_pro_fonts_manager_preview_text').on('input', function () {

            var $val = $(this).val(),
                $placeholder = $(this).attr('placeholder');
            $('#kata-plus-fonts-manager-add-font-table iframe').each(function () {
                if( $val ) {
                    $(this).contents().find('.font-preview-text').html($val);
                } else {
                    $(this).contents().find('.font-preview-text').html($placeholder);
                }
            });
        })
    }

    if ($('.kata-plus-fonts-manager-wrap #exportFonts').length) {
        $(document).on('click', '.kata-plus-fonts-manager-wrap #exportFonts', function () {
            $(this).parents('form').first().trigger('submit');
        })
    }

    if ($('.kata-plus-fonts-manager-wrap #importFonts').length) {
        $(document).on('click', '.kata-plus-fonts-manager-wrap #importFonts', function () {
            $(this).parents('form').first().trigger('submit');
        })
    }

    if ($('.kata-plus-fonts-manager-wrap #generate_preview').length) {
        $('.kata-plus-fonts-manager-wrap #generate_preview').on('click', function () {
            var css_font = $('#font_css_url').val();

            if (!css_font) {
                $('#font_css_url').focus();
                return false;
            }

            if ($(this).data('source') == 'typekit' || $(this).data('source') == 'custom-font') {
                var font_name = '';
                $("input[name='font_family[]']").map(function () {
                    if ($(this).val()) {
                        font_name = font_name + $(this).val() + ",";
                    }
                }).get();

                font_name = font_name.slice(0, -1);
                var iframeUrl = ajaxurl + '?action=kata_plus_pro_fonts_manager_font_preview&font-family=' + font_name + '&source=' + $(this).data('source') + '&url=' + css_font;
                var iframe = $('<iframe src="' + iframeUrl + '"></iframe>');
                iframe.on('load', function () {
                    this.style.height = this.contentWindow.document.body.offsetHeight + 'px';
                });
                $('#live_preview_content').html(iframe);
            }
        });
    }

    if ($('.kata-plus-fonts-manager-wrap #add_another_font_family').length) {
        $('.kata-plus-fonts-manager-wrap #add_another_font_family').on('click', function () {
            var content = '<div class="kata-plus-fonts-manager-font-family-group">';
            content += '<div class="col col-9">';
            content += '<input type="text" name="font_family[]" class="kata-plus-fonts-manager-add-new-font-selector-input full">';
            content += '</div>';
            content += '<div class="col col-1">';
            content += '<div class="remove-selector" onclick=$(this).parent().parent().remove();><span class="dashicons dashicons-no-alt"></span></div>';
            content += '</div>';
            content += '</div>';
            $('.kata-plus-fonts-manager-wrap #font_family_container').append(content);
        });
    }

    if ($('input:radio[name="fontname"]').length) {
        $(document).on('change', 'input:radio[name="fontname"]', function () {
            if ($(this).is(':checked')) {
                $('.kata-plus-fonts-manager-font-family .name').html($(this).val());
                $('.font-box').each(function () {
                    $(this).removeClass('active');
                })
                $(this).parents('.font-box').addClass('active');

                $('.fonts-manager-sticky').append('<div class="preloader-parent"><div class="lds-ripple kata-plus-fonts-manager-loading"> <div></div> <div></div> </div></div>');
                $('.fonts-manager-sticky').css('opacity', '0.5');
                var getFontDataUrl = ajaxurl + '?action=kata_plus_pro_fonts_manager_get_font_data&font-family=' + $(this).val() + '&source=' + $(this).data('source');
                $.ajax({
                    type: "get",
                    url: getFontDataUrl,
                    dataType: 'json',
                    success: function (response) {
                        $("#fonts_manager_font_variants").html(response.variants);

                        if (typeof response.subsets != undefined) {
                            $("#fonts_manager_font_subsets").html(response.subsets);
                        }

                        if (typeof response.preview != undefined) {
                            $("#live_preview_content").html(response.preview);
                        }

                        $variants_options = response.variants_options;
                        $(".kata-plus-fonts-manager-add-new-font-selector-select-variant").each(function () {
                            $(this).html(response.variants_options);
                        });
                        $('.fonts-manager-sticky').getNiceScroll().resize();
                        $('.fonts-manager-sticky').find('.preloader-parent').remove();
                        $('.fonts-manager-sticky').css('opacity', '1');                        
                    }
                });
            }
        });
    }

    if ($('.kata-plus-fonts-manager-css-selector-row-hidden').length) {
        $css_selector_row = $('.kata-plus-fonts-manager-css-selector-row-hidden').html();
        var b = $css_selector_row.replace(/\[_SELECTOR_ID_]/g, '[offset_' + $t_s_counter + ']');
        $('.kata-plus-fonts-manager-css-selector-row-hidden').html(b.replace(/\_SELECTOR_ID_/g, $t_s_counter))
        $('.kata-plus-fonts-manager-wrap #add-new-section').on('click', function () {
            if ($variants_options == '') {
                $('#fonts_manager_font_variants input:checkbox').each(function () {
                    $variants_options += '<option value="' + $(this).val() + '">' + $(this).val() + '</option>';
                })
            }
            $t_s_counter++;
            console.log('Added Selector : ' + $t_s_counter);
            var $css_selector_row_cloned = $('<div class="row css-selector-row">' + $css_selector_row + '</div>');
            $css_selector_row_cloned.find('.kata-plus-fonts-manager-add-new-font-selector-select-variant').html($variants_options);
            var d = $css_selector_row_cloned.html().replace(/\[_SELECTOR_ID_]/g, '[offset_' + $t_s_counter + ']');

            $('.css-selector-wrap').append('<div class="row css-selector-row">' + d.replace(/\_SELECTOR_ID_/g, $t_s_counter) + '</div>');
        });
    }

    $('.postbox-container').find('.postbox').find('.handlediv').on('click', function () {
        var $this = $(this),
            $wrap = $this.closest('.postbox'),
            $inside = $wrap.find('.inside')
        $inside.slideToggle(400);
        $this.toggleClass('closed');
    });
});

jQuery.fn.exists = function () {
    return jQuery(this).length > 0;
}
jQuery(document).ready(function ($) {

    if ($(".plupload-upload-uic").exists()) {
        var pconfig = false;
        $(".plupload-upload-uic").each(function () {
            var $this = $(this);
            var id1 = $this.attr("id");
            var imgId = id1.replace("plupload-upload-ui", "");

            plu_show_thumbs(imgId);

            pconfig = JSON.parse(JSON.stringify(base_plupload_config));

            pconfig["browse_button"] = imgId + pconfig["browse_button"];
            pconfig["container"] = imgId + pconfig["container"];
            pconfig["drop_element"] = imgId + pconfig["drop_element"];
            pconfig["file_data_name"] = imgId + pconfig["file_data_name"];
            pconfig["multipart_params"]["imgid"] = imgId;
            pconfig["multipart_params"]["_ajax_nonce"] = $this.find(".ajaxnonceplu").attr("id").replace("ajaxnonceplu", "");

            if ($this.hasClass("plupload-upload-uic-multiple")) {
                pconfig["multi_selection"] = true;
            }

            if ($this.find(".plupload-resize").exists()) {
                var w = parseInt($this.find(".plupload-width").attr("id").replace("plupload-width", ""));
                var h = parseInt($this.find(".plupload-height").attr("id").replace("plupload-height", ""));
                pconfig["resize"] = {
                    width: w,
                    height: h,
                    quality: 90
                };
            }
            console.log(pconfig);

            var uploader = new plupload.Uploader(pconfig);
            console.log(uploader);

            uploader.bind('Init', function (up) {
                console.log(up);

            });

            uploader.init();

            // a file was added in the queue
            uploader.bind('FilesAdded', function (up, files) {
                $.each(files, function (i, file) {
                    $this.find('.filelist').append('<div class="file" id="' + file.id + '"><b>' +

                        file.name + '</b> (<span>' + plupload.formatSize(0) + '</span>/' + plupload.formatSize(file.size) + ') ' + '<div class="fileprogress"></div></div>');
                });

                up.refresh();
                up.start();
            });

            uploader.bind('UploadProgress', function (up, file) {

                $('#' + file.id + " .fileprogress").width(file.percent + "%");
                $('#' + file.id + " span").html(plupload.formatSize(parseInt(file.size * file.percent / 100)));
            });

            // a file was uploaded
            uploader.bind('FileUploaded', function (up, file, response) {


                $('#' + file.id).fadeOut();
                response = response["response"]
                // add url to the hidden field
                if ($this.hasClass("plupload-upload-uic-multiple")) {
                    // multiple
                    var v1 = $.trim($("#" + imgId).val());
                    if (v1) {
                        v1 = v1 + "," + response;
                    } else {
                        v1 = response;
                    }
                    $("#" + imgId).val(v1);
                } else {
                    // single
                    $("#" + imgId).val(response + "");
                }
                // show thumbs
                plu_show_thumbs(imgId);
            });
        });
    }
});

function plu_show_thumbs(imgId) {
    var $ = jQuery;
    var thumbsC = $("#" + imgId + "plupload-thumbs");
    thumbsC.html("");
    // get urls
    var imagesS = $("#" + imgId).val();
    var images = imagesS.split(",");
    for (var i = 0; i < images.length; i++) {
        if (images[i]) {
            var thumb = $('<div class="thumb" id="thumb' + imgId + i + '"><img src="' + images[i] + '" alt="" /><div class="thumbi"><a id="thumbremovelink' + imgId + i + '" href="#">Remove</a></div> <div class="clear"></div></div>');
            thumbsC.append(thumb);
            thumb.find("a").click(function () {
                var ki = $(this).attr("id").replace("thumbremovelink" + imgId, "");
                ki = parseInt(ki);
                var kimages = [];
                imagesS = $("#" + imgId).val();
                images = imagesS.split(",");
                for (var j = 0; j < images.length; j++) {
                    if (j != ki) {
                        kimages[kimages.length] = images[j];
                    }
                }
                $("#" + imgId).val(kimages.join());
                plu_show_thumbs(imgId);
                return false;
            });
        }
    }
    if (images.length > 1) {
        thumbsC.sortable({
            update: function (event, ui) {
                var kimages = [];
                thumbsC.find("img").each(function () {
                    kimages[kimages.length] = $(this).attr("src");
                    $("#" + imgId).val(kimages.join());
                    plu_show_thumbs(imgId);
                });
            }
        });
        thumbsC.disableSelection();
    }
}