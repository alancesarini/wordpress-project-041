<?php

if( !class_exists( 'Project041_Conference' ) ) {
	
	class Project041_Conference {

		private static $_version;

		public static $prefix;

		/*-----------------------------------------------------------------------------------*/
		// Class constructor
		/*-----------------------------------------------------------------------------------*/
		public function __construct() {

			self::$_version = '1.0.0';

			self::$prefix = '_project041_conference_';

			// Register CPT
			add_action( 'init', array( $this, 'register_cpt' ) );			

			// Setup metaboxes
			add_action( 'load-post.php', array( $this, 'setup_metaboxes' ) );
			add_action( 'load-post-new.php', array( $this, 'setup_metaboxes' ) );

			// Ajax action to save the ID of the conference before invoking the LinkedIn login
			add_action( 'wp_ajax_project041-save-id', array( $this, 'save_conference_id' ) );			
			add_action( 'wp_ajax_nopriv_project041-save-id', array( $this, 'save_conference_id' ) );	
			
			// Ajax action to register a user to a conference call
			add_action( 'wp_ajax_project041-register-in-conference', array( $this, 'register_user_ajax' ) );			
			add_action( 'wp_ajax_nopriv_project041-register-in-conference', array( $this, 'register_user_ajax' ) );	
			
			// Add a column in the conferences list to show the subscribers count
			add_filter( 'manage_conference_posts_columns' , array( $this, 'add_conference_columns' ) );

			// Fill the column
			add_action( 'manage_conference_posts_custom_column' , array( $this, 'fill_custom_column' ), 10, 2 );

		}

		/*-----------------------------------------------------------------------------------*/
		// Add a column in the conferences list
		/*-----------------------------------------------------------------------------------*/
		function add_conference_columns( $columns ) {

			return array_merge( $columns, array( 'subscribers' => 'Nº suscritos' ) );

		} 

		/*-----------------------------------------------------------------------------------*/
		// Fill the custom column
		/*-----------------------------------------------------------------------------------*/
		function fill_custom_column( $column, $post_id ) {

			switch( $column ) {
				case 'subscribers':
					$subscribers = get_post_meta( $post_id, '_project041_conference_users', true );
					if( $subscribers ) {
						echo count( $subscribers );
					} else {
						echo '0';
					}
			}
		}

		/*-----------------------------------------------------------------------------------*/
		// Register custom post type "Conference call"
		/*-----------------------------------------------------------------------------------*/
		function register_cpt() {

			$labels = array(
				'name'               => __( 'Conference calls' ),
				'singular_name'      => __( 'Conference call' ),
				'add_new'            => __( 'Añade nuevo conference' ),
				'add_new_item'       => __( 'Añade nuevo conference' ),
				'edit_item'          => __( 'Editar' ),
				'new_item'           => __( 'Nuevo' ),
				'all_items'          => __( 'Todos' ),
				'view_item'          => __( 'Ver' ),
				'search_items'       => __( 'Buscar' ),
				'not_found'          => __( 'No se han encontrado conferences' ),
				'not_found_in_trash' => __( 'No se han encontrado conferences en la papelera' ), 
				'parent_item_colon'  => '',
				'menu_name'          => 'Conference calls'
			);
			$args = array(
				'labels'        => $labels,
				'description'   => 'Conference calls',
				'public'        => true,
				'menu_position' => 21,
				'hierarchical'  => true,
				'supports'      => array( 'title', 'editor', 'thumbnail' ),
				'has_archive'   => false,
				'rewrite'		=> array( 'slug' => 'conference', 'with_front' => false ),
				'exclude_from_search' => true,
				'publicaly_queryable' => false,
				'query_var' => false	                
			);
			register_post_type( 'conference', $args );	

		}

		/*-----------------------------------------------------------------------------------*/
		// Setup meta boxes
		/*-----------------------------------------------------------------------------------*/
		function setup_metaboxes() {
			
			add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );

		}

		/*-----------------------------------------------------------------------------------*/
		// Add the meta boxes to the new/edit screen
		/*-----------------------------------------------------------------------------------*/
		function add_metaboxes() {
			
			add_meta_box(
				'project041_conference_metabox_info',
				'Usuarios registrados en la conference call',
				array( $this, 'show_metabox_info' ),
				'conference',
				'normal',
				'low'
			);

		}

		/*-----------------------------------------------------------------------------------*/
		// Display the meta box for the info fields
		/*-----------------------------------------------------------------------------------*/
		function show_metabox_info( $object ) {
						
			$subscribed_users = get_post_meta( $object->ID, self::$prefix . 'users', true );
							
			if( is_array( $subscribed_users ) && count( $subscribed_users ) > 0 ) {
				echo '<ul>';
				foreach( $subscribed_users as $user_id ) {
					$user_data = get_userdata( $user_id );
					if( $user_data ) {
						echo '<li>' . $user_data->first_name . ' ' . $user_data->last_name . '</li>';
					}
				}
				echo '</ul>';
			} else {
				echo '<p>Aún no se ha registrado ningún usuario.</p>';
			}

		}
		
		/*-----------------------------------------------------------------------------------*/
		// Register a user in the conference
		/*-----------------------------------------------------------------------------------*/
		public static function register_user( $conference_id, $user_id ) {
			
			$subscribed_users = get_post_meta( $conference_id, self::$prefix . 'users', true );
			if( !is_array( $subscribed_users ) ) {
				$subscribed_users = array( $user_id );
				self::send_email( $conference_id, $user_id );
			} else {
				if( !in_array( $user_id, $subscribed_users ) ) {
					$subscribed_users[] = $user_id;
					self::send_email( $conference_id, $user_id );
				}
			}
			update_post_meta( $conference_id, self::$prefix . 'users', $subscribed_users );

		}

		/*-----------------------------------------------------------------------------------*/		
		// Send an email to a user when he registers in a conference call
		/*-----------------------------------------------------------------------------------*/
		public static function send_email( $conference_id, $user_id ) {

			$the_user = get_user_by( 'ID', $user_id );
			$the_conference = get_post( $conference_id );
			if( is_a( $the_user, 'WP_User' ) && is_a( $the_conference, 'WP_Post' ) ) {
				$user_name = $the_user->display_name;
				$conference_name = $the_conference->post_title;
				$conference_date = get_field( self::$prefix . 'date', $conference_id );
				$conference_date = str_replace( ' ', ' a las ', $conference_date ) . 'h';
				$conference_speaker = '';
				while( have_rows( self::$prefix . 'speakers', $conference_id ) ) {
					the_row();
					$conference_speaker = get_sub_field( 'name', $conference_id );
				}

				$message = "Estimado %NAME%,\n\n";
				$message .= "Gracias por registrarte en la sesión %CONFERENCE%, a cargo de %SPEAKER%, que tendrá lugar el %DATE% en Project041.\n\n";
				$message .= "Te recordamos que poco antes de que empiece la sesión, recibirás de nuevo un email recordatorio con las indicaciones de acceso.\n\n";
				$message .= "Si tienes alguna duda contacta con nosotros. Estaremos encantados de resolver cualquier problema o cuestión que te surja.\n\n";
				$message .= "Un cordial saludo del Equipo de Project041.";
				$message = str_replace( '%NAME%', $user_name, $message );
				$message = str_replace( '%CONFERENCE%', $conference_name, $message );
				$message = str_replace( '%SPEAKER%', $conference_speaker, $message );
				$message = str_replace( '%DATE%', $conference_date, $message );
				$subject = 'Gracias por registrarte en la sesión %CONFERENCE%';
				$subject = str_replace( '%CONFERENCE%', $conference_name, $subject );
				$headers = 'From: Project041 <hello@project041.com>';
				mail( $the_user->user_email, $subject, $message, $headers);
			}

		}		

		/*-----------------------------------------------------------------------------------*/
		// Check if a user is registered in a conference call
		/*-----------------------------------------------------------------------------------*/
		public static function is_user_registered( $conference_id, $user_id ) {
	
			$subscribed_users = get_post_meta( $conference_id, self::$prefix . 'users', true );
			if( !is_array( $subscribed_users ) ) {
				return false;
			} else {
				if( in_array( $user_id, $subscribed_users ) ) {
					return true;
				} else {
					return false;
				}
			}
			
		}

		/*-----------------------------------------------------------------------------------*/
		// Ajax function to save the conference ID
		/*-----------------------------------------------------------------------------------*/		
		function save_conference_id() {

			$conference_id = intval( $_GET[ 'cid' ] );
			if( $conference_id > 0 ) {
				$wp_session = WP_Session::get_instance();
				$wp_session['post_id'] = $conference_id;
				$wp_session[ 'login_type' ] = 'conference';
			}
			$response = json_encode( array( 'response' => 'OK' ) );
			die( $response );

		}

		/*-----------------------------------------------------------------------------------*/
		// Ajax function to register a user in a conference
		/*-----------------------------------------------------------------------------------*/				
		function register_user_ajax() {
			
			$user_id = get_current_user_id();
			$conference_id = intval( $_GET['cid'] );
			self::register_user( $conference_id, $user_id );
			$response = json_encode( array( 'response' => 'OK' ) );
			die( $response );
						
		}
	}
}

new Project041_Conference();