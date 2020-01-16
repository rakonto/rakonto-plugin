<?php

/**
 * The admin-specific functionality of the plugin.
 */

class Rakonto_Admin {
    /** @var string $plugin_name The ID of this plugin. */
    private $plugin_name;

    /** @var string $version The current version of this plugin. */
    private $version;

    /** @var string $partials_dir The path of the directory in which partial templates are available. */
    private $partials_dir;

    /** @var boolean $use_global_address Whether or not to use the globally defined Litecoin address as per the settings. */
    private $use_global_address;

    /**
     * @var string $address_list_file The address list file for Rakonto, which contains a comma-delimited list of addresses that are
     * registered with the Rakonto plugin.
     */
    static public $address_list_file = 'rkta.txt';

    /** @var array $capabilitiesForRakonto An array of the WordPress capabilities which are associated with needing a Litecoin address. */
    static public $capabilitiesForRakonto = array(
        'publish_posts',
        'edit_posts',
        'edit_published_posts',
        'edit_others_posts',

        'publish_pages',
        'edit_pages',
        'edit_publish_pages',
        'edit_others_pages'
    );

    /**
     * Initialize the class and set its properties.
     *
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version           The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->partials_dir = plugin_dir_path(__FILE__) . 'partials/';
        $this->use_global_address = get_option('rakonto_use_global_address') == 'on' ? true : false;
    }

    /**
     * Adds the meta box for the Post create/edit page, which contains the private key for the user when per-user
     * addresses are being used.
     */
    public function add_meta_boxes_cb() {
        if ($this->use_global_address)
            return;

        add_meta_box('rakonto_post_key_box',
            '[Rakonto] Litecoin Private Key',
            array($this, 'post_meta_box_cb'),
            'post',
            'side',
            'low');
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rakonto-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts() {
        if (!$this->use_global_address)
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/rakonto-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Adds the Rakonto options page to the general WordPress settings menu.
     */
    public function add_options_page() {
        add_options_page(
            __($this->plugin_name . ' Settings'),
            ucfirst(__($this->plugin_name)),
            'manage_options',
            $this->plugin_name .'_settings',
            array($this, 'display_options_page')
        );
    }

    /**
     * View function for the Rakonto options page.
     */
    public function display_options_page() {
        include_once($this->partials_dir . 'rakonto-admin-display.php');
    }

    /**
     * Add plugin action links.
     *
     * @param array $links List of existing plugin action links.
     * @return array List of modified plugin action links.
     */
    public function register_action_links($links) {
        $matches = false;

        foreach($links as $key => $val) {
            $matches = (bool) preg_match("'plugin=rakonto'", $val);
        }

        if (!$matches)
            return $links;

        $links["activate"] = '<a href="' . esc_url(admin_url('/options-general.php?page=rakonto_settings')) . '">' . __('Settings', 'textdomain') . '</a>';
        return $links;
    }

    /**
     * Configure WordPress as required in order to save and display the Rakonto
     * options fields.
     */
    public function register_settings() {
        add_settings_section(
            $this->plugin_name . '_general',
            __('General'),
            array($this, 'general_settings_section_cb'),
            $this->plugin_name . '_settings'
        );

        add_settings_field(
            'rakonto_use_global_address',
            __('Use global Litecoin address'),
            array($this, 'rakonto_use_global_address_cb'),
            $this->plugin_name . '_settings',
            $this->plugin_name . '_general',
            array()
        );

        add_settings_field(
            'litecoin_global_address',
            __('Litecoin Address'),
            array($this, 'global_address_field_cb'),
            $this->plugin_name . '_settings',
            $this->plugin_name . '_general',
            array()
        );

        add_settings_field(
            'litecoin_global_private_key',
            __('Litecoin Private Key'),
            array($this, 'global_private_key_field_cb'),
            $this->plugin_name . '_settings',
            $this->plugin_name . '_general',
            array()
        );

        add_settings_field(
            'rakonto_verbose_logging',
            __('Verbose logging'),
            array($this, 'admin_verbose_logging_setting_cb'),
            $this->plugin_name . '_settings',
            $this->plugin_name . '_general',
            array()
        );

        register_setting($this->plugin_name . '_settings', 'litecoin_global_address', array($this, 'litecoin_global_address_field_sanitize'));
        register_setting($this->plugin_name . '_settings', 'litecoin_private_key', array($this, 'litecoin_private_key_field_sanitize'));
        register_setting($this->plugin_name . '_settings', 'rakonto_verbose_logging', array($this, 'rakonto_verbose_logging_sanitize'));
        register_setting($this->plugin_name . '_settings', 'rakonto_use_global_address', array($this, 'rakonto_use_global_address_sanitize'));
    }

    /**
     * Adds the user field to the user edit/create page.
     * @param object $user The user object for the user we are acting upon (or a string if new user).
     */
    public function add_rakonto_user_fields($user) {
        wp_enqueue_script($this->plugin_name . 'user_edit_page',
            plugin_dir_url(__FILE__) . 'js/rakonto-user-page.js', array('jquery'), $this->version, false);

        if (array_key_exists('rakonto_address_req', $_GET)) {
            wp_enqueue_script($this->plugin_name . 'user_page_no_address',
                plugin_dir_url(__FILE__) . 'js/rakonto-user-page-no-address.js', array('jquery'), $this->version, false);
        }

        $litecoin_address = '';

        if (is_object($user)) {
            $userRequiresAddress = $this->user_requires_address($user->ID);
            if (!$userRequiresAddress)
                return;

            $litecoin_address = get_user_meta($user->ID, 'litecoin_address', true) ?
                get_user_meta($user->ID, 'litecoin_address', true) : '';
        }

        $roles_requiring_address = implode(',', Rakonto_Admin::roles_requiring_address());

        require($this->partials_dir . 'rakonto-user-address-field.php');
    }

    private function add_address_to_rakonto_file($addr) {
        if (!$addr)
            return;

        $address_list_file = get_home_path() . Rakonto_Admin::$address_list_file;
        $address_raw = file_get_contents($address_list_file);
        $addresses = explode(',', $address_raw);

        $addrExists = false;
        foreach($addresses as $address) {
            if ($address == $addr) {
                $addrExists = true;
                break;
            }
        }

        if (!$addrExists) {
            $addresses[] = $addr;
            $updated_addresses_raw = implode(',', $addresses);
            file_put_contents($address_list_file, $updated_addresses_raw);
        }
    }

    public function save_rakonto_user_fields($uid) {
        if (!current_user_can('edit_user', $uid))
            return false;

        $updated_addr = $_POST['litecoin_address'];
        $this->add_address_to_rakonto_file($updated_addr);
        update_user_meta($uid, 'litecoin_address', $updated_addr);
    }

    /**
     * Callback for handling of general Rakonto settings section.
     */
    public function general_settings_section_cb() {
        wp_enqueue_script($this->plugin_name . 'settings-admin', plugin_dir_url(__FILE__) . 'js/rakonto-settings-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Callback for handling the display of the Litecoin address field on the Rakonto settings section.
     */
    public function global_address_field_cb() {
        $litecoin_global_address = get_option('litecoin_global_address') ?
            get_option('litecoin_global_address') : '';
        require($this->partials_dir . 'rakonto-global-address-field.php');
    }

    /**
     * Callback for handling the display of the Litecoin private KEY field on the Rakonto settings section.
     */
    public function global_private_key_field_cb() {
        $litecoin_private_key = get_option('litecoin_private_key') ? get_option('litecoin_private_key') : '';
        require($this->partials_dir . 'rakonto-global-private-key-field.php');
    }

    /**
     * Callback for handling the display of the global address usage admin setting.
     */
    public function rakonto_use_global_address_cb() {
        $use_global_address = get_option('rakonto_use_global_address') == 'on' ? ' checked' : '';
        require($this->partials_dir . 'rakonto-use-global-address-field.php');
    }

    /**
     * Callback for handling the display of the verbose logging admin setting.
     */
    public function admin_verbose_logging_setting_cb() {
        $verbose_logging = get_option('rakonto_verbose_logging') == 'on' ? ' checked' : '';
        require($this->partials_dir . 'rakonto-verbose-logging-field.php');
    }

    /**
     * Callback for the post page's meta box, containing a field for the private key for a user. Hidden via CSS.
     */
    public function post_meta_box_cb() {
        require($this->partials_dir . 'rakonto-admin-post-meta-box.php');
    }

    /**
     * Sanitize the value given by the user for the Litecoin wallet address field.
     * @param $val The original value of the field.
     * @return mixed The modified value of the field.
     */
    public function litecoin_global_address_field_sanitize($val) {
        $file_path = get_home_path() . Rakonto_Admin::$address_list_file;
        $file_contents = file_get_contents($file_path);

        $new_contents = '';

        if ($file_contents != '') {
            $items = explode(',', $file_contents);
            foreach($items as $item) {
                if ($item == $val)
                    return $val;
            }

            $new_contents = $file_contents . ',' . $val;
        } else {
            $new_contents = $val;
        }

        file_put_contents($file_path, $new_contents);

        return $val;
    }

    /**
     * Sanitize the value given by the user for the Litecoin private key field.
     * @param $val The original value of the field.
     * @return string A verified private key string.
     */
    public function litecoin_private_key_field_sanitize($val) {
        if (preg_match('/^[a-zA-Z0-9]*$/', $val))
            return $val;
        return '';
    }

    /**
     * Sanitize the value of the global address option.
     * @param $val The original value of the field.
     * @return string Either 'on' or an empty string, representing true and false.
     */
    public function rakonto_use_global_address_sanitize($val) {
        return $val === 'on' ? 'on' : '';
    }

    /**
     * Sanitize the value of the verbose logging option.
     * @param $val The original value of the field.
     * @return string Either 'on' or an empty string, representing true and false.
     */
    public function rakonto_verbose_logging_sanitize($val) {
        return $val === 'on' ? 'on' : '';
    }

    /**
     * Static function for determining whether or not a user will require a Litecoin address for Rakonto, based on
     * that user's permissions.
     * @param $uid The user ID for the user.
     * @return bool Whether or not the user will need a Litecoin address.
     */
    private static function user_requires_address($uid) {
        $userRequiresAddress = false;

        if (!$uid)
            return $userRequiresAddress;

        $user_data = get_userdata($uid);
        foreach($user_data->roles as $role) {
            if ($userRequiresAddress)
                break;

            $user_role = get_role($role);

            foreach($user_role->capabilities as $capability_name => $capability_val) {
                if (in_array($capability_name, Rakonto_Admin::$capabilitiesForRakonto) && $capability_val) {
                    $userRequiresAddress = true;
                    break;
                }
            }
        }

        return $userRequiresAddress;
    }

    /**
     * Get an array of all roles that would require an address.
     */
    private static function roles_requiring_address() {
        global $wp_roles;

        $rolesRequiringAddress = array();

        foreach ($wp_roles->roles as $name => $role) {
            foreach ($role['capabilities'] as $capability => $isCapable) {
                if (in_array($capability, Rakonto_Admin::$capabilitiesForRakonto) && $isCapable) {
                    $rolesRequiringAddress[] = $name;
                    break;
                }
            }
        }

        return $rolesRequiringAddress;
    }
}

