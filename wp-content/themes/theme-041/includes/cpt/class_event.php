<?php

if( !class_exists( 'Project041_Event' ) ) {
	
	class Project041_Event {

		private static $_version;

		public static $prefix;

		/*-----------------------------------------------------------------------------------*/
		// Class constructor
		/*-----------------------------------------------------------------------------------*/
		public function __construct() {

			self::$_version = '1.0.0';

			self::$prefix = '_project041_event_';

			// Create a new user role for the users that register to an event
			$event_role = get_role( 'subscriber_evt' );
			if( NUll === $event_role ) {
				add_role( 'subscriber_evt', 'Eventos',
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

			// Ajax action to register a user in an event
			add_action( 'wp_ajax_project041-register-user-event', array( $this, 'register_user_event' ) );			
			add_action( 'wp_ajax_nopriv_project041-register-user-event', array( $this, 'register_user_event' ) );	

			// Ajax action to download the CSV file
			add_action( 'wp_ajax_project041-download-event-users', array( $this, 'generate_csv_users' ) );			

			// Shortcode to render the register button
			add_shortcode( 'boton_registro_evento', array( $this, 'render_register_event_button' ) );

			// Add a column in the users table with the event registration date
			add_filter( 'manage_users_columns', array( $this, 'add_column_to_users' ) );

			// Fill the column with the registration date
			add_filter( 'manage_users_custom_column', array( $this, 'fill_date_column' ), 10, 3 );

			// Make the date column sortable
			add_filter( 'manage_users_sortable_columns', array( $this, 'make_date_column_sortable' ) );

			// Sort by date
			add_filter( 'users_list_table_query_args', array( $this, 'sort_users_by_date' ), 10, 1 );
			
		}

		/*-----------------------------------------------------------------------------------*/
		// Register custom post type "Evento"
		/*-----------------------------------------------------------------------------------*/
		function register_cpt() {

			$labels = array(
				'name'               => __( 'Eventos' ),
				'singular_name'      => __( 'Evento' ),
				'add_new'            => __( 'Añade nuevo evento' ),
				'add_new_item'       => __( 'Añade nuevo evento' ),
				'edit_item'          => __( 'Editar' ),
				'new_item'           => __( 'Nuevo' ),
				'all_items'          => __( 'Todos' ),
				'view_item'          => __( 'Ver' ),
				'search_items'       => __( 'Buscar' ),
				'not_found'          => __( 'No se han encontrado eventos' ),
				'not_found_in_trash' => __( 'No se han encontrado eventos en la papelera' ), 
				'parent_item_colon'  => '',
				'menu_name'          => 'Eventos'
			);
			$args = array(
				'labels'        => $labels,
				'description'   => 'Eventos',
				'public'        => true,
				'menu_position' => 21,
				'hierarchical'  => true,
				'supports'      => array( 'title', 'editor', 'thumbnail' ),
				'has_archive'   => true,
				'rewrite'		=> array( 'slug' => 'evento', 'with_front' => false )
			);
			register_post_type( 'evento', $args );	

			$labels = array(
				'name'              => __( 'Tipos de eventos' ),
				'singular_name'     => __( 'Tipo de evento' ),
				'search_items'      => __( 'Busca tipo' ),
				'all_items'         => __( 'Todos los tipos' ),
				'parent_item'       => __( 'Tipo superior' ),
				'parent_item_colon' => __( 'Tipo superior:' ),
				'edit_item'         => __( 'Editar tipo' ), 
				'update_item'       => __( 'Actualizar tipo' ),
				'add_new_item'      => __( 'Añadir tipo' ),
				'new_item_name'     => __( 'Nuevo tipo' ),
				'menu_name'         => __( 'Tipos de eventos' ),
			);
			$args = array(
				'labels' => $labels,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => 'tipo_evento', 'with_front' => false )
			);
			register_taxonomy( 'tipo_evento', 'evento', $args );			

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
				'project041_event_metabox_info',
				'Usuarios registrados en el evento',
				array( $this, 'show_metabox_info' ),
				'evento',
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
				echo "<a href='#' class='button button-primary download-event-users' data-event='" . $object->ID . "'>Descargar usuarios</a>";
			} else {
				echo '<p>Aún no se ha registrado ningún usuario.</p>';
			}

		}

		/*-----------------------------------------------------------------------------------*/
		// Register a user in an event
		/*-----------------------------------------------------------------------------------*/
		function register_user_event() {

			$event_id = intval( $_POST['eid'] );
			$first_name = sanitize_text_field( $_POST['funds_firstname'] );
			$last_name = sanitize_text_field( $_POST['funds_lastname'] );
			$phone = sanitize_text_field( $_POST['funds_phone'] );
			$email = sanitize_text_field( $_POST['funds_email'] );
			$entity = sanitize_text_field( $_POST['funds_entity'] );
			$random_password = wp_generate_password( 12, false );
			$login = 'user_' . MD5( $email );
			$rgpd_text = get_field( self::$prefix . 'rgpd', $event_id );
			$error = false;

			$user_id = email_exists( $email );
			if( false === $user_id ) {
				$data = array(
					'user_login' => $login,
					'user_pass' => $random_password,
					'user_email' => $email,
					'first_name' => $first_name,
					'last_name' => $last_name,
					'role' => 'subscriber_evt'
				);
				$user_id = wp_insert_user( $data );
				if( !is_wp_error( $user_id ) ) {
					update_user_meta( $user_id, '_project041_user_phone', $phone );
					update_user_meta( $user_id, '_project041_user_entity', $entity );
				} else {
					$error = true;
					$message = 'Ha ocurrido un error en el registro. Disculpe las molestias';
				}
			} else {
				$_user = get_user_by( 'ID', $user_id );
				$_user->add_role('subscriber_evt');
			}

			if( !$error ) {
				$subscribed_users = get_post_meta( $event_id, self::$prefix . 'users', true );
				if( !is_array( $subscribed_users ) ) {
					$subscribed_users = array( $user_id );
				} else {
					if( !in_array( $user_id, $subscribed_users ) ) {
						$subscribed_users[] = $user_id;
					}
				}
				update_post_meta( $event_id, self::$prefix . 'users', $subscribed_users );
				update_user_meta( $user_id, '_project041_user_rgpd', strip_tags( $rgpd_text ) );
				$_date = new DateTime();
				update_user_meta( $user_id, '_project041_user_evt_date', $_date->format( 'Y-m-d H:i' ) );				
			}

			if( !$error ) {
				$response = json_encode( array( 'response' => 'OK', 'message' => 'Su registro se ha realizado con éxito' ) );
			} else {
				$response = json_encode( array( 'response' => 'KO', 'message' => $message ) );
			}

			die( $response );

		}

		/*-----------------------------------------------------------------------------------*/
		// Get the next events 
		/*-----------------------------------------------------------------------------------*/
		function get_next_events_NEW( $number ) {

			$the_date = new DateTime();
			$now = $the_date->format( 'Y-m-d H:i' );
			$sql = "SELECT post_id FROM wp_postmeta WHERE meta_key = '_project041_event_date' AND meta_value > '" . $now . "' ORDER BY meta_value ASC LIMIT 0," . $number;
			global $wpdb;
			$event_ids = $wpdb->get_results( $sql );
			$array_events = array();
			foreach( $event_ids as $event_id ) {
				$sql = "SELECT * FROM wp_posts WHERE post_type = 'evento' AND post_status = 'publish' AND ID = " . $event_id->post_id;
				$event = $wpdb->get_row( $sql );
				if( $event != null ) {
					$the_event = self::get_event_data( $event->ID );
					$array_events[] = $the_event;
				}
			}

			return $array_events;

		}

		/*-----------------------------------------------------------------------------------*/
		// Get the next events 
		/*-----------------------------------------------------------------------------------*/
		function get_next_events( $number ) {

			$the_date = new DateTime();
			$now = $the_date->format( 'Y-m-d H:i' );
			$sql = "SELECT distinct post_id FROM wp_postmeta WHERE meta_key = '_project041_event_date' AND meta_value > '" . $now . "' AND post_id IN (SELECT ID FROM wp_posts WHERE post_type = 'evento' AND post_status = 'publish') ORDER BY meta_value ASC LIMIT 0," . $number;
			global $wpdb;
			$events = $wpdb->get_results( $sql );
			$array_events = array();
			foreach( $events as $event ) {
				$the_event = self::get_event_data( $event->post_id );
				$array_events[] = $the_event;
			}

			return $array_events;

		}
		
		/*-----------------------------------------------------------------------------------*/
		// Get events in a date
		/*-----------------------------------------------------------------------------------*/
		function get_events_by_date( $date ) {

			$sql = "SELECT post_id FROM wp_postmeta WHERE meta_key = '_project041_event_date' AND meta_value LIKE '" . $date . "%' ORDER BY meta_value ASC";
			global $wpdb;
			$events_date = $wpdb->get_results( $sql );
			$array_events = array();
			foreach( $events_date as $event ) {
				$sql = "SELECT ID FROM wp_posts WHERE post_type = 'evento' AND post_status = 'publish' AND ID = $event->post_id";
				$events = $wpdb->get_results( $sql );
				if( 1 == count( $events ) ) {
					$the_event = self::get_event_data( $events[0]->ID );
					$array_events[] = $the_event;
				}
			}

			return $array_events;

		}

		/*-----------------------------------------------------------------------------------*/
		// Get an event data 
		/*-----------------------------------------------------------------------------------*/
		function get_event_data( $event_id ) {

			$event = get_post( $event_id );	
			$the_event = new stdClass();
			$the_event->ID = $event_id;
			$the_event->title = get_the_title( $event_id );
			$event_url = get_field( '_project041_event_link', $event_id );
			if( $event_url != '' ) {
				$the_event->url = $event_url;
			} else {
				$the_event->url = get_the_permalink( $event_id );
			}
			$the_event->date = get_field( '_project041_event_date', $event_id );
			$event_date = $the_event->date;
			$datetime_array = explode( ' ', $event_date );
			$date_array = explode( '/', $datetime_array[0] );
			$the_event->calendar_date = $date_array[2] . '-' . $date_array[1] . '-' . $date_array[0] . 'T' . $datetime_array[1] . ':00';
			$the_event->place = get_field( '_project041_event_place', $event_id );
			$the_event->citypic = get_field( '_project041_event_citypic', $event_id );
			$the_event->name = get_field( '_project041_event_name', $event_id );
			$the_event->excerpt = substr( str_replace( '"', '\'', strip_tags( $event->post_content ) ), 0, 250 ) . '...';
			$the_event->is_for_professionals = false;
			$the_event->is_external = false;
			$the_event->banner = get_field( '_project041_event_banner', $event_id );
			$the_event->external_link = get_field( '_project041_event_bannerlink', $event_id );
			$terms = get_the_terms( $event_id, 'tipo_evento' );
			if( count( $terms ) > 0 ) {
				foreach( $terms as $term ) {
					if( 'de-terceros' == $term->slug ) $the_event->is_external = true;
					if( 'para-profesionales' == $term->slug ) $the_event->is_for_professionals = true;
				}
			}
			
			return $the_event;

		}		

		/*-----------------------------------------------------------------------------------*/
		// Render the next events in the homepage
		/*-----------------------------------------------------------------------------------*/
		public static function render_next_events( $number ) {
			
			$array_events = self::get_next_events( $number );

			echo '<ul class="date_li clearfix">';
			foreach( $array_events as $event ) {
				$event_date = new DateTime( $event->calendar_date );
				echo '<li>
					<a href="' . $event->url . '">
	                <div class="date_img"> <img src="' . $event->citypic['sizes']['thumbnail'] . '" alt="' . $event->title . '" title="' . $event->title . '" />
					</div>
					<h2>' . strftime( '%e', strtotime( $event_date->format( 'Y-m-d' ) ) ) . ' ' . strftime( '%b', strtotime( $event_date->format( 'Y-m-d' ) ) ) . '</h2>
					<h3>' . $event->place . '</h3>
					</a>
	              	</li>';
			}
			echo '</ul>';

		}

		/*-----------------------------------------------------------------------------------*/
		// Render the selected events for the homepage
		/*-----------------------------------------------------------------------------------*/
		public static function render_custom_events() {
			
			if( have_rows( '_project041_home_events' ) ) {
				echo '<ul class="date_li clearfix">';				
				while( have_rows( '_project041_home_events' ) ) { 
					the_row();
					$event_object = get_sub_field( 'event' );
					$event = self::get_event_data( $event_object->ID );
					$event_date = new DateTime( $event->calendar_date );
					echo '<li>
						<a href="' . $event->url . '">
						<div class="date_img"> <img src="' . $event->citypic['sizes']['thumbnail'] . '" alt="' . $event->title . '" title="' . $event->title . '" />
						</div>
						<h2>' . strftime( '%e', strtotime( $event_date->format( 'Y-m-d' ) ) ) . ' ' . strftime( '%b', strtotime( $event_date->format( 'Y-m-d' ) ) ) . '</h2>
						<h3>' . $event->place . '</h3>
						</a>
						</li>';					 
				}
				echo '</ul>';				
			} else {
				self::render_next_events( 5 );
			}

		}	
		
		/*-----------------------------------------------------------------------------------*/
		// Render the button to register in an event
		/*-----------------------------------------------------------------------------------*/
		function render_register_event_button( $atts ) {
			$params = shortcode_atts( array(
				'id' => -1,
				'texto' => 'Inscríbete'
			), $atts );			
			
			if( intval( $params['id'] ) > 0 ) {
				$rgpd_text = get_field( self::$prefix . 'rgpd', $params['id'] );
				if( $rgpd_text != '' ) {
					$rgpd_html = '<div class="wrapper-check-legal"><input type="checkbox" id="check-funds-1" name="check-funds-1" required>&nbsp;&nbsp;<label for="check-funds-1">' . $rgpd_text . '</label></div>';
				}
				$html = '
				<a href="#" class="button-red" data-target="#modal-register-event" data-toggle="modal">' . $params['texto'] . '</a>
				<div id="modal-register-event" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Inscripción al evento</h4>
							</div>
							<div class="modal-body">
								<p>Rellena tus datos para poder asistir al evento</p>
								<div class="register-messages"></div>
								<form id="form-register-event">
								<div class="form-row">
									<input name="funds_firstname" required type="text" placeholder="Nombre" />
								</div>
								<div class="form-row">
									<input name="funds_lastname" required type="text" placeholder="Apellidos" />
								</div>
								<div class="form-row">
									<input name="funds_phone" required type="number" placeholder="Teléfono" />
								</div>
								<div class="form-row">
									<input name="funds_email" required type="text" placeholder="Correo" />
								</div>
								<div class="form-row">
									<input name="funds_entity" required type="text" placeholder="Entidad" />
								</div>
								<div class="form-row">
								' . $rgpd_html . '	
								</div>              
								<div class="form-row">
									<input type="hidden" name="eid" value="' . $params['id'] . '" />
									<input class="button-red" type="submit" value="Asistir">
								</div>
								</form>							
							</div>
						</div>
					</div>
				</div>
				';
				return $html;
			}
		}

		/*-----------------------------------------------------------------------------------*/
		// Render the featured events
		/*-----------------------------------------------------------------------------------*/
		function render_featured_events( $number ) {

			$featured_events = get_option( Project041_Configuration::$prefix . 'featured_events' );
			if( count( $featured_events ) > 0 ) {
				echo "<p class='featured-events'>";
				foreach( $featured_events as $event_id ) {
					$_event = self::get_event_data( $event_id );
					$image = get_field( self::$prefix . 'banner', $event_id );
					echo "<a href='" . $_event->external_link . "' target='_BLANK'><img src='" . $_event->banner . "' /></a>";
				}
				echo "</p>";
			}
		}

		/*-----------------------------------------------------------------------------------*/
		// Generate a CSV file with the users subscribed to an event
		/*-----------------------------------------------------------------------------------*/
		function generate_csv_users() {

			$event_id = intval( $_GET['eid'] );
			$process_ok = false;

			if( $event_id > 0 ) {
				$users = get_post_meta( $event_id, self::$prefix . 'users', true );
				if( count( $users ) > 0 ) {
					$csv = '';
					foreach( $users as $user_id ) {
						$_user = get_userdata( $user_id );
						if( $_user != false ) {
							$phone = get_user_meta( $user_id, '_project041_user_phone', true );
							$entity = get_user_meta( $user_id, '_project041_user_entity', true );
							$rgpd = get_user_meta( $user_id, '_project041_user_rgpd', true );
							$rgpd = trim( preg_replace( '/\s+/', ' ', $rgpd ) );
							$csv .= $_user->first_name . ";" . $_user->last_name . ";" . $_user->user_email . ";" . $phone . ";" . $entity . ";" . $rgpd. "\n";
						}
					}
					if( $csv != '' ) {
						$uploads_dir = wp_upload_dir();
						$path = $uploads_dir['basedir'] . '/project041-tmp/usuarios.csv';
						$url = home_url() . '/wp-content/uploads/project041-tmp/usuarios.csv';	
						file_put_contents( $path, $csv );	
						$process_ok = true;	
					}
				}
			}

			if( $process_ok ) {
				$response = json_encode( array( 'response' => 'OK', 'file' => $url, 'csv' => $csv ), JSON_UNESCAPED_UNICODE );
			} else {
				$response = json_encode( array( 'response' => 'KO' ) );
			}
			die( $response );

		}

		/*-----------------------------------------------------------------------------------*/
		// Add a column with the magazine subscription date
		/*-----------------------------------------------------------------------------------*/
		function add_column_to_users( $column ) {

			if( 'subscriber_evt' == $_GET['role'] ) {
				$column['evt_date'] = 'Fecha registro en evento';
			}

			return $column;
		
		}

		/*-----------------------------------------------------------------------------------*/
		// Fill the column with the subscription date
		/*-----------------------------------------------------------------------------------*/
		function fill_date_column( $val, $column_name, $user_id ) {

			$date = '';
			switch( $column_name ) {
				case 'evt_date' :
					$date = get_user_meta( $user_id, '_project041_user_evt_date', true );
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

			$columns['evt_date'] = 'Fecha registro en evento';
			return $columns;

		}	
		
		/*-----------------------------------------------------------------------------------*/
		// Sort the users table by date
		/*-----------------------------------------------------------------------------------*/
		function sort_users_by_date( $vars ) {

			if( isset( $vars['orderby'] ) && 'Fecha registro en evento' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => '_project041_user_evt_date',
					'orderby' => 'meta_value'
				) );
			}
			return $vars;
	
		}		

	}
}

new Project041_Event();