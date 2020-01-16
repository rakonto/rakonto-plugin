<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rakonto.net
 * @since      1.0.0
 *
 * @package    Rakonto
 * @subpackage Rakonto/public
 */

/**
 * The public-facing functionality of the plugin. Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 */

class Rakonto_Public {
    /** @var string $plugin_name The ID of this plugin. */
    private $plugin_name;

    /** @var string $version The current version of this plugin. */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/rakonto-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/rakonto-public.js', array('jquery'), $this->version, false);
    }

}
