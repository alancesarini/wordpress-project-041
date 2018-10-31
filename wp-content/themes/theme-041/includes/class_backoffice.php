<?php

if( !class_exists( 'Project041_Backoffice' ) ) {

	class Project041_Backoffice {

		private static $_this;

		private static $_version;

		function __construct() {
		
			self::$_this = $this;

			self::$_version = '1.0.0';	

			/*-----------------------------------------------------------------------------------*/
			// Load JS and CSS for the admin screens
			/*-----------------------------------------------------------------------------------*/
			add_action( 'admin_enqueue_scripts', array( $this, 'load_js_css_admin' ) );				

			/*-----------------------------------------------------------------------------------*/
			// Add JS code to the footer
			/*-----------------------------------------------------------------------------------*/			
			add_action( 'wp_footer', array( $this, 'render_ajax_url' ) );

			/*-----------------------------------------------------------------------------------*/						
            // Add menu item to the backoffice
			/*-----------------------------------------------------------------------------------*/						            
			add_action( 'admin_menu', array( $this, 'add_menu_item' ) );

			/*-----------------------------------------------------------------------------------*/						
            // Save options
			/*-----------------------------------------------------------------------------------*/						            
			add_action( 'admin_post_project041_save_options', array( $this, 'save_options' ) );

			/*-----------------------------------------------------------------------------------*/												
			// Ajax action to return users based on a text search
			/*-----------------------------------------------------------------------------------*/												
			add_action( 'wp_ajax_project041_get_users', array( $this, 'get_users_ajax' ) );

			/*-----------------------------------------------------------------------------------*/												
			// Ajax action to return events based on a text search
			/*-----------------------------------------------------------------------------------*/												
			add_action( 'wp_ajax_project041_get_events', array( $this, 'get_events_ajax' ) );

		}

		/*-----------------------------------------------------------------------------------*/
		// Load assets for the backend
		/*-----------------------------------------------------------------------------------*/
		function load_js_css_admin() {

			wp_register_script( 'project041-select2', get_stylesheet_directory_uri() . '/js/select2.min.js', array( 'jquery' ), Project041_Definitions::$scripts_version, true );						
			wp_register_script( 'project041-select2-lang', get_stylesheet_directory_uri() . '/js/select2-language-es.js', array( 'jquery' ), Project041_Definitions::$scripts_version, true );						
			wp_register_script( 'project041-main-admin', get_stylesheet_directory_uri() . '/js/main-admin.min.js', array( 'jquery', 'project041-select2' ), Project041_Definitions::$scripts_version, true );
			wp_register_style( 'project041-select2-css', get_stylesheet_directory_uri() . '/css/select2.min.css', false, Project041_Definitions::$scripts_version );

			wp_enqueue_script( 'project041-select2' );
			wp_enqueue_script( 'project041-select2-lang' );
			wp_enqueue_script( 'project041-main-admin' );
			wp_enqueue_style( 'project041-select2-css' );

		}

		/*-----------------------------------------------------------------------------------*/
		// Render the AJAX url in the footer
		/*-----------------------------------------------------------------------------------*/
		function render_ajax_url() {

			echo "<script>var ajaxurl = '" . admin_url( 'admin-ajax.php' ) . "';</script>";

		}

        		/*-----------------------------------------------------------------------------------*/
		// Add new page to the backoffice menu
		/*-----------------------------------------------------------------------------------*/		
		function add_menu_item() {

			add_menu_page( 'Project041', 'Project041', 'manage_options', 'project041_config', array( $this, 'render_config_page' ) );

		}

		/*-----------------------------------------------------------------------------------*/		
		// Render the config page
		/*-----------------------------------------------------------------------------------*/		
		function render_config_page() {

			$project041_nonce = wp_create_nonce( 'project041_nonce' ); 
			$meta_enable_checkbox = Project041_Configuration::$prefix . 'enable_checkbox';			
			$enable_checkbox = get_option( $meta_enable_checkbox );

            $meta_featured_authors = Project041_Configuration::$prefix . 'featured_authors';
            $featured_authors = get_option( $meta_featured_authors );

            $meta_featured_events = Project041_Configuration::$prefix . 'featured_events';
			$featured_events = get_option( $meta_featured_events );
						
			$json_featured_authors = array();
			if( is_array( $featured_authors ) ) {
                foreach( $featured_authors as $user_id ) {
                    $user_info = get_userdata( $user_id );
                    $json_featured_authors[] = array( 'id' => $user_id, 'text' => $user_info->first_name . ' ' . $user_info->last_name, 'selected' => 'true' );
                }
			}
			
			$json_featured_events = array();
			if( is_array( $featured_events ) ) {
                foreach( $featured_events as $event_id ) {
                    $event_name = get_the_title( $event_id );
                    $json_featured_events[] = array( 'id' => $event_id, 'text' => $event_name, 'selected' => 'true' );
                }
            }			
		?>

			<?php if( isset( $_GET['updated'] ) && 1 == intval( $_GET['updated'] ) ) { ?>
				<div class="updated settings-error notice is-dismissible">
					<p>Se han guardado los cambios correctamente.</p>
				</div>
			<?php } ?>

			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<div id="poststuff">

                <div id="postbox-container-project041-home" class="postbox-container" style="width:100%;max-width:600px">
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        <div id="project041_metabox_info_1" class="postbox ">
                            <button type="button" class="handlediv button-link" aria-expanded="true"><span class="toggle-indicator" aria-hidden="true"></span></button>
                            <h2 class="hndle ui-sortable-handle"><span>Opciones de portada</span></h2>
                            <div class="inside">	
                                <p>
                                    <input type="checkbox" name="<?php echo $meta_enable_checkbox; ?>" id="<?php echo $meta_enable_checkbox; ?>" value="1" <?php if( 1 == $enable_checkbox ) echo 'checked'; ?>>
                                    <label for="<?php echo $meta_enable_checkbox; ?>">Activar artículos extra en portada</label>
                                </p>																				
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="postbox-container-project041-authors" class="postbox-container" style="width:100%;max-width:600px;clear:both">
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        <div id="project041_metabox_info_2" class="postbox ">
                            <button type="button" class="handlediv button-link" aria-expanded="true"><span class="toggle-indicator" aria-hidden="true"></span></button>
                            <h2 class="hndle ui-sortable-handle"><span>Colaboradores destacados</span></h2>
                            <div class="inside">	
                                <p>
                                    <label for="<?php echo $meta_featured_authors; ?>">Selecciona los colaboradores:</label>
                                    <select id="<?php echo $meta_featured_authors; ?>" name="<?php echo $meta_featured_authors; ?>[]" multiple="multiple">
                                    </select>			
                                </p>																				
                            </div>
                        </div>
                    </div>
                </div>   
                
                <div id="postbox-container-project041-events" class="postbox-container" style="width:100%;max-width:600px;clear:both">
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        <div id="project041_metabox_info_3" class="postbox ">
                            <button type="button" class="handlediv button-link" aria-expanded="true"><span class="toggle-indicator" aria-hidden="true"></span></button>
                            <h2 class="hndle ui-sortable-handle"><span>Eventos destacados</span></h2>
                            <div class="inside">	
                                <p>
                                    <label for="<?php echo $meta_featured_events; ?>">Selecciona los eventos:</label>
                                    <select id="<?php echo $meta_featured_events; ?>" name="<?php echo $meta_featured_events; ?>[]" multiple="multiple">
                                    </select>			
                                </p>																				
                            </div>
                        </div>
                    </div>
                </div>   

            </div>

            <p style="clear:both">
                <input type="hidden" name="project041_nonce" value="<?php echo $project041_nonce; ?>" />	
                <input type="hidden" name="action" value="project041_save_options" />
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar" />			
            </p>	
            </form>	
            

			<script>
                var project041_featured_authors = <?php echo json_encode( $json_featured_authors ); ?>;
                var project041_featured_events = <?php echo json_encode( $json_featured_events ); ?>;
            </script>
		
		<?php
		}

		/*-----------------------------------------------------------------------------------*/		
		// Save configuration
		/*-----------------------------------------------------------------------------------*/		
		function save_options() {

            if( isset( $_POST['project041_nonce'] ) && wp_verify_nonce( $_POST['project041_nonce'], 'project041_nonce') ) {
				$meta_enable_checkbox = Project041_Configuration::$prefix . 'enable_checkbox';			
				$enable_checkbox = $_POST[$meta_enable_checkbox];
				if( NULL == $enable_checkbox ) {
					$enable_checkbox = 0;
				}
                update_option( $meta_enable_checkbox, $enable_checkbox );		
                
                $meta_featured_authors = Project041_Configuration::$prefix . 'featured_authors';
                $featured_authors = $_POST[$meta_featured_authors];
				if( is_array( $featured_authors ) && count( $featured_authors ) > 0 ) {
					update_option( $meta_featured_authors, $_POST[$meta_featured_authors] );
				}  
                
                $meta_featured_events = Project041_Configuration::$prefix . 'featured_events';
                $featured_events = $_POST[$meta_featured_events];
				if( is_array( $featured_events ) && count( $featured_events ) > 0 ) {
					update_option( $meta_featured_events, $_POST[$meta_featured_events] );
				}  				              
			}
			header( 'Location: ' . admin_url( 'admin.php?page=project041_config&updated=1' ) );
			die();	

        }
        
		/*-----------------------------------------------------------------------------------*/				
		// Returns users for the autocomplete fields
		/*-----------------------------------------------------------------------------------*/								
		function get_users_ajax() {

			$search = sanitize_text_field( $_GET['q'] ); 
            $array_users = array();

            $args = array( 
                'search' => '*' . $search . '*', 
                'role__in' => array( 'Contributor', 'Author' )
            );
            $user_query = new WP_User_Query( $args );
            if( !empty( $user_query->get_results() ) ) {
                foreach( $user_query->get_results() as $user ) {
                    $array_users[] = array( 'id' => $user->ID, 'text' => $user->first_name . ' ' . $user->last_name );
                }
                die( json_encode( array( 'results' => $array_users ) ) );
            } else {
				die( json_encode( array( 'results' => array() ) ) );
            }
		
        }
        
		/*-----------------------------------------------------------------------------------*/				
		// Returns events for the autocomplete fields
		/*-----------------------------------------------------------------------------------*/								
		function get_events_ajax() {

			$search = sanitize_text_field( $_GET['q'] ); 
            $array_events = array();

			$args = array(
				'post_type' => 'evento',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				's' => $search,
				'orderby' => 'title',
				'order' => 'ASC'
 			);

			$query = new WP_Query( $args );
			if( $query->have_posts() ) {
				while( $query->have_posts() ) {
					$query->the_post();
					$array_events[] = array( 'id' => get_the_ID(), 'text' => get_the_title() );
				}
				wp_reset_postdata();
				die( json_encode( array( 'results' => $array_events ) ) );			
            } else {
				die( json_encode( array( 'results' => array() ) ) );
            }
		
		}
		        
		static function this() {
		
			return self::$_this;
		
		}

	}

}

new Project041_Backoffice();