(function( $ ) {
    'use strict';

    $(document).ready(function() {
        function isStringValid(address) {
            if (address == '')
                return false;

            var matches = /^[a-zA-Z0-9]*$/.exec(address);
            var matchesLength = (matches === null) ? 0 : matches.length;
            if (matchesLength > 0)
                return true;
            return false;
        }

        if (!$('input[name="rakonto_use_global_address"]').prop('checked')) {
            $('input[name="litecoin_global_address"]').parent().parent().css('display', 'none');
            $('input[name="litecoin_private_key"]').parent().parent().css('display', 'none');
        }

        $('input[name="rakonto_use_global_address"]').on('change', function() {
            $('input[name="litecoin_global_address"]').parent().parent().toggle();
            $('input[name="litecoin_private_key"]').parent().parent().toggle();
        });

        $('form[action="options.php"] input[type="submit"]').on('click', function(e) {
            if($('input[name="rakonto_use_global_address"]').val() !== 'on')
                return;

            var litecoin_address = $('input[name="litecoin_global_address"]').val();
            var addressValid = isStringValid(litecoin_address);

            var litecoin_private_key = $('input[name="litecoin_private_key"]').val();
            var privateKeyValid = isStringValid(litecoin_private_key);

            if (!addressValid || !privateKeyValid) {
                e.preventDefault();
                // show validation messages

                var invalidationClass = 'form-invalid';
                var visibilityClass = "visible";

                $('input[name="litecoin_global_address"]').parent().parent().removeClass(invalidationClass);
                $('input[name="litecoin_global_address"]').parent().find('p.form-invalid').removeClass(visibilityClass);

                $('input[name="litecoin_private_key"]').parent().parent().removeClass(invalidationClass);
                $('input[name="litecoin_private_key"]').parent().find('p.form-invalid').removeClass(visibilityClass);

                if (!addressValid) {
                    $('input[name="litecoin_global_address"]').parent().parent().addClass(invalidationClass);
                    $('input[name="litecoin_global_address"]').parent().find('p.form-invalid').addClass(visibilityClass);
                }

                if (!privateKeyValid) {
                    $('input[name="litecoin_private_key"]').parent().parent().addClass(invalidationClass);
                    $('input[name="litecoin_private_key"]').parent().find('p.form-invalid').addClass(visibilityClass);
                }
            }
        });
    });
})(jQuery);