<?php
/**
 * Partial for the global Litecoin private key field.
 *
 * @var string $litecoin_private_key The global Litecoin address for the Rakonto installation.
 */
?>

<p class="form-invalid">
    Please enter a valid private key (should contain only letters and numbers)
</p>
<input type="password" name="litecoin_private_key" value="<?php echo $litecoin_private_key; ?>">
<p class="description">
    The Litecoin private key for the address specified above. Note that this key <strong>is</strong>
    stored within the database (which is insecure by nature).
</p>