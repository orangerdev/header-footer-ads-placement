<?php

namespace HFAds;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Hfads
 * @subpackage Hfads/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hfads
 * @subpackage Hfads/admin
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Admin {

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
	 * Register custom post types
	 * Hooked via action init, priority 10
	 * @return 	void
	 */
	public function register_post_type() {

	    $labels = array(
	        'name'                  => _x( 'Floating Ads', 'Post type general name', 'hfads' ),
	        'singular_name'         => _x( 'Floating Ad', 'Post type singular name', 'hfads' ),
	        'menu_name'             => _x( 'Floating Ads', 'Admin Menu text', 'hfads' ),
	        'name_admin_bar'        => _x( 'Floating Ad', 'Add New on Toolbar', 'hfads' ),
	        'add_new'               => __( 'Add New', 'hfads' ),
	        'add_new_item'          => __( 'Add New Floating Ad', 'hfads' ),
	        'new_item'              => __( 'New Floating Ad', 'hfads' ),
	        'edit_item'             => __( 'Edit Floating Ad', 'hfads' ),
	        'view_item'             => __( 'View Floating Ad', 'hfads' ),
	        'all_items'             => __( 'All Floating Ads', 'hfads' ),
	        'search_items'          => __( 'Search Floating Ads', 'hfads' ),
	        'parent_item_colon'     => __( 'Parent Floating Ads:', 'hfads' ),
	        'not_found'             => __( 'No books found.', 'hfads' ),
	        'not_found_in_trash'    => __( 'No books found in Trash.', 'hfads' ),
	        'featured_image'        => _x( 'Floating Ad Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'hfads' ),
	        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'hfads' ),
	        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'hfads' ),
	        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'hfads' ),
	        'archives'              => _x( 'Floating Ad archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'hfads' ),
	        'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'hfads' ),
	        'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'hfads' ),
	        'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'hfads' ),
	        'items_list_navigation' => _x( 'Floating Ads list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'hfads' ),
	        'items_list'            => _x( 'Floating Ads list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'hfads' ),
	    );

	    $args = array(
	        'labels'             => $labels,
	        'public'             => true,
	        'publicly_queryable' => false,
	        'show_ui'            => true,
	        'show_in_menu'       => true,
			'show_in_nav_menus'	 => false,
			'show_in_rest'		 => false,
	        'query_var'          => true,
			'exclude_from_search'=> true,
	        'rewrite'            => array( 'slug' => 'floating-ad' ),
	        'capability_type'    => 'post',
	        'has_archive'        => false,
	        'hierarchical'       => false,
	        'menu_position'      => null,
	        'supports'           => array( 'title' ),
	    );

	    register_post_type( 'floating-ad', $args );
	}

	/**
	 * Load carbonfields library
	 * Hooked via action after_setup_theme, priority 10
	 * @return 	void
	 */
	public function crb_load() {

		\Carbon_Fields\Carbon_Fields::boot();

	}

	/**
	 * Get floating ad dropdown options
	 * @return 	array
	 */
	public function get_floating_ad_dropdown() {

		$options = array(
			''	=> 'No Floating Ad'
		);

		$query = new \WP_Query(array(
			'post_type'      => 'floating-ad',
			'posts_per_page' => -1
		));

		foreach($query->posts as $post) :
			$options[$post->ID] = $post->post_title;
		endforeach;

		return $options;
	}

	/**
	 * Set options for floating ad editor
	 * Hooked via action carbon_fields_register_fields, priority 10
	 * @return 	void
	 */
	public function set_floating_ad_options() {

		Container::make('post_meta', __('Setting', 'hfads'))
			->where('post_type', '=', 'floating-ad')
			->add_fields([
				Field::make('separator',	'hfads_sep_desktop',	__('Desktop', 'hfads')),
				Field::make('rich_text',	'desktop_shortcode',	__('Shortcode', 'hfads'))
					->set_required(true),
				Field::make('text',			'desktop_size',			__('Height in PX', 'hfads'))
					->set_required(true)
					->set_attribute('type', 'number')
					->set_default_value(100),
				Field::make('text',			'desktop_margin',		__('Margin in PX', 'hfads'))
					->set_attribute('type', 'number')
					->set_default_value(0),

				Field::make('separator',	'hfads_sep_mobile',		__('Mobile', 'hfads')),
				Field::make('rich_text',	'mobile_shortcode',		__('Shortcode', 'hfads'))
					->set_required(true),
				Field::make('text',			'mobile_size',			__('Height in PX', 'hfads'))
					->set_required(true)
					->set_attribute('type', 'number')
					->set_default_value(100),
				Field::make('text',			'mobile_margin',		__('Margin in PX', 'hfads'))
					->set_attribute('type', 'number')
					->set_default_value(0),
			]);
	}

	/**
	 * Set options for page editor
	 * Hooked via action carbon_fields_register_fields, priority 20
	 */
	public function set_page_options() {

		Container::make('post_meta', __('Floating Ads', 'hfads'))
			->where('post_type', '=', 'page')
			->add_fields([
				Field::make('select',	'hfads_header',	__('Header', 'hfads'))
					->set_help_text(__('Display floating ad on header', 'hfads'))
					->add_options(array($this, 'get_floating_ad_dropdown')),

				Field::make('select',	'hfads_footer',	__('Footer', 'hfads'))
					->set_help_text(__('Display floating ad on footer', 'hfads'))
					->add_options(array($this, 'get_floating_ad_dropdown')),
			]);

	}


}
