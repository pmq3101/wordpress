<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UM_ReCAPTCHA
 */
class UM_ReCAPTCHA {

	/**
	 * For backward compatibility with 1.3.x and PHP8.2 compatibility.
	 *
	 * @var bool
	 */
	public $plugin_inactive = false;

	/**
	 * @var
	 */
	private static $instance;

	/**
	 * @return UM_ReCAPTCHA
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * UM_ReCAPTCHA constructor.
	 */
	public function __construct() {
		// Global for backwards compatibility.
		$GLOBALS['um_recaptcha'] = $this;
		add_filter( 'um_call_object_ReCAPTCHA', array( &$this, 'get_this' ) );
		add_filter( 'um_settings_default_values', array( &$this, 'default_settings' ), 10, 1 );

		if ( UM()->is_request( 'admin' ) ) {
			$this->admin();
		}

		add_action( 'plugins_loaded', array( &$this, 'init' ), 0 );
	}

	/**
	 * @return $this
	 */
	public function get_this() {
		return $this;
	}

	/**
	 * @param $defaults
	 *
	 * @return array
	 */
	public function default_settings( $defaults ) {
		$defaults = array_merge( $defaults, $this->setup()->settings_defaults );
		return $defaults;
	}

	/**
	 * @return um_ext\um_recaptcha\core\Setup()
	 */
	public function setup() {
		if ( empty( UM()->classes['um_recaptcha_setup'] ) ) {
			UM()->classes['um_recaptcha_setup'] = new um_ext\um_recaptcha\core\Setup();
		}
		return UM()->classes['um_recaptcha_setup'];
	}

	/**
	 * @return um_ext\um_recaptcha\core\Enqueue()
	 */
	public function enqueue() {
		if ( empty( UM()->classes['um_recaptcha_enqueue'] ) ) {
			UM()->classes['um_recaptcha_enqueue'] = new um_ext\um_recaptcha\core\Enqueue();
		}
		return UM()->classes['um_recaptcha_enqueue'];
	}

	/**
	 * @return um_ext\um_recaptcha\admin\Init()
	 */
	public function admin() {
		if ( empty( UM()->classes['um_recaptcha_admin_init'] ) ) {
			UM()->classes['um_recaptcha_admin_init'] = new um_ext\um_recaptcha\admin\Init();
		}
		return UM()->classes['um_recaptcha_admin_init'];
	}

	/**
	 * Init
	 */
	public function init() {
		/** @noinspection PhpIncludeInspection */
		require_once UM_RECAPTCHA_PATH . 'includes/core/actions/um-recaptcha-form.php';
	}

	/**
	 * Captcha allowed
	 *
	 * @param $args
	 *
	 * @return bool
	 */
	public function captcha_allowed( $args ) {
		$enable = false;

		$recaptcha    = UM()->options()->get( 'g_recaptcha_status' );
		$your_sitekey = UM()->options()->get( 'g_recaptcha_sitekey' ) || UM()->options()->get( 'g_reCAPTCHA_site_key' );
		$your_secret  = UM()->options()->get( 'g_recaptcha_secretkey' ) || UM()->options()->get( 'g_reCAPTCHA_secret_key' );

		if ( $recaptcha ) {
			$enable = true;
		}

		if ( isset( $args['g_recaptcha_status'] ) ) {
			$enable = (bool) $args['g_recaptcha_status'];
		}

		if ( ! $your_sitekey || ! $your_secret ) {
			$enable = false;
		}

		if ( isset( $args['mode'] ) && 'password' === $args['mode'] && ! UM()->options()->get( 'g_recaptcha_password_reset' ) ) {
			$enable = false;
		}

		return ( false === $enable ) ? false : true;
	}

	/**
	 * @return array
	 */
	public function error_codes_list() {
		$error_codes = array(
			'missing-input-secret'   => __( '<strong>Error</strong>: The secret parameter is missing.', 'um-recaptcha' ),
			'invalid-input-secret'   => __( '<strong>Error</strong>: The secret parameter is invalid or malformed.', 'um-recaptcha' ),
			'missing-input-response' => __( '<strong>Error</strong>: The response parameter is missing.', 'um-recaptcha' ),
			'invalid-input-response' => __( '<strong>Error</strong>: The response parameter is invalid or malformed.', 'um-recaptcha' ),
			'bad-request'            => __( '<strong>Error</strong>: The request is invalid or malformed.', 'um-recaptcha' ),
			'timeout-or-duplicate'   => __( '<strong>Error</strong>: The response is no longer valid: either is too old or has been used previously.', 'um-recaptcha' ),
			'undefined'              => __( '<strong>Error</strong>: Undefined reCAPTCHA error.', 'um-recaptcha' ),
		);

		return $error_codes;
	}
}

//create class var
add_action( 'plugins_loaded', 'um_init_recaptcha', -10, 1 );
function um_init_recaptcha() {
	if ( function_exists( 'UM' ) ) {
		UM()->set_class( 'ReCAPTCHA', true );
	}
}
