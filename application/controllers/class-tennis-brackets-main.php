<?php // @codingStandardsIgnoreLine

namespace Tennis_Brackets\Controllers;

/**
 * "Tennis Brackets" plugin's main class.
 *
 * @category Class
 * @package  tennis-brackets
 * @author   Konstantinos Galanakis
 */
class Tennis_Brackets_Main {

	/**
	 * "Tennis Brackets" plugin's main class initialization.
	 */
	public function initialize() {
		$this->add_hooks();
	}

	/**
	 * Tennis Brackets plugin's main class hooks initialization.
	 */
	public function add_hooks() {
		add_action( 'init', array( $this, 'register_post_type' ) );

		add_action( 'init', array( $this, 'register_assets' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		add_shortcode( 'tennis-bracket', array( $this, 'tennis_bracket_shortcode_callback' ) );

		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_tennis_bracket_meta' ) );
		}
	}

	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Tennis Brackets', 'post type general name', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'singular_name'      => _x( 'Tennis Bracket', 'post type singular name', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'menu_name'          => _x( 'Tennis Brackets', 'admin menu', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'name_admin_bar'     => _x( 'Tennis Bracket', 'add new on admin bar', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'add_new'            => _x( 'Add New', 'tennis-bracket', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'add_new_item'       => __( 'Add New Tennis Bracket', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'new_item'           => __( 'New Tennis Bracket', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'edit_item'          => __( 'Edit Tennis Bracket', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'view_item'          => __( 'View Tennis Bracket', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'all_items'          => __( 'All Tennis Brackets', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'search_items'       => __( 'Search Tennis Brackets', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'parent_item_colon'  => __( 'Parent Tennis Brackets:', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'not_found'          => __( 'No tennis brackees found.', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'not_found_in_trash' => __( 'No tennis brackets found in Trash.', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'tennis-bracket',
			),
			'menu_icon'          => 'dashicons-networking',
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'tennis-bracket', $args );
	}

	/**
	 * Register meta box(es).
	 */
	public function register_meta_box() {
		add_meta_box(
			'tennis-brackets-metabox',
			__( 'Bracket', TENNIS_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			array( $this, 'tennis_bracket_display' ),
			'tennis-bracket',
			'normal'
		);
	}

	public function save_tennis_bracket_meta( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['bracket_data'] ) ) {
			return $post_id;
		}

		$nonce = isset( $_POST['tennis_brackets_nonce'] ) ? $_POST['tennis_brackets_nonce'] : '';

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'tennis_brackets_data_save' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, it's safe for us to save the data now. */

		// Sanitize the user input.
		$bracket_data = sanitize_text_field( $_POST['bracket_data'] );

		// Update the meta field.
		update_post_meta( $post_id, 'tennis_bracket_data', $bracket_data );
	}

	public function tennis_bracket_display( $post ) {
		// Use get_post_meta to retrieve an existing value from the database.
		$tennis_bracket_data = get_post_meta( $post->ID, 'tennis_bracket_data', true );

		$output = '<div id="tennis-bracket"></div>';
		$output .= '<input type="hidden" id="tennis_brackets_data" name="bracket_data"  value="' . esc_attr( $tennis_bracket_data ) . '" />';
		$output .= wp_nonce_field( 'tennis_brackets_data_save', 'tennis_brackets_nonce', true, false );
		echo $output;
	}

	/**
	 * "Tennis Brackets" plugin's various secondary classes initialization.
	 */
	public function initialize_classes() {
//		$mgd_admin_menu = new Mailgun_Dashboard_Admin_Menu();
//		$mgd_admin_menu->initialize();
	}

	/**
	 * "Tennis Brackets" plugin's main class assets registration.
	 */
	public function register_assets() {
		wp_register_script(
			'tennis_bracket_jquery_bracket',
			TENNIS_BRACKETS_URL . '/node_modules/jquery-bracket/dist/jquery.bracket.min.js',
			array( 'jquery' ),
			TENNIS_BRACKETS_URL,
			false
		);

		wp_register_style(
			'tennis_bracket_jquery_bracket_style',
			TENNIS_BRACKETS_URL . '/node_modules/jquery-bracket/dist/jquery.bracket.min.css',
			array(),
			TENNIS_BRACKETS_URL
		);

		wp_register_script(
			'tennis_bracket_admin_script',
			TENNIS_BRACKETS_URL . '/assets/js/tennis-bracket-admin.js',
			array( 'jquery' ),
			TENNIS_BRACKETS_URL,
			false
		);
	}

	public function admin_enqueue_assets() {
		$current_screen = get_current_screen();
		if ( 'tennis-bracket' === $current_screen->id ) {
			wp_enqueue_script( 'tennis_bracket_jquery_bracket' );

			wp_enqueue_style( 'tennis_bracket_jquery_bracket_style' );

			wp_enqueue_script( 'tennis_bracket_admin_script' );
		}
	}

	public function enqueue_assets() {
		wp_enqueue_script( 'tennis_bracket_jquery_bracket' );

		wp_enqueue_style( 'tennis_bracket_jquery_bracket_style' );
	}

	public function tennis_bracket_shortcode_callback( $atts ) {
		$tennis_bracket_id = $atts['id'];
		$tennis_bracket_data = get_post_meta( $tennis_bracket_id, 'tennis_bracket_data', true );
		require( TENNIS_BRACKETS_VIEWS_PATH . '/tennis-bracket-front.phtml' );
	}
}

