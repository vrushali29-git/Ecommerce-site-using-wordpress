/**
 * Dialog.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
'use strict';

var Kata_Plus_Media_Upload = (function ($) {
    return {
        /**
         * Init.
         * 
         * @since	1.0.0
         */
        init: function () {
            var self = this;
            this.mediaUpload();
            $(document).on('DOMNodeInserted', function(e) {
                self.mediaUpload();
            });
        },
        mediaUpload: function () {
            $('.widget-inside').each(function() {
                var $widgetContainer = $(this);
                
                $widgetContainer.find('.kata-add-media-upload').off().on('click', function (e) {
                    e.preventDefault();

                    var $addImageBtn = $(this);
                    var $deleteImageBtn = $addImageBtn.siblings('.kata-remove-media-upload');
                    var $imageContainer = $addImageBtn.siblings('.kata-preview-media-upload');
                    var $inputMediaUpload = $addImageBtn.siblings('input.kata-input-media-upload');
                    var value = $inputMediaUpload.val();
    
                    value = value ? value : '';
    
                    // Create a new media frame
                    var frame = wp.media({
                        multiple: false // Set to true to allow multiple files to be selected
                    });
    
                    // Open the modal on click
                    frame.open();
    
                    // When an image is selected in the media frame...
                    frame.on('select', function () {
                        // Get media attachment details from the frame state
                        var attachment = frame.state().get('selection').first().toJSON();
                        // Send the attachment URL to our custom image input field.
                        $imageContainer.html('').append('<img src="' + attachment.url + '" alt="">').find('img').css('display', 'block');
                        // Send the attachment id to our hidden input
                        $inputMediaUpload.val(attachment.id).trigger('change');
                        // Show the remove image link
                        $deleteImageBtn.show();
                    });
                });
    
                // Delete image link
                $widgetContainer.find('.kata-remove-media-upload').off().on('click', function (event) {
                    event.preventDefault();
                    var $deleteImageBtn = $(this);
                    var $imageContainer = $deleteImageBtn.siblings('.kata-preview-media-upload');
                    var $inputMediaUpload = $deleteImageBtn.siblings('input.kata-input-media-upload');
                    // Clear out the preview image
                    $imageContainer.html('').hide();
                    // Hide the delete image link
                    $deleteImageBtn.hide();
                    // Delete the image id from the hidden input
                    $inputMediaUpload.val('').trigger('change');
                });
            });
        }
    };
})(jQuery);

jQuery(document).ready(function () {
    Kata_Plus_Media_Upload.init();
});