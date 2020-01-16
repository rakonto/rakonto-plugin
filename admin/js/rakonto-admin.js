(function( $ ) {
    'use strict';

    $(document).ready(function() {
        // First create the content element for the ThickBox dialog.
        $('form[action="post.php"]').append(
            '<div id="rakonto_private_key_tb" style="display: none">' +
                '<div style="margin-bottom: 30px">In order to publish this article, you must enter your Litecoin private key below, or via the meta box in the sidebar.</div>' +
                '<strong>Private Key: </strong><input type="password" name="litecoin_private_key_tb_input">' +
                '<div style="margin-top: 30px;"><input type="submit" value="Submit Key" id="rakonto_tb_pkey_submit"></div>' +
            '</div>'
        );

        // Bind the ThickBox dialog submit button click action to a function.
        $('#rakonto_tb_pkey_submit').on('click', function(e) {
            e.preventDefault();
            var rakontoPrivateKeyTbVal = $('input[name="litecoin_private_key_tb_input"]').val();
            $('input[name="litecoin_private_key"]').val(rakontoPrivateKeyTbVal);
            tb_remove();
            $('form[action="post.php"] input#publish').trigger('click');
        });

        // Attach a click listener to the publish button to display the ThickBox dialog.
        $('form[action="post.php"] input#publish').on('click', function(e) {
            var isPublicVisibility = $('#post-visibility-select #visibility-radio-public').is(':checked');
            if (!isPublicVisibility)
                return;

            var privateKeyVal = $("input[name='litecoin_private_key']").val();
            if (privateKeyVal !== '') {
                return;
            }

            e.preventDefault();
            var url = "#TB_inline?width=450&height=150&inlineId=rakonto_private_key_tb&modal=false";
            tb_show("Rakonto Plugin", url);

            var tb_width = 500;
            var tb_height = 200;

            var $tb_el = $('#TB_window');
            $tb_el.attr('style', '');
            $tb_el.css('visibility', 'visible');

            $tb_el.css('width', tb_width);
            $tb_el.css('height', tb_height);
            $tb_el.css('margin-left', -(tb_width / 2));
            $tb_el.css('margin-top', -(tb_height / 2));
        });
    });
})(jQuery);
