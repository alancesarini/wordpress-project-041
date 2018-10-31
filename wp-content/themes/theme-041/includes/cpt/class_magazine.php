<?php

if( !class_exists( 'Project041_Magazine' ) ) {
	
	class Project041_Magazine {

		private static $_version;

		public static $prefix;

		/*-----------------------------------------------------------------------------------*/
		// Class constructor
		/*-----------------------------------------------------------------------------------*/
		public function __construct() {

			self::$_version = '1.0.0';

			self::$prefix = '_project041_magazine_';

			// Create a new user role for the users that subscribe to the magazine
			$event_role = get_role( 'subscriber_mag' );
			if( NUll === $event_role ) {
				add_role( 'subscriber_mag', 'Revista',
					array (
					  'read' => TRUE
					)
				);				
			}

			// Register CPT
			add_action( 'init', array( $this, 'register_cpt' ) );
			
			// Setup metaboxes
			add_action( 'load-post.php', array( $this, 'setup_metaboxes' ) );
			add_action( 'load-post-new.php', array( $this, 'setup_metaboxes' ) );		

			// Ajax action to subscribe a user to a magazine
			add_action( 'wp_ajax_project041-subscribe-to-magazine', array( $this, 'subscribe_user_ajax' ) );			
			add_action( 'wp_ajax_nopriv_project041-subscribe-to-magazine', array( $this, 'subscribe_user_ajax' ) );	
			
			// Add a column in the users table with the magazine subscription date
			add_filter( 'manage_users_columns', array( $this, 'add_column_to_users' ) );

			// Fill the column with the subscription date
			add_filter( 'manage_users_custom_column', array( $this, 'fill_date_column' ), 10, 3 );

			// Make the date column sortable
			add_filter( 'manage_users_sortable_columns', array( $this, 'make_date_column_sortable' ) );

			// Sort by date
			add_filter( 'users_list_table_query_args', array( $this, 'sort_users_by_date' ), 10, 1 );

		}

		/*-----------------------------------------------------------------------------------*/
		// Add a column with the magazine subscription date
		/*-----------------------------------------------------------------------------------*/
		function add_column_to_users( $column ) {

			if( 'subscriber_mag' == $_GET['role'] ) {
				$column['mag_date'] = 'Fecha suscripción a revista';
			}

			return $column;
		
		}

		/*-----------------------------------------------------------------------------------*/
		// Fill the column with the subscription date
		/*-----------------------------------------------------------------------------------*/
		function fill_date_column( $val, $column_name, $user_id ) {

			$date = '';
			switch( $column_name ) {
				case 'mag_date' :
					$date = get_user_meta( $user_id, '_project041_user_mag_date', true );
					if( $date != false ) {
						$tmp = explode( ' ', $date );
						$date = $tmp[0];
						$tmp2 = explode( '-', $tmp[0] );
						$date = $tmp2[2] . '/' . $tmp2[1] . '/' . $tmp2[0];						
					}
					$val = $date;
			}
			return $val;

		}
		
		/*-----------------------------------------------------------------------------------*/
		// Make the date column sortable
		/*-----------------------------------------------------------------------------------*/
		function make_date_column_sortable( $columns ) {

			$columns['mag_date'] = 'Fecha suscripción a revista';		
			return $columns;

		}

		/*-----------------------------------------------------------------------------------*/
		// Sort the users table by date
		/*-----------------------------------------------------------------------------------*/
		function sort_users_by_date( $vars ) {

			if( isset( $vars['orderby'] ) && 'Fecha suscripción a revista' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => '_project041_user_mag_date',
					'orderby' => 'meta_value'
				) );
			}
			return $vars;
	
		}		

		/*-----------------------------------------------------------------------------------*/
		// Register custom post type "Revista"
		/*-----------------------------------------------------------------------------------*/
		function register_cpt() {

			$labels = array(
				'name'               => __( 'Revistas' ),
				'singular_name'      => __( 'Revista' ),
				'add_new'            => __( 'Añade nueva revista' ),
				'add_new_item'       => __( 'Añade nueva revista' ),
				'edit_item'          => __( 'Editar' ),
				'new_item'           => __( 'Nueva' ),
				'all_items'          => __( 'Todas' ),
				'view_item'          => __( 'Ver' ),
				'search_items'       => __( 'Buscar' ),
				'not_found'          => __( 'No se han encontrado revistas' ),
				'not_found_in_trash' => __( 'No se han encontrado revistas en la papelera' ), 
				'parent_item_colon'  => '',
				'menu_name'          => 'Revistas'
			);
			$args = array(
				'labels'        => $labels,
				'description'   => 'Revistas',
				'public'        => true,
				'menu_position' => 21,
				'hierarchical'  => true,
				'supports'      => array( 'title', 'editor', 'thumbnail' ),
				'has_archive'   => false,
				'rewrite'		=> array( 'slug' => 'revista', 'with_front' => false ),
				'exclude_from_search' => true,
				'publicaly_queryable' => false,
				'query_var' => false	                
			);
			register_post_type( 'revista', $args );	

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
				'project041_magazine_metabox_info',
				'Usuarios suscritos a la revista',
				array( $this, 'show_metabox_info' ),
				'revista',
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
				echo '<p>Aún no se ha suscrito ningún usuario.</p>';
			}

		}

		function subscribe_user_ajax() {

			$user_id = get_current_user_id();
			$magazine_id = intval( $_GET['mid'] );
			self::subscribe_user( $magazine_id, $user_id );
			$response = json_encode( array( 'response' => 'OK' ) );
			die( $response );
						
		}

		/*-----------------------------------------------------------------------------------*/
		// Subscribe a user to the magazine
		/*-----------------------------------------------------------------------------------*/
		public static function subscribe_user( $magazine_id, $user_id ) {
			
			$subscribed_users = get_post_meta( $magazine_id, self::$prefix . 'users', true );
			if( !is_array( $subscribed_users ) ) {
				$subscribed_users = array( $user_id );
			} else {
				if( !in_array( $user_id, $subscribed_users ) ) {
					$subscribed_users[] = $user_id;
				}
			}
			update_post_meta( $magazine_id, self::$prefix . 'users', $subscribed_users );
			$_date = new DateTime();
			update_user_meta( $user_id, '_project041_user_mag_date', $_date->format( 'Y-m-d H:i' ) );

			$_user = get_user_by( 'ID', $user_id );
			$_user->add_role('subscriber_mag');

		}		

		/*-----------------------------------------------------------------------------------*/
		// Check if a user is subscribed to a magazine
		/*-----------------------------------------------------------------------------------*/
		public static function is_user_subscribed( $magazine_id, $user_id ) {

			$subscribed_users = get_post_meta( $magazine_id, self::$prefix . 'users', true );
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
	}
}

new Project041_Magazine();
