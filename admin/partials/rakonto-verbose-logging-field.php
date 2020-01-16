<?php
/**
 * Partial for the checkbox for using verbose logging.
 *
 * @var string $verbose_logging The current value of whether or not to use the verbose logging option.
 */
?>

<input type="checkbox" name="rakonto_verbose_logging" <?php echo $verbose_logging; ?>></input>
<p class="description">
    Whether or not to output additional log messages to the web server's logging system. Such messages
    are meant for debugging purposes and are not neccessary to the operation of the plugin.
</p>
