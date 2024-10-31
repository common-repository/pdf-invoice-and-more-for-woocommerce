(function( $ ) {
    $(function() {

        // Add Color Picker to all inputs that have 'color-field' class
        $( '.cpa-color-picker' ).wpColorPicker();

    });

    var imageUpload;
    if(imageUpload){
        imageUpload.open();
        return;
    }

    $('.upload-header').on('click', function (element) {
        element.preventDefault();

            imageUpload = wp.media.frames.file_frame = wp.media({
                'title' : 'Please upload logo',
                'button' : {
                    'text' : 'Insert your logo'
                }
            });
            imageUpload.open();

            imageUpload.on('select', function () {
                logo_image = imageUpload.state().get('selection').first().toJSON();
                logo_url = logo_image.url;

                $('.wip_logo').val(logo_url);
                $('.change_logo').attr('src', logo_url);

            });

        imageUpload.open();
    });

    $('.upload-mark').on('click', function (element) {
        element.preventDefault();

        imageUpload = wp.media.frames.file_frame = wp.media({
            'title' : 'Please Upload Your Mark',
            'button' : {
                'text' : 'Insert Your Mark'
            }
        });
        imageUpload.open();

        imageUpload.on('select', function () {
            mark_image = imageUpload.state().get('selection').first().toJSON();
            mark_url = mark_image.url;

            $('.wip_mark').val(mark_url);
            $('.change_mark').attr('src', mark_url);

        });

        imageUpload.open();
    });


})( jQuery );
