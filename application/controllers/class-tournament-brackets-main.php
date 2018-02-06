<?php // @codingStandardsIgnoreLine

namespace Tournament_Brackets\Controllers;

/**
 * Tournament Brackets plugin's main class.
 *
 * @category Class
 * @package  tournament-brackets
 * @author   Konstantinos Galanakis
 */
class Tournament_Brackets_Main {

	/**
	 * Tournament Brackets plugin's main class initialization.
	 */
	public function initialize() {
		$this->add_hooks();
	}

	/**
	 * Tournament Brackets plugin's main class hooks initialization.
	 */
	public function add_hooks() {
		add_action( 'init', array( $this, 'register_post_type' ) );

		add_action( 'init', array( $this, 'register_assets' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		add_shortcode( 'tournament-bracket', array( $this, 'tournament_brackets_shortcode_callback' ) );

		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_tournament_brackets_meta' ) );
		}
	}

	/**
	 * Register the Tournament Brackes custom post type.
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Tournament Brackets', 'post type general name', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'singular_name'      => _x( 'Tournament Bracket', 'post type singular name', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'menu_name'          => _x( 'Tournament Brackets', 'admin menu', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'name_admin_bar'     => _x( 'Tournament Bracket', 'add new on admin bar', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'add_new'            => _x( 'Add New', 'tournament-brackets', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'add_new_item'       => __( 'Add New Tournament Bracket', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'new_item'           => __( 'New Tournament Bracket', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'edit_item'          => __( 'Edit Tournament Bracket', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'view_item'          => __( 'View Tournament Bracket', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'all_items'          => __( 'All Tournament Brackets', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'search_items'       => __( 'Search Tournament Brackets', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'parent_item_colon'  => __( 'Parent Tournament Brackets:', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'not_found'          => __( 'No tournament brackets found.', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'not_found_in_trash' => __( 'No tournament brackets found in Trash.', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'tournament-bracket',
			),
			'menu_icon'          => 'dashicons-networking',
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'tournament-bracket', $args );
	}

	/**
	 * Register the Tournament Brackets meta box(es) on the custom post edit page..
	 */
	public function register_meta_box() {
		add_meta_box(
			'tournament-brackets-metabox',
			__( 'Bracket', TOURNAMENT_BRACKETS_TEXT_DOMAIN ), // @codingStandardsIgnoreLine
			array( $this, 'tournament_brackets_display' ),
			'tournament-bracket',
			'normal'
		);
	}


	/**
	 * Callback for the saving of the fields contained inside the meta boxes.
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function save_tournament_brackets_meta( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['bracket_data'] ) ) {
			return $post_id;
		}

		$nonce = isset( $_POST['tournament_brackets_nonce'] ) ? $_POST['tournament_brackets_nonce'] : '';

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'tournament_brackets_data_save' ) ) {
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
		update_post_meta( $post_id, 'tournament_brackets_data', $bracket_data );
	}

	/**
	 * Callback for the displaying of the meta boxes content.
	 *
	 * @param $post
	 */
	public function tournament_brackets_display( $post ) {
		// Use get_post_meta to retrieve an existing value from the database.
		$tournament_brackets_data = get_post_meta( $post->ID, 'tournament_brackets_data', true );

		$output = '<div id="tournament-brackets"></div>';
		$output .= '<input type="hidden" id="tournament_brackets_data" name="bracket_data"  value="' . esc_attr( $tournament_brackets_data ) . '" />';
		$output .= wp_nonce_field( 'tournament_brackets_data_save', 'tournament_brackets_nonce', true, false );
		echo $output;
	}

	/**
	 * Tournament Brackets plugin's various secondary classes initialization.
	 */
	public function initialize_classes() {
//		$mgd_admin_menu = new Mailgun_Dashboard_Admin_Menu();
//		$mgd_admin_menu->initialize();
	}

	/**
	 * Tournament Brackets plugin's main class assets registration.
	 */
	public function register_assets() {
		wp_register_script(
			'tournament_brackets_jquery_bracket',
			TOURNAMENT_BRACKETS_URL . '/assets/js/third-party/jquery.bracket.min.js',
			array( 'jquery' ),
			TOURNAMENT_BRACKETS_URL,
			false
		);

		wp_register_style(
			'tournament_brackets_jquery_bracket_style',
			TOURNAMENT_BRACKETS_URL . '/assets/css/third-party/jquery.bracket.min.css',
			array(),
			TOURNAMENT_BRACKETS_URL
		);

		wp_register_script(
			'tournament_brackets_admin_script',
			TOURNAMENT_BRACKETS_URL . '/assets/js/tournament-brackets-admin.js',
			array( 'jquery' ),
			TOURNAMENT_BRACKETS_URL,
			false
		);
	}

	public function admin_enqueue_assets() {
		$current_screen = get_current_screen();
		if ( 'tournament-bracket' === $current_screen->id ) {
			wp_enqueue_script( 'tournament_brackets_jquery_bracket' );

			wp_enqueue_style( 'tournament_brackets_jquery_bracket_style' );

			wp_enqueue_script( 'tournament_brackets_admin_script' );
		}
	}

	public function enqueue_assets() {
		wp_enqueue_script( 'tournament_brackets_jquery_bracket' );

		wp_enqueue_style( 'tournament_brackets_jquery_bracket_style' );
	}

	public function tournament_brackets_shortcode_callback( $atts ) {
		$tournament_brackets_id = $atts['id'];
		$tournament_brackets_data = get_post_meta( $tournament_brackets_id, 'tournament_brackets_data', true );
		require( TOURNAMENT_BRACKETS_VIEWS_PATH . '/tournament-brackets-front.phtml' );
	}
}

