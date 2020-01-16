<?php
/**
 * Partial for displaying the user Litecoin address field on the user add/edit page.
 *
 * @var string $litecoin_address The Litecoin address for the user, as assigned via the user's profile edit page in WordPress.
 */
?>

<table class="form-table" id="rakonto-user-address-field">
    <tbody>
        <tr class="form-field form-required">
            <th scope="row">
                <label for="litecoin_address">Litecoin Address</label>
            </th>
            <td>
                <input type="text" name="litecoin_address" placeholder="Litecoin address" value="<?php echo $litecoin_address; ?>">
                <p class="description">
                    Enter your personal Litecoin address here for posting article hashes to the blockchain.
                </p>
            </td>
        </tr>
    </tbody>
</table>

<div class="hidden" id="data-rakonto-roles" data-rakonto-roles="<?php echo $roles_requiring_address; ?>"></div>
