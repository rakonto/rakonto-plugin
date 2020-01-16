<?php
/**
 * Partial for the checkbox for choosing whether or not to use the global Litecoin address.
 *
 * @var string $use_global_address The current value of whether or not to use the global Litecoin address.
 */
?>

<input type="checkbox" name="rakonto_use_global_address" <?php echo $use_global_address; ?>></input>
<p class="description">
    Whether or not to use a single, global Litecoin address for the plugin. This is opposed to having
    a private address for each individual WordPress user (recommended).
</p>
