<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ghldevs.com/team/rakhi-sharma
 * @since      1.0.0
 *
 * @package    Crm_Pro
 * @subpackage Crm_Pro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Crm_Pro
 * @subpackage Crm_Pro/public
 * @author     rakhi Sharma <rakhi@ghldevs.com>
 */
class Crm_Pro_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Initialize shortcode for forms.
	 * 
	 * @since 1.0.0
	 */
	public function shortcode_init() {
		add_shortcode( 'crmpro_form', array( $this, 'crmpro_form_handler_function' ) );
	}

	/**
	 * Render form using shortcode.
	 * 
	 * @since 1.0.0
	 */
	public function crmpro_form_handler_function( $atts ) {
		$html = '';
		$form_id = isset( $atts['id'] ) ? esc_attr( $atts['id'] ) : '';
		if(!empty($form_id)){
			$form_url = CRM_PRO_FORM_BASEURL . 'widget/form/'.$form_id;
			$html .= '<iframe src="'. $form_url.'" style="border:none;width:100%;" scrolling="no" id="'. $form_id .'"></iframe>';
			wp_enqueue_script( $this->plugin_name.'_form' );
		}

		return $html;
	}

	/**
	 * Add chatbot script in footer if enabled.
	 * 
	 * @since 1.0.0
	 */
	public function add_chatbot_script() {
		$options  = $this->crm_pro_get_options();

		$enable = isset( $options['enableChat'] ) ? intval( $options['enableChat'] ) : false;

		if($enable){

			$location_id = isset( $options['location_id'] ) ? esc_attr($options['location_id']) : '';
			$chat_settings = isset($options['chat_settings']) ? json_decode($options['chat_settings'], true) : array();	
	
			$primary_color = isset( $chat_settings['widgetPrimaryColor'] ) ? esc_attr($chat_settings['widgetPrimaryColor']) : '';
			$avatar = isset( $chat_settings['promptAvatar'] ) ? esc_url($chat_settings['promptAvatar']) : '';
			$agency_name = isset( $chat_settings['agencyName'] ) ? esc_attr($chat_settings['agencyName']) : '';
			$agency_website = isset( $chat_settings['agencyWebsite'] ) ? esc_attr($chat_settings['agencyWebsite']) : '';
			?>
			<chat-widget style="--chat-widget-primary-color: <?php esc_attr_e( $primary_color ); ?>; --chat-widget-active-color:<?php esc_attr_e( $primary_color ); ?>; --chat-widget-bubble-color: <?php esc_attr_e( $primary_color ); ?>"; location-id="<?php esc_attr_e( $location_id ); ?>" prompt-avatar="<?php echo esc_url( $avatar ); ?>" agency-name="<?php esc_attr_e( $agency_name ); ?>" agency-website="<?php esc_attr_e( $agency_website ); ?>" ></chat-widget> 
			<!-- <script src="<?php //echo CRM_PRO_CDN_BASE_URL ?>loader.js" data-resources-url="<?php //echo CRM_PRO_CDN_BASE_URL ?>chat-widget/loader.js" > </script> -->
		<?php
		wp_enqueue_script( $this->plugin_name.'_chat' );
		}
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
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/crm-pro-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/crm-pro-public.js', array( 'jquery' ), $this->version, false );

		// crm pro form script
		wp_register_script( $this->plugin_name.'_form', CRM_PRO_FORM_BASEURL . 'js/form_embed.js', '', $this->version, true );

		// register script for live chat.
		wp_register_script( $this->plugin_name.'_chat', CRM_PRO_CDN_BASE_URL . 'loader.js', '', $this->version, false );
	}

	/**
	 * Add data attributes for the chat script.
	 *
	 * @since    1.0.0
	 */
	public function add_data_attribute( $tag, $handle ) {
		if( $this->plugin_name.'_chat' !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' data-cb-site="'.CRM_PRO_CDN_BASE_URL.'chat-widget/loader.js" src', $tag );
	}

}
