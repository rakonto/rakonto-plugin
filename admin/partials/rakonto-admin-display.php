<?php

/**
 * Provide a admin area view for Rakonto.
 */
?>

<p>Define the behavior and configuration of the Rakonto plugin.</p>

<h1>Rakonto Settings</h1>

<form action="options.php" method="post" class="rakonto-options">
    <?php settings_fields( 'rakonto_settings' ); ?>
    <?php do_settings_sections( 'rakonto_settings' ); ?>
    <?php submit_button(); ?>
</form>
