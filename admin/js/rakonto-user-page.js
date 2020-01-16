(function( $ ) {
    'use strict';

    $(document).ready(function() {
        var rakonto_roles_data = $("#data-rakonto-roles").data("rakonto-roles").split(',');

        $('#profile-page #role').on('change', function(e) {
            var selected_role = $('#profile-page #role').val();
            var shouldAddressBeVisible = false;

            for (var i = 0; i < rakonto_roles_data.length; i++) {
                if (rakonto_roles_data[i] === selected_role) {
                    shouldAddressBeVisible = true;
                    break;
                }
            }

            var $user_address_field_el = $('#rakonto-user-address-field');
            if (shouldAddressBeVisible)
                $user_address_field_el.show();
            else
                $user_address_field_el.hide();
        });
    });
})(jQuery);