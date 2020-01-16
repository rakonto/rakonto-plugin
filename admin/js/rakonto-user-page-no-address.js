(function( $ ) {
    'use strict';

    $(document).ready(function() {
        var msg = "The Rakonto plugin requires that you set a Litecoin address value on your user profile before " +
            "saving or editing a post. Edit the address field below, then resubmit your post.";
        $('<p style="color: red">' + msg + '</p>').insertAfter('#your-profile h2:first');

        $('#rakonto-user-address-field tr').addClass('form-invalid');
    });
})(jQuery);