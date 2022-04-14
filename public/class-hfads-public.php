<?php

namespace HFAds;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Hfads
 * @subpackage Hfads/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hfads
 * @subpackage Hfads/public
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Front {

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
	 * Floatig ads
	 *
	 * @since 	1.0.0
	 * @var 	array
	 */
	protected $floating_ads = array();

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
	 * Get ad data
	 * @since 	1.0.0
	 * @param  	integer 	$ad_id
	 * @param 	string 		$type
	 * @return 	array|null
	 * - shortcode 	( string )
	 * - height 	( integer )
	 */
	protected function get_ad( $ad_id, $type = 'header' ) {

		$ad = array(
			'mobile'	=> carbon_get_post_meta( $ad_id, 'mobile_shortcode'),
			'desktop'	=> carbon_get_post_meta( $ad_id, 'desktop_shortcode')
		);

		return $ad;
	}

	/**
	 * Check if current page has floating ads
	 * Hooked via action template_redirect, priority 10
	 * @since 	1.0.0
	 * @return 	void
	 */
	public function check_floating_ads() {

		global $post;

		if(is_page()) :

			$cookie = array(
							'header'	=> false,
							'footer'	=> false
						);

			$key = 'hfads_display';

			$header_ad = absint(carbon_get_post_meta($post->ID, 'hfads_header'));
			$footer_ad = absint(carbon_get_post_meta($post->ID, 'hfads_footer'));

			if( 0 < $header_ad ) :

				$ad_setting = $this->get_ad( $header_ad );

				if( is_array( $ad_setting )) :
					$this->floating_ads['header'] = $ad_setting;
				endif;

			endif;

			if( 0 < $footer_ad ) :

				$ad_setting = $this->get_ad( $footer_ad, 'footer' );

				if( is_array( $ad_setting )) :
					$this->floating_ads['footer'] = $ad_setting;
				endif;

			endif;

		endif;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $post;

		if(
			array_key_exists('header', $this->floating_ads) ||
			array_key_exists('footer', $this->floating_ads)
		) :

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hfads-public.css', array(), $this->version, 'all' );

		endif;

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if(
			array_key_exists('header', $this->floating_ads) ||
			array_key_exists('footer', $this->floating_ads)
		) :

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hfads-public.js', array( 'jquery' ), $this->version, true );

			wp_localize_script( $this->plugin_name, 'hfads', array(
				'url'	=> add_query_arg(array(
							'action'	=> 'hfads-set-cookie'
						   ), admin_url('admin-ajax.php'))
			));

		endif;

	}

	/**
	 * Modify body classes
	 * @since 	1.0.0
	 * @param 	array 	$body_classes
	 * @return 	array
	 */
	public function set_body_classes( array $body_classes ) {

		if(array_key_exists('header', $this->floating_ads)) :
			$body_classes[]	= 'hfads-header-enable';
		endif;

		if(array_key_exists('footer', $this->floating_ads)) :
			$body_classes[]	= 'hfads-footer-enable';
		endif;

		return $body_classes;
	}

	/**
	 * Display floating ads
	 * Hooked via wp_footer, priority 10
	 * @since 	1.0.0
	 * @return 	void
	 */
	public function display_floating_ads() {

		global $post;

		__debug($this->floating_ads);

		if(array_key_exists('header', $this->floating_ads)) :
			?>
			<div class='hfads-header hfads-header-<?= $post->ID ; ?> hfads-holder' >
				<div class='desktop-view'>
					<?= do_shortcode( $this->floating_ads['header']['desktop']); ?>
				</div>
				<div class='mobile-view'>
					<?= do_shortcode( $this->floating_ads['header']['mobile']); ?>
				</div>
				<a href='#' class='hfads-closing' data-target='header'>
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAAcklEQVQ4jc2SQQ6AIAwER1/A44SDPlNeJyZ6qQkhbT30oHuju2yXUvgTElCB7GiyaJJGVuACGlAUvgh3Abvl/ghGkzJwZsoFOER4ApvUWldbrctWNyuViz6J23l2TKaXs4nQE7SBaYNVEf7G8CKFV/kb3OR9P1Xog/cZAAAAAElFTkSuQmCC"/>
				</a>
			</div>
			<?php
		endif;

		if(array_key_exists('footer', $this->floating_ads)) :
			?>
			<div class='hfads-footer hfads-footer-<?= $post->ID ; ?> hfads-holder'>
				<div class='desktop-view'>
					<?= do_shortcode( $this->floating_ads['footer']['desktop']); ?>
				</div>
				<div class='mobile-view'>
					<?= do_shortcode( $this->floating_ads['footer']['mobile']); ?>
				</div>
				<a href='#' class='hfads-closing' data-target='footer'>
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAAcklEQVQ4jc2SQQ6AIAwER1/A44SDPlNeJyZ6qQkhbT30oHuju2yXUvgTElCB7GiyaJJGVuACGlAUvgh3Abvl/ghGkzJwZsoFOER4ApvUWldbrctWNyuViz6J23l2TKaXs4nQE7SBaYNVEf7G8CKFV/kb3OR9P1Xog/cZAAAAAElFTkSuQmCC"/>
				</a>
			</div>
			<?php
		endif;
	}

}
