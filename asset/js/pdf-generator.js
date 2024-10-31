jQuery(document).ready(function () {
    (function ($) {

        // $('.pdfg_opt_wrapper .invoice').click(function(){
        //
        //     var link = this;
        //     $(link).html('loading...');
        //     //var post_id = "$(link).attr('href').replace(/^.*#more-/, '')";
        //     var data = {
        //         action: 'boj_arm_ajax',
        //         post_id: '1'
        //     };
        //     $.get('custom_pdf.ajaxurl', data, function(data){
        //         $(link).after(data).remove();
        //     });
        //     return false;
        // });
    })(jQuery);

});


(function ($) {
    $(document).ready(function () {
        $('.pdfg_opt_wrapper').on('click', '.wipg-download-btn', function (e) {
            e.preventDefault();// prevent default action of the button
            // prepare the data to send with the ajax request
            let $wrapper = $(".pdfg_opt_wrapper"),
                data = {
                    'action': 'download_wi_pdf',
                    'order_id': $(this).data('order-id') // get the data from data-order-id attr
                };
            //    making ajax request

            $.ajax({
                url: wip_js_object.ajax_url, // AJAX handler
                data: data,
                type: 'POST',
                // beforeSend: function (xhr) {
                //     $wrapper.text(wip_js_object.loading_text); // change the button text, you can also add a preloader image
                // },
                success: function (data) {
                window.location = data;



                }
            });


        })



    });
})(jQuery);








