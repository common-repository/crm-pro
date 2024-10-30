<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ghldevs.com/team/rakhi-sharma
 * @since      1.0.0
 *
 * @package    Crm_Pro
 * @subpackage Crm_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Crm_Pro
 * @subpackage Crm_Pro/admin
 * @author     rakhi Sharma <rakhi@ghldevs.com>
 */
class Crm_Pro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the setting pages and their menu items
	 * 
	 * @since 1.0.0
	 */
	public function build_menu() {
		$required_cap = $this->get_required_capability();

		$menu_items = array(
			array(
				'title'    => esc_html__( 'API Settings', 'crm-pro' ),
				'text'     => 'API Settings',
				'slug'     => '',
				'callback' => array( $this, 'show_api_setting_page' ),
				'position' => 0,
			),
			array(
				'title'    => esc_html__( 'Chat Settings', 'crm-pro' ),
				'text'     => esc_html__( 'Chat', 'crm-pro' ),
				'slug'     => 'chat',
				'callback' => array( $this, 'show_chat_setting_page' ),
				'position' => 80,
			),
			array(
				'title'    => esc_html__( 'Forms Settings', 'crm-pro' ),
				'text'     => esc_html__( 'Forms', 'crm-pro' ),
				'slug'     => 'form',
				'callback' => array( $this, 'show_form_setting_page' ),
				'position' => 85,
			)
		);

		/**
		 * Filters the menu items to appear under the main menu item.
		 *
		 * To add your own item, add an associative array in the following format.
		 *
		 * $menu_items[] = array(
		 *     'title' => 'Page title',
		 *     'text'  => 'Menu text',
		 *     'slug' => 'Page slug',
		 *     'callback' => 'my_page_function',
		 *     'position' => 50
		 * );
		 *
		 * @param array $menu_items
		 * @since 1.0
		 */
		$menu_items = (array) apply_filters( 'crm_pro_admin_menu_items', $menu_items );

		// add top menu item
		$icon = file_get_contents( CRM_PRO_ABSPATH . 'admin/img/icon.svg' );
		add_menu_page( __('CRM Pro', 'crm-pro'), __('CRM Pro', 'crm-pro'), $required_cap, 'crm-pro', array( $this, 'show_api_setting_page' ), 'data:image/svg+xml;base64,' . base64_encode( $icon ), '55.68491' );

		// sort submenu items by 'position'
		usort( $menu_items, array( $this, 'sort_menu_items_by_position' ) );

		// add sub-menu items
		foreach ( $menu_items as $item ) {
			$this->add_menu_item( $item );
		}

	}

	/**
	 * Add menu item
	 * 
	 * @param array $item
	 * @since 1.0.0
	 */
	public function add_menu_item( array $item ) {

		// generate menu slug
		$slug = 'crm-pro';
		if ( ! empty( $item['slug'] ) ) {
			$slug .= '-' . $item['slug'];
		}

		// provide some defaults
		$parent_slug = ! empty( $item['parent_slug'] ) ? $item['parent_slug'] : 'crm-pro';
		$capability  = ! empty( $item['capability'] ) ? $item['capability'] : $this->get_required_capability();

		// register page
		$hook = add_submenu_page( $parent_slug, $item['title'] . ' - CRM PRO', $item['text'], $capability, $slug, $item['callback'] );

		// register callback for loading this page, if given
		if ( array_key_exists( 'load_callback', $item ) ) {
			add_action( 'load-' . $hook, $item['load_callback'] );
		}
	}

	/**
	 * Show the API Settings page
	 * 
	 * @since 1.0.0
	 */
	public function show_api_setting_page() {
		
		$opts = $this->crm_pro_get_options();		
		$api_key = isset($opts['api_key']) ? sanitize_text_field($opts['api_key']) : '';
		$connected = isset($opts['valid']) ? boolval($opts['valid']) : false;

		require CRM_PRO_ABSPATH . '/admin/partials/api-settings.php';
	}

	/**
	 * Show the Chat Settings page
	 * 
	 * @since 1.0.0
	 */
	public function show_chat_setting_page() {
		$opts = $this->crm_pro_get_options();
		$api_key = isset($opts['api_key']) ? sanitize_text_field($opts['api_key']) : '';
		$connected = isset($opts['valid']) ? boolval($opts['valid']) : false;

		if(!$connected){

			add_settings_error('crm_pro_settings', 'api_error', __('Please check Api connection to enable chat widget.', 'crm-pro'), 'alert');
			$disable = array(
				'disabled' => 'disabled'
			);
		}

		$enable = isset($opts['enableChat']) ? boolval($opts['enableChat']) : false;

		require CRM_PRO_ABSPATH . '/admin/partials/chat-settings.php';
	}

	/**
	 * Show the Chat Settings page
	 * 
	 * @since 1.0.0
	 */
	public function show_form_setting_page() {
		$opts = $this->crm_pro_get_options();
		$api_key = isset($opts['api_key']) ? sanitize_text_field($opts['api_key']) : '';
		$connected = isset($opts['valid']) ? boolval($opts['valid']) : false;
		$forms = array();

		if($connected){
			$args = array(
				'timeout' => 60,
				'headers' => array(
					'Authorization' => 'Bearer ' . $api_key,
				),
			);
	
			$response = wp_remote_get(CRM_PRO_BASEURL . 'v1/forms', $args); 
			$http_code = wp_remote_retrieve_response_code($response);
			$body = json_decode(wp_remote_retrieve_body($response), true);
			if ($http_code === 200) {
				if( isset($body['forms']) && is_array($body['forms']) ) {
					$forms = (array) $body['forms'];
				}
			}
			if(!count($forms)){
				add_settings_error('crm_pro_settings', 'api_error', __('No forms found in your account, please create a form in the crm to list here.', 'crm-pro'), 'alert');
			}
		}else {
			add_settings_error('crm_pro_settings', 'api_error', __('Please check Api connection to list Forms.', 'crm-pro'), 'alert');
		}		

		require CRM_PRO_ABSPATH . '/admin/partials/form-settings.php';
	}
	
	/**
	 * Initializes various stuff used in WP Admin
	 * 
	 * - Registers settings
	 * @since 1.0.0
	 */
	public function initialize() {

		// register settings
		register_setting( 'crm_pro_settings', 'crm_pro', array( $this, 'validate_crm_pro_settings' ) );
	}

	/**
	 * Validates the settings
	 * @param array $settings
	 * @return array
	 */
	public function validate_crm_pro_settings( array $settings ) {	
		
		if( isset($settings['api_key']) ) {

			$api_key = isset( $settings['api_key'] ) ? sanitize_text_field( $settings['api_key'] ) : '';

			if( !empty( $api_key ) ) {

				$args = array(
					'timeout' => 60,
					'headers' => array(
						'Authorization' => 'Bearer ' . $api_key,
					),
				);

				$response = wp_remote_get(CRM_PRO_BASEURL . 'v1/locations/me?includeWhiteLabelUrl=true', $args);
				$http_code = wp_remote_retrieve_response_code($response);	
				$obj = json_decode(wp_remote_retrieve_body($response), false);		

				if ($http_code === 200) {
					
					if(isset($obj->id) && !empty($obj->id)){
						$settings['location_id'] = esc_attr($obj->id);
						$settings['valid'] = true;
						$settings['whiteLabelUrl'] = isset($obj->whiteLabelUrl) ? esc_attr($obj->whiteLabelUrl) : '';
						if (isset($obj->settings) && isset($obj->settings->textwidget) && count((array) $obj->settings->textwidget) > 0) {
							$settings['chat_settings'] = json_encode($obj->settings->textwidget, JSON_UNESCAPED_UNICODE);
						}
					}else{

						add_settings_error('crm_pro_settings', 'api_error', __('Something went wrong, please try again later.', 'crm-pro'), 'error');
						$settings['valid'] = false;
						$settings['enableChat'] = false;
					}				
				} else{

					if(isset($obj->msg)){
						$msg = esc_attr($obj->msg);
						add_settings_error('crm_pro_settings', 'api_error', $msg, 'error');
					}

					$settings['valid'] = false;
					$settings['enableChat'] = false;
				}
			}

		}elseif( isset($settings['enableChat']) ) {
			$opts = $this->crm_pro_get_options();

			$opts['enableChat'] = boolval($settings['enableChat']);
			$settings = $opts;
		}

		return $settings;
	}

	/**
	 * Required capabilities access plugin admin pages.
	 * 
	 * @return string
	 * @since 1.0.0
	 */
	public function get_required_capability() {
		$capability = 'manage_options';
		
		/**
		 * Filters the required user capability to access plugin admin pages.
		 *
		 * Defaults to `manage_options`
		 *
		 * @since 1.0.0
		 * @param string $capability
		 * @see https://codex.wordpress.org/Roles_and_Capabilities
		 */
		$capability = (string) apply_filters( 'crm_pro_admin_required_capability', $capability );

		return $capability;
	}

	/**
	 * Sort Values.
	 * 
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public function sort_menu_items_by_position( $a, $b ) {
		$pos_a = isset( $a['position'] ) ? $a['position'] : 80;
		$pos_b = isset( $b['position'] ) ? $b['position'] : 90;
		return $pos_a < $pos_b ? -1 : 1;
	}

	/**
	 * Gets the GHL for WP options from the database
	 *
	 * @since 1.0
	 * @access public
	 * @static array $options
	 * @return array
	 */
	public function crm_pro_get_options() {
		$options  = (array) get_option( CRM_PRO_OPTION_NAME, array() );
		/**
		 * Filters the settings.
		 *
		 * @param array $options
		 */
		return apply_filters( 'crm_pro_settings', $options );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Crm_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Crm_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/crm-pro-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Crm_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Crm_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/crm-pro-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'crm_pro', array( 'ajaxurl' => admin_url('admin-ajax.php') ) );
		wp_enqueue_script( $this->plugin_name );
	}

	/**
	 * Update chat settings.
	 * 
	 * @since 1.0.0
	 */
	public function crn_pro_chat_update() {

		if( !wp_verify_nonce( $_REQUEST['nonce'] , "crm_pro_chat_update_nonce" ) ){
			exit("No kidding please");
		}

		$settings = $this->crm_pro_get_options();
		
		if( isset($settings['api_key']) && !empty($settings['api_key']) ) {

			$api_key = esc_attr($settings['api_key']);

			$args = array(
				'timeout' => 60,
				'headers' => array(
					'Authorization' => 'Bearer ' . $api_key,
				),
			);

			$response = wp_remote_get(CRM_PRO_BASEURL . 'v1/locations/me?includeWhiteLabelUrl=true', $args);
			$http_code = wp_remote_retrieve_response_code($response);	
			$obj = json_decode(wp_remote_retrieve_body($response), false);	

			if ($http_code === 200) {
				
				if(isset($obj->id) && !empty($obj->id)){
					$settings['location_id'] = esc_attr($obj->id);
					$settings['valid'] = true;
					$settings['whiteLabelUrl'] = isset($obj->whiteLabelUrl) ? esc_attr($obj->whiteLabelUrl) : '';
					if (isset($obj->settings) && isset($obj->settings->textwidget) && count((array) $obj->settings->textwidget) > 0) {
						$settings['chat_settings'] = json_encode($obj->settings->textwidget, JSON_UNESCAPED_UNICODE);
						$result['message'] = __('Chat Settings updated succesfully!', 'crm-pro');
					}else{
						$result['message'] = __('Please enable chat settings from your CRM account.', 'crm-pro');
					}
				}else{

					$result['message'] = __('Something went wrong, please try again later.', 'crm-pro');
					$settings['valid'] = false;
					$settings['enableChat'] = false;
				}				
			} else{

				if(isset($obj->msg)){
					$msg = esc_attr($obj->msg);
					$result['message'] = $msg;
				}

				$settings['valid'] = false;
				$settings['enableChat'] = false;
				$result['message'] = __('Something went wrong, please try again later.', 'crm-pro');
			}
		} else{
			$result['message'] = __('Please complete your api connection first.', 'crm-pro');
		}

		update_option( CRM_PRO_OPTION_NAME, $settings );
		echo json_encode($result);
		wp_die();
	}

}
