jQuery(document).ready( function($) {
    var pointerContentIndex = 0;
    var current_pointer = 0;
    var previous_pointer = 0;
    kata_open_pointer(0);
    function kata_open_pointer(i) {
        var pointer_num = kataPointer.pointers;
        var pointer = kataPointer.pointers[i];
        options = $.extend( pointer.options, {
            close: function() {
                $.post( ajaxurl, {
                    action: 'kata_disable_tour_mode'
                });
            }
        });
 
        $(pointer.target).pointer( options ).pointer('open');
        console.log('i:' + i)
        if ( i === 0 ){
            jQuery( 'a.close' ).after('<a href="#" class="kata-tour-next button-primary">Next</a>')
        } else if(  i == parseInt(pointer_num.length) - 1 ) {
            jQuery( 'a.close' ).after('<a href="#" class="kata-tour-back button-primary">Back</a>')
        } else {
            jQuery( 'a.close' ).after('<a href="#" class="kata-tour-next button-primary">Next</a>')
            jQuery( 'a.close' ).after('<a href="#" class="kata-tour-back button-primary">Back</a>')
        }
         
        
    }

    jQuery( 'body' ).on( 'click', 'a.kata-tour-next', function(e){
        e.preventDefault();
        pointer = kataPointer.pointers;
        var main_id = jQuery(this).parents('.wp-pointer').attr('id');
        var init_id = main_id.match("wp-pointer-(.*)");
        previous_pointer = parseInt(init_id[1]);
        current_pointer = parseInt(init_id[1]) + 1;

        jQuery(this).parents('.wp-pointer').hide();
        if( pointerContentIndex < pointer.length  ){
            ++pointerContentIndex;
        }
        
        //Open the next pointer
        kata_open_pointer(pointerContentIndex);
         
    });

    jQuery( 'body' ).on( 'click', 'a.kata-tour-back', function(e){
        e.preventDefault();
        pointer = kataPointer.pointers;
        main_id = jQuery(this).parents('.wp-pointer').attr('id');
        init_id = main_id.match("wp-pointer-(.*)");
        var do_action_pointer = parseInt(init_id[1]) - 1;
        pointerContentIndex = pointerContentIndex - 1;

        jQuery(this).parents('.wp-pointer').hide();

        //Open the prev pointer
        kata_open_pointer(do_action_pointer);
         
    });
});