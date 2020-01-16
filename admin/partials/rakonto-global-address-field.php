<?php
/**
 * Partial for the global Litecoin address field.
 *
 * @var string $litecoin_global_address The global Litecoin address for the Rakonto installation.
 */
?>

<p class="form-invalid">
    Please enter a valid address (should contain only letters and numbers)
</p>
<input type="text" name="litecoin_global_address" value="<?php echo $litecoin_global_address; ?>">
<p class="description">
    The Litecoin address from which to post all blockchain transaction.
</p>