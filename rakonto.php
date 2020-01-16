<?php
/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rakonto.net
 * @since             0.1.0
 * @package           Rakonto
 *
 * @wordpress-plugin
 * Plugin Name:       Rakonto
 * Plugin URI:        https://bitbucket.org/EdelmanDigitalNY/rakonto
 * Description:       The Rakonto WordPress Plugin for content publishing verification.
 * Version:           0.1.2
 * Author:            Rakonto
 * Author URI:        https://rakonto.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rakonto
 * Domain Path:       /languages
 * Bitbucket Plugin URI: https://bitbucket.org/EdelmanDigitalNY/rakonto
 * Bitbucket Branch:     master
 */

ob_clean();
ob_start();

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('PLUGIN_NAME_VERSION', '1.0.1');

/**
 * The code that runs during plugin activation.
 */
function activate_rakonto() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-rakonto-activator.php';
    Rakonto_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_rakonto() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-rakonto-deactivator.php';
    Rakonto_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_rakonto');
register_deactivation_hook(__FILE__, 'deactivate_rakonto');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-rakonto.php';

/**
 * Begins execution of the plugin.
 */
function run_rakonto() {
    $plugin = new Rakonto();
    $plugin->run();
}
run_rakonto();
