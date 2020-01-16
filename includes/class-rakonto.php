<?php

/**
 * The core Rakonto plugin class.
 */

class Rakonto {
    const MAGIC_TAG = '<div id="rakonto-magic" style="display: none;"></div>';
    const MAGIC_TAG_REGEX = '/rakonto-magic/';

    /** @var Rakonto_Loader $loader Maintains and registers all hooks for the plugin. */
    protected $loader;

    /** @var string $plugin_name The string used to uniquely identify this plugin. */
    protected $plugin_name;

    /** @var string $version The current version of the plugin. */
    protected $version;

    /** @var boolean $verbose_logging Whether or not to do verbose logging functions. */
    private $verbose_logging;

    public function __construct() {
        if (defined('PLUGIN_NAME_VERSION')) {
            $this->version = PLUGIN_NAME_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->plugin_name = 'rakonto';

        $this->verbose_logging = get_option('rakonto_verbose_logging') == 'on' ? true : false;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-rakonto-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-rakonto-i18n.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-rakonto-admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-rakonto-public.php';

        /** Composer autoloader dependency */
        require_once plugin_dir_path( dirname(__FILE__)) . 'vendor/autoload.php';

        $this->loader = new Rakonto_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization. Uses the Rakonto_i18n class in order to set the
     * domain and to register the hook with WordPress.
     */
    private function set_locale() {
        $plugin_i18n = new Rakonto_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     */
    private function define_admin_hooks() {
        $plugin_admin = new Rakonto_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        $this->loader->add_filter('wp_insert_post_data', $this, 'filter_post', 99, 2);
        $this->loader->add_action('save_post', $this, 'save_post', 99, 2);
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_options_page');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
        $this->loader->add_filter('plugin_action_links', $plugin_admin, 'register_action_links', 10, 2);

        $this->loader->add_action('show_user_profile', $plugin_admin, 'add_rakonto_user_fields');
        $this->loader->add_action('edit_user_profile', $plugin_admin, 'add_rakonto_user_fields');
        $this->loader->add_action('user_new_form', $plugin_admin, 'add_rakonto_user_fields');

        $this->loader->add_action('edit_user_profile_update', $plugin_admin, 'save_rakonto_user_fields');
        $this->loader->add_action('user_register', $plugin_admin, 'save_rakonto_user_fields');
        $this->loader->add_action('personal_options_update', $plugin_admin, 'save_rakonto_user_fields');

        $this->loader->add_action('add_meta_boxes_post', $plugin_admin, 'add_meta_boxes_cb');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     */
    private function define_public_hooks() {
        $plugin_public = new Rakonto_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Filter to add hidden 'magic' element for locating post content 
     * in a full rendered page.
     */ 
    public function filter_post($data, $postarr) {
        if ($data['post_type'] == 'attachment'       ||
           $data['post_type'] == 'nav_menu_item'    ||
           $data['post_type'] == 'custom_css'       ||
           $data['post_type'] == 'customize_changeset') {
                return $data;
        }

        if (!preg_match(self::MAGIC_TAG_REGEX, $data['post_content'])) {
            $data['post_content'] .= self::MAGIC_TAG;
        }

        return $data;
    }

    /**
     * Post is saved so now get live content and pass for processing...
     */ 
    public function save_post($ID, $post) {
        if($post->post_status == 'publish') {
            $post_data = [
                'url' => get_permalink($ID)
            ];
            $ch = curl_init('https://rakonto.net/api/fetch_inlined');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $content = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);            
            if($status != 400) {
                $this->post_published($ID, $post, $content);
            } else {
                if ($this->verbose_logging) {
                    error_log('Error fetching inlined content: ' . $content);
                }
            }
        }
    }

    /**
     * Post is published or updated, so hash...
     */ 
    private function post_published($ID, $post, $content) {

        // Don't do the blockchain request if the post is password protected
        if ($post->post_password != '')
            return;

        // Create and submit transaction with hash in OP_RETURN field...
        $current_user_id = get_current_user_id();
        $from_address = get_user_meta($current_user_id, 'litecoin_address', true);
        $from_key = array_key_exists('litecoin_private_key', $_POST) ? $_POST['litecoin_private_key'] : '';

        $should_use_global_address = get_option('rakonto_use_global_address') == 'on' ? true : false;

        if (!$should_use_global_address && !$from_address) {
            $url = get_edit_user_link();
            wp_redirect($url . '?rakonto_address_req=true');
            exit();
        }

        if (!$from_address || !$from_key) {
            $from_address = get_option("litecoin_global_address");
            $from_key = get_option("litecoin_private_key");
        }

        if (!$from_address || !$from_key) {
            if ($this->verbose_logging) {
                error_log('No address or key!');
            }
            return;
        }

        $obj = (object) [
            'url'       => get_permalink($ID),
            'timestamp' => date('c'),
            'content'   => $content,
            'hash'      => ''
        ];

        if ($this->verbose_logging) {
            error_log('Content published: ' . $content);
        }

        // Hash the content.
        $obj->hash = hash('sha1', $obj->content);

        if ($this->verbose_logging) {
            error_log('Content hash: ' . $obj->hash);
        }

        $content_json = json_encode($obj, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_UNESCAPED_UNICODE);

        $post_data = [
            'from'      => $from_address,
            'privkey'   => $from_key,
            'hash'      => $obj->hash,
            'url'       => $obj->url,
        ];

        // Transmit the data to the Rakonto service, which will submit the hash to the blockchain.
        $ch = curl_init('https://rakonto.net/api/send_tx');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $txid = curl_exec($ch);

        if ($this->verbose_logging) {
            error_log('tx post return:' . $txid);
        }

        curl_close($ch);

        // Store json.
        $store_path = get_home_path() . "rakonto-objects/";
        if(!is_dir($store_path)) {
            mkdir($store_path);
        }
        file_put_contents($store_path . $obj->hash . ".json", $content_json);

        if ($this->verbose_logging) {
            error_log("Object URL: " . home_url("/") . "rakonto-objects/" . $obj->hash . ".json");
        }
    }

    /** Utility function for getting the content type header from the given URL. */
    private function get_content_type($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        return curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
