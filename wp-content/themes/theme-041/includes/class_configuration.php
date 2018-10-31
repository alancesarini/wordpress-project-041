<?php

if( !class_exists( 'Project041_Configuration' ) ) {

	class Project041_Configuration {

		private static $_this;

		private static $_version;

		public static $prefix;

		private static $array_exclude;

		function __construct() {
		
			self::$_this = $this;

			self::$_version = '1.0.0';

			self::$prefix = '_project041_';

			self::$array_exclude = array();

			/*-----------------------------------------------------------------------------------*/
			// Add support for thumbnails
			/*-----------------------------------------------------------------------------------*/
			add_theme_support( 'post-thumbnails' ); 	

			/*-----------------------------------------------------------------------------------*/
			// Register menus
			/*-----------------------------------------------------------------------------------*/
			add_action( 'init', array( $this, 'register_menus' ) );

			/*-----------------------------------------------------------------------------------*/
			// Add new image sizes
			/*-----------------------------------------------------------------------------------*/
			add_image_size( 'project041-featured', 460, 400, true );
			add_image_size( 'project041-small', 264, 185, true );
			add_image_size( 'project041-list', 264, 150, true );
			add_image_size( 'project041-tiny', 100, 50, true );
			add_image_size( 'project041-gallery', 764, 405, true );
			add_image_size( 'project041-magazine', 150, 200, true );

			/*-----------------------------------------------------------------------------------*/
			// Load text domain
			/*-----------------------------------------------------------------------------------*/
			add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );	

			/*-----------------------------------------------------------------------------------*/
			// Register the widget areas
			/*-----------------------------------------------------------------------------------*/
			register_sidebars( 1,
				array(
					'name' => 'Sidebar portada',
					'before_widget' => '<div class="widget">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2>',
					'after_title'   => '</h2>'
				)				
			);					
			register_sidebars( 1,
				array(
					'name' => 'Sidebar general',
					'before_widget' => '<div class="widget">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2>',
					'after_title'   => '</h2>'
				)				
			);
			register_sidebars( 1,
				array(
					'name' => 'Sidebar artículo',
					'before_widget' => '<div class="widget">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2>',
					'after_title'   => '</h2>'
				)				
			);
			register_sidebars( 1,
				array(
					'name' => 'Sidebar evento',
					'before_widget' => '<div class="widget">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2>',
					'after_title'   => '</h2>'
				)				
			);
			register_sidebars( 1,
				array(
					'name' => 'Banner cabecera',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)				
			);
			register_sidebars( 1,
				array(
					'name' => 'Banner portada 468x60',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)				
			);		
			register_sidebars( 1,
				array(
					'name' => 'Iconos sociales en pie',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)				
			);
			register_sidebars( 1,
				array(
					'name' => 'Banner footer',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)				
			);	
			register_sidebars( 1,
				array(
					'name' => 'Banner 160x600 izquierda',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)				
			);	
			register_sidebars( 1,
				array(
					'name' => 'Banner 160x600 derecha',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)				
			);	

			/*-----------------------------------------------------------------------------------*/
			// Register the widgets
			/*-----------------------------------------------------------------------------------*/
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		
			/*-----------------------------------------------------------------------------------*/
			// Load JS and CSS for the frontend screens
			/*-----------------------------------------------------------------------------------*/
			add_action( 'wp_enqueue_scripts', array( $this, 'load_js_css' ) );

			/*-----------------------------------------------------------------------------------*/
			// Remove admin bar
			/*-----------------------------------------------------------------------------------*/
			//add_action( 'after_setup_theme', array( $this, 'remove_admin_bar' ) );	

			/*-----------------------------------------------------------------------------------*/
			// Setup metaboxes
			/*-----------------------------------------------------------------------------------*/
			add_action( 'load-post.php', array( $this, 'setup_metaboxes' ) );
			add_action( 'load-post-new.php', array( $this, 'setup_metaboxes' ) );	

			/*-----------------------------------------------------------------------------------*/
			// Change default message in popular posts widget
			/*-----------------------------------------------------------------------------------*/
			add_filter( 'wpp_no_data', array( $this, 'filter_popular_output' ) );

			/*-----------------------------------------------------------------------------------*/
			// Redirect new user to where he was
			/*-----------------------------------------------------------------------------------*/
			add_action( 'parse_query', array( $this, 'redirect_new_user' ) );

			/*-----------------------------------------------------------------------------------*/
			// Add JS code to the footer
			/*-----------------------------------------------------------------------------------*/			
			add_action( 'wp_footer', array( $this, 'render_ajax_url' ) );

			/*-----------------------------------------------------------------------------------*/			
			// Filter the excerpt to make it shorter
			/*-----------------------------------------------------------------------------------*/						
			add_filter( 'excerpt_length', array( $this, 'shorten_excerpt' ), 999 );

			/*-----------------------------------------------------------------------------------*/			
			// Disable autosave function
			/*-----------------------------------------------------------------------------------*/						
			add_action( 'wp_print_scripts', array( $this, 'disable_autosave' ) );

			// Always order post listings by date desc
			add_action( 'pre_get_posts', array( $this, 'order_posts_by_date' ) );

			// Shortcode to render the newsletter form
			add_shortcode( 'formulario_newsletter', array( $this, 'render_newsletter_form' ) );

			// Add rewrites for the search results pagination
			//add_filter( 'generate_rewrite_rules', array( $this, 'add_rewrites' ) );		

			// Save user info in MailChimp
			//add_action( 'wp_ajax_project041-save-mailchimp', array( $this, 'save_mailchimp_user' ) );			
			//add_action( 'wp_ajax_nopriv_project041-save-mailchimp', array( $this, 'save_mailchimp_user' ) );
			
			//add_action( 'init', array( $this, 'add_user_meta' ) );			

		}

		/*
		function add_user_meta() {

			if( isset( $_GET['add_user_meta'] ) ) {
				$query = new WP_User_Query( array( 'role' => 'subscriber_mag' ) );
				foreach ( $query->get_results() as $user ) {
					$date = get_user_meta( $user->ID, '_project041_user_mag_date', true );
					if( !$date ) {
						echo $user->display_name . ' - ';
						var_dump( $user->roles);
						echo ' - ' . $date . '<br>';
						update_user_meta( $user->ID, '_project041_user_mag_date', '' );
					}
				}
				die();
			}

		}
		*/
		

		/*-----------------------------------------------------------------------------------*/		
		// Always order post listings by date desc
		/*-----------------------------------------------------------------------------------*/		
		function order_posts_by_date( $query ) {

			if ( !is_admin() && $query->is_main_query() ) {
				if ( is_search() ) {
					$query->set( 'orderby', 'date' );
				}
			}	

		}

		/*-----------------------------------------------------------------------------------*/		
		// Save Mailchimp user data after subscription
		/*-----------------------------------------------------------------------------------*/		
		function save_mailchimp_user() {

			require( dirname( __FILE__ ) . '/vendor/mailchimp/MailChimp.php' );
			$_mailchimp = new MailChimp( Project041_Definitions::$mailchimp['api_key'] );

			$email = $_GET['email'];
			$subscriber_hash = $_mailchimp->subscriberHash( $email );
			$list_id = Project041_Definitions::$mailchimp['list_id'];

			$result = $_mailchimp->patch("lists/$list_id/members/$subscriber_hash", [
				'merge_fields' => ['PROFESION'=>'']
			]);

			$response = json_encode( array( 'response' => 'OK' ) );
			die( $response );

		}

		/*-----------------------------------------------------------------------------------*/		
		// Add the rewrites for the search results pagination
		/*-----------------------------------------------------------------------------------*/		
        function add_rewrites( $wp_rewrite ) {

			$rules = array();
			//$rules["page/([0-9]{1,})/\?s=(.+?)&post_type=post"] = 'index.php?s=$matches[2]&paged=$matches[1]&post_type=post';
			//$rules["page/([0-9]{1,})/(\?)s=(.+?)&post_type=post"] = 'index.php?s=$matches[2]&paged=$matches[1]&post_type=post';
			$rules["page/?([0-9]{1,})/?s=(.+?)&post_type=post"] = 'index.php?s=$matches[2]&paged=$matches[1]&post_type=post';
			$wp_rewrite->rules = $rules + $wp_rewrite->rules;

		}	

		/*-----------------------------------------------------------------------------------*/
		// Disable autosave
		/*-----------------------------------------------------------------------------------*/		
		function disable_autosave() {

			wp_deregister_script( 'autosave' );
		
		}

		/*-----------------------------------------------------------------------------------*/
		// Shorten excerpt 
		/*-----------------------------------------------------------------------------------*/		
		function shorten_excerpt( $length ) {
			return 25;
		}		

		/*-----------------------------------------------------------------------------------*/
		// Register the widgets
		/*-----------------------------------------------------------------------------------*/
		function register_widgets() {
			
			include( 'widgets/widget_sponsors.php' );
			include( 'widgets/widget_sponsors_event.php' );
			include( 'widgets/widget_events.php' );			
			include( 'widgets/widget_subscription.php' );			
			include( 'widgets/widget_subscription_button.php' );			
			include( 'widgets/widget_chart.php' );			
			include( 'widgets/widget_related.php' );			
			include( 'widgets/widget_related_custom.php' );			
			register_widget( 'project041_widget_sponsors' );
			register_widget( 'project041_widget_sponsors_event' );
			register_widget( 'project041_widget_events' );
			register_widget( 'project041_widget_subscription' );
			register_widget( 'project041_widget_subscription_button' );
			register_widget( 'project041_widget_chart' );
			register_widget( 'project041_widget_related' );
			register_widget( 'project041_widget_related_custom' );

		}

		/*-----------------------------------------------------------------------------------*/
		// Register menus
		/*-----------------------------------------------------------------------------------*/
		function register_menus() {
		
			register_nav_menu( 'headermenu', __( 'Menú cabecera' ) );
			register_nav_menu( 'footermenu', __( 'Menú pie' ) );

		}
		
		/*-----------------------------------------------------------------------------------*/
		// Load textdomain
		/*-----------------------------------------------------------------------------------*/		
		function load_textdomain() {
			
			load_theme_textdomain( 'project-041', get_template_directory() . '/lang' );
			
		}		

		/*-----------------------------------------------------------------------------------*/
		// Setup meta boxes
		/*-----------------------------------------------------------------------------------*/
		function setup_metaboxes() {

			add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
			add_action( 'save_post', array( $this, 'save_metaboxes' ), 10, 2 );

		}

		/*-----------------------------------------------------------------------------------*/
		// Add the meta boxes to the new/edit screen
		/*-----------------------------------------------------------------------------------*/
		function add_metaboxes() {
			
			add_meta_box(
				'project041_metabox_info',
				'Artículo destacado',
				array( $this, 'show_metabox_info' ),
				'post',
				'normal',
				'high'
			);

		}

		/*-----------------------------------------------------------------------------------*/
		// Display the meta box for the info fields
		/*-----------------------------------------------------------------------------------*/
		function show_metabox_info( $object ) {
						
			wp_nonce_field( basename( __FILE__ ), 'project041_nonce' ); 

			$option_featured_1 = get_option( self::$prefix . 'featured_home_1' );
			$option_featured_2 = get_option( self::$prefix . 'featured_home_2' );
			$option_featured_3 = get_option( self::$prefix . 'featured_home_3' );
			$featured = 0;
			if( $object->ID == $option_featured_1 ) $featured = 1;
			if( $object->ID == $option_featured_2 ) $featured = 2;
			if( $object->ID == $option_featured_3 ) $featured = 3;

			$meta_featured = self::$prefix . 'featured';
			
		?>			

			<p>
				<input type="radio" name="<?php echo $meta_featured; ?>" value="0" <?php if( '0' == $featured ) { echo 'checked'; } ?> />
				<label for="<?php echo $meta_featured; ?>">No destacado</label><br/>
				<input type="radio" name="<?php echo $meta_featured; ?>" value="1" <?php if( '1' == $featured ) { echo 'checked'; } ?> />
				<label for="<?php echo $meta_featured; ?>">Destacado grande</label><br/>
				<input type="radio" name="<?php echo $meta_featured; ?>" value="2" <?php if( '2' == $featured ) { echo 'checked'; } ?> />
				<label for="<?php echo $meta_featured; ?>">Destacado pequeño superior</label><br/>
				<input type="radio" name="<?php echo $meta_featured; ?>" value="3" <?php if( '3' == $featured ) { echo 'checked'; } ?> />
				<label for="<?php echo $meta_featured; ?>">Destacado pequeño inferior</label>																		
			</p>	

		<?php 

		}

		/*-----------------------------------------------------------------------------------*/
		// Save the data
		/*-----------------------------------------------------------------------------------*/
		function save_metaboxes( $post_id, $post ) {

			if ( !isset( $_POST[ 'project041_nonce' ] ) || !wp_verify_nonce( $_POST[ 'project041_nonce' ], basename( __FILE__ ) ) )
				return $post_id;
			
			$meta_featured = self::$prefix . 'featured';
			$array_featured = array();
			for( $i = 1; $i < 4; $i++ ) {
				$array_featured[] = get_option( self::$prefix . 'featured_home_' . $i );
			}

			$position = intval( $_POST[$meta_featured] );
			if( $position > 0 ) {
				$meta_key = self::$prefix . 'featured_home_' . $position;
				$meta_value = $post_id;
				update_option( $meta_key, $meta_value );
				for( $i = 0; $i < 3; $i++ ) {
					if( ( $i + 1 ) != $position && $array_featured[$i] == $post_id ) {
						update_option( self::$prefix . 'featured_home_' . ( $i + 1 ), 0 );
					}
				}
			} else {
				if( $array_featured[0] == $post_id ) update_option( self::$prefix . 'featured_home_1', 0 );
				if( $array_featured[1] == $post_id ) update_option( self::$prefix . 'featured_home_2', 0 );
				if( $array_featured[2] == $post_id ) update_option( self::$prefix . 'featured_home_3', 0 );
			}

		}

		/*-----------------------------------------------------------------------------------*/
		// Load assets
		/*-----------------------------------------------------------------------------------*/
		function load_js_css() {

			wp_register_script( 'bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ), Project041_Definitions::$scripts_version, true );
			wp_register_script( 'tabmenu', get_stylesheet_directory_uri() . '/js/tabmenu.min.js', array( 'jquery' ), Project041_Definitions::$scripts_version, true );			
			wp_register_script( 'owl', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array( 'jquery' ), Project041_Definitions::$scripts_version, true );			
			wp_register_script( 'tooltipster', get_stylesheet_directory_uri() . '/js/tooltipster.bundle.min.js', array( 'jquery' ), Project041_Definitions::$scripts_version, true );						
			wp_register_script( 'project041-main', get_stylesheet_directory_uri() . '/js/main.min.js', array( 'jquery', 'bootstrap' ), Project041_Definitions::$scripts_version, true );
			
			wp_enqueue_script( 'bootstrap' );
			wp_enqueue_script( 'tabmenu' );
			wp_enqueue_script( 'owl' );
			wp_enqueue_script( 'tooltipster' );
			wp_enqueue_script( 'project041-main' );

		}

		/*-----------------------------------------------------------------------------------*/
		// Remove the admin bar
		/*-----------------------------------------------------------------------------------*/
		function remove_admin_bar() {

			show_admin_bar( false );

		}	

		function filter_popular_output( $output ) {
			
			return _x( 'Disculpe, en estos momentos no podemos mostrar ningún artículo.', 'project-041' );
			
		}
					

		/*-----------------------------------------------------------------------------------*/
		// Render the featured articles
		/*-----------------------------------------------------------------------------------*/
		public static function render_featured_articles() {

			$featured_1 = get_option( self::$prefix . 'featured_home_1' );
			$featured_2 = get_option( self::$prefix . 'featured_home_2' );
			$featured_3 = get_option( self::$prefix . 'featured_home_3' );

			if( !$featured_1 || 0 == intval( $featured_1 ) ) $featured_1 = self::get_latest_article( self::$array_exclude ); 
			self::$array_exclude[] = $featured_1;
			if( !$featured_2 || 0 == intval( $featured_3 ) ) $featured_2 = self::get_latest_article( self::$array_exclude ); 
			self::$array_exclude[] = $featured_2;
			if( !$featured_3 || 0 == intval( $featured_3 ) ) $featured_3 = self::get_latest_article( self::$array_exclude ); 
			self::$array_exclude[] = $featured_3;

			self::render_featured_big( $featured_1 );
			self::render_featured_small( array( $featured_2, $featured_3 ) );

		}

		/*-----------------------------------------------------------------------------------*/
		// Render extra articles in the homepage
		/*-----------------------------------------------------------------------------------*/
		public static function render_extra_articles() {

			$extra_articles = get_option( Project041_Configuration::$prefix . 'enable_checkbox' );

			if( $extra_articles ) {
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => 3,
					'post__not_in' => self::$array_exclude
				);

				$query = new WP_Query( $args );
				if( $query->have_posts() ) {
					$i = 1;
					while( $query->have_posts() ) {
						$query->the_post();
						if( 1 == $i ) {
							$class = 'no-padding-left';
						} elseif( 3 == $i ) {
							$class = 'no-padding-right';
						} else {
							$class = '';
						}
						?>						
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 <?php echo $class; ?>">
						<div class="small_img">
						<a href="<?php the_permalink(); ?>">
						<article>
							<div class="img_hand"> 
								<?php if( has_post_thumbnail() ) { ?>
									<?php the_post_thumbnail( 'project041-small' ); ?>
								<?php } else { ?>
									<span class="image-holder"></span>
								<?php } ?>
								<div class="img_details right_details">
									<h2><?php echo the_title(); ?></h2>
								</div>
							</div>	
						</article>
						</a>
						</div>	
						</div>				
						<?php
						$i++;
					}
				}
				wp_reset_postdata();
			}
			
		}

		/*-----------------------------------------------------------------------------------*/
		// Get latest article published
		/*-----------------------------------------------------------------------------------*/
		function get_latest_article( $array_exclude ) {

			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'post__not_in' => $array_exclude,
				'orderby' => 'date',
				'order' => 'DESC'
			);

			$post_id = 0;

			$query = new WP_Query( $args );
			if( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
			}

			return $post_id;

		}

		/*-----------------------------------------------------------------------------------*/
		// Render the big featured article
		/*-----------------------------------------------------------------------------------*/
		function render_featured_big( $post_id ) {
			$the_post = get_post( $post_id );
			$excerpt = strip_tags( $the_post->post_content );
			$excerpt = substr( $excerpt, 0, 170 ) . ' ...';
		?>

			<a href="<?php echo get_permalink( $post_id ); ?>">
			<article>
			<div class="big_img">
              <div class="img_hand"> 
			  	<?php echo get_the_post_thumbnail( $post_id, 'project041-featured' ); ?>
                <div class="img_details">
                  <h1><?php echo get_the_title( $post_id ); ?></h1>
				  <p><?php echo $excerpt; ?></p>
                </div>
              </div>
            </div>		
			</article>
			</a>
		
		<?php
		}

		/*-----------------------------------------------------------------------------------*/
		// Render the small featured articles
		/*-----------------------------------------------------------------------------------*/
		function render_featured_small( $array_post_id ) {
		?>
			<div class="small_img">

			<?php $i = 1; foreach( $array_post_id as $post_id ) { ?>
			<a href="<?php echo get_permalink( $post_id ); ?>">
			<article>
				<div class="img_hand <?php if( 1 == $i ) { ?>right_side<?php } ?>"> 
					<?php echo get_the_post_thumbnail( $post_id, 'project041-small' ); ?>
					<div class="img_details right_details">
			  			<h2><?php echo get_the_title( $post_id ); ?></h2>
					</div>
		  		</div>	
			</article>
			</a>

			<?php $i++; } ?>

			</div>
		
		<?php
		}

		/*-----------------------------------------------------------------------------------*/
		// Render the N latest articles of a category
		/*-----------------------------------------------------------------------------------*/
		public static function render_latest_category( $cat_slug, $number ) {

			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => $number,
				'category_name' => $cat_slug,
				'orderby' => 'date',
				'order' => 'DESC'				
			);

			$query = new WP_Query( $args );
			if( $query->have_posts() ) {
				echo '<ul class="news_list">';
				while( $query->have_posts() ) {
					$query->the_post();
					echo '<li><h2><a href="' . get_the_permalink( get_the_ID() ) . '">' . get_the_title( get_the_ID() ) . '</a></h2>';
					echo '<p><a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author( get_the_ID() ) . '</a> | ' . get_the_date( 'd-m-Y', get_the_ID() ) . '</p></li>';
				}
				echo '</ul>';
			}

		}

		/*-----------------------------------------------------------------------------------*/
		// Render the N most popular posts
		/*-----------------------------------------------------------------------------------*/
		public static function render_most_popular( $type, $number ) {

			$args = array(
				'post_type' => 'post',
				'limit' => $number,
				'thumbnail_width' => 140,
				'thumbnail_height' => 80,
				'stats_date' => 1,
				'stats_date_format' => 'd-m-Y',
				'stats_author' => 1,
				'range' => $type,
				'wpp_start' => '<ul class="news_list">',
				'wpp_end' => '</ul>',
				'post_html' => '					
					<li>
					<div class="news_img">{thumb}</div>
					<div class="news_dl_right">
					  <h2><a href="{url}">{text_title}</a></h2>
					  <p>{author} | {date}</p>
					</div>
				  	</li>'
			);

			/*
			$args = array(
				'post_type' => 'post',
				'limit' => $number,
				'thumbnail_width' => 140,
				'thumbnail_height' => 80,
				'stats_date' => 1,
				'stats_date_format' => 'd-m-Y',
				'stats_author' => 1,
				'range' => 'custom',
				'time_unit' => 'day',				
				'wpp_start' => '<ul class="news_list">',
				'wpp_end' => '</ul>',
				'post_html' => '					
					<li>
					<div class="news_img">{thumb}</div>
					<div class="news_dl_right">
					  <h2><a href="{url}">{text_title}</a></h2>
					  <p>{author} | {date}</p>
					</div>
				  	</li>'
			);

			if( 'weekly' == $type ) {
				$args[ 'time_quantity' ] = 7;
			} else {
				$args[ 'time_quantity' ] = 30;
			}
			*/

			wpp_get_mostpopular( $args );

		}		
		
		/*-----------------------------------------------------------------------------------*/
		// Redirect user after LinkedIn login or registration
		/*-----------------------------------------------------------------------------------*/
		function redirect_new_user( $wp_query ) {

			if( isset( $_GET['newuser'] ) || isset( $_GET['loginuser'] ) ) {
				$wp_session = WP_Session::get_instance();
				if( isset( $wp_session[ 'login_type' ] ) ) {
					$login_type = $wp_session[ 'login_type' ];
					$post_id = $wp_session[ 'post_id' ];
					unset( $wp_session[ 'login_type' ] );
					unset( $wp_session[ 'post_id' ] );					
					if( intval( $post_id ) > 0 && 'conference' == $login_type ) {
						$user_id = get_current_user_id();
						Project041_Conference::register_user( $post_id, $user_id );
						//header( 'Location: ' . home_url( 'usuario-suscrito-conference' ) );
						header( 'Location: ' . home_url( 'conference-calls' ) . '#conference-' . $post_id );
						die();
					} elseif( intval( $post_id ) > 0 && 'magazine' == $login_type ) {
						$user_id = get_current_user_id();
						Project041_Magazine::subscribe_user( $post_id, $user_id );
						header( 'Location: ' . home_url( 'revista' ) );
						die();
					} else {
						header( 'Location: ' . home_url() );
					}					
				}     
			}

		}

		/*-----------------------------------------------------------------------------------*/
		// Render the AJAX url in the footer
		/*-----------------------------------------------------------------------------------*/
		function render_ajax_url() {

			echo "<script>var ajaxurl = '" . admin_url( 'admin-ajax.php' ) . "';</script>";

		}

		function render_newsletter_form( $atts ) {

			ob_start();
		?>

			<!-- Begin MailChimp Signup Form -->
			<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
			<style type="text/css">
				#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
				/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
				We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
			</style>
			<style type="text/css">
				#mc-embedded-subscribe-form input[type=checkbox]{display: inline; width: auto;margin-right: 10px;}
				#mergeRow-gdpr {margin-top: 20px;}
				#mergeRow-gdpr fieldset label {font-weight: normal;}
				#mc-embedded-subscribe-form .mc_fieldset{border:none;min-height: 0px;padding-bottom:0px;}
				#mc_embed_signup .button {
					height: 40px;
					background: #e42320;
					border: none;
					font-family: 'Catamaran',sans-serif;
					font-weight: 500;
					font-size: 18px;
					color: #fff;
					padding: 0 20px;
					border-radius: 0;			
				}
				#mc_embed_signup #mc-embedded-subscribe-form div.mce_inline_error {color:#fff}
				#mc_embed_signup div#mce-responses {padding:0;margin:0}
			</style>
			<div id="mc_embed_signup">
			<form action="https://project041.us17.list-manage.com/subscribe/post?u=9039f9db50c0fbd27be411ff8&amp;id=c16c07506f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll">
			<div class="indicates-required"><span class="asterisk">*</span> campos obligatorios</div>
			<div class="mc-field-group">
				<label for="mce-EMAIL">Email  <span class="asterisk">*</span>
			</label>
				<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
			</div>
			<div class="mc-field-group">
				<label for="mce-FNAME">Nombre  <span class="asterisk">*</span>
			</label>
				<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
			</div>
			<div class="mc-field-group">
				<label for="mce-LNAME">Apellidos  <span class="asterisk">*</span>
			</label>
				<input type="text" value="" name="LNAME" class="required" id="mce-LNAME">
			</div>
			<div class="mc-field-group">
				<label for="mce-DIRECCION">Dirección postal </label>
				<input type="text" value="" name="DIRECCION" class="" id="mce-DIRECCION">
				<p style="padding-bottom:0">
				Déjanos tu dirección postal si quieres recibir el formato físico de la revista Project041
				</p>
			</div>
			<div id="mergeRow-gdpr" class="mergeRow gdpr-mergeRow content__gdprBlock mc-field-group">
				<div class="content__gdpr">
					<label><input type="checkbox" id="gdpr_6493" name="gdpr[6493]" value="Y" class="av-checkbox required"> Acepto las condiciones de Project041</label>
				</div>
			</div>
				<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_9039f9db50c0fbd27be411ff8_c16c07506f" tabindex="-1" value=""></div>
				<div class="clear"><input type="submit" value="Suscribirse" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</div>
			</form>
			</div>
			<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[4]='PROFESION';ftypes[4]='text';fnames[3]='DIRECCION';ftypes[3]='text'; /*
			* Translated default messages for the $ validation plugin.
			* Locale: ES
			*/
			$.extend($.validator.messages, {
			required: "Este campo es obligatorio.",
			remote: "Por favor, rellena este campo.",
			email: "Por favor, escribe una dirección de correo válida",
			url: "Por favor, escribe una URL válida.",
			date: "Por favor, escribe una fecha válida.",
			dateISO: "Por favor, escribe una fecha (ISO) válida.",
			number: "Por favor, escribe un número entero válido.",
			digits: "Por favor, escribe sólo dígitos.",
			creditcard: "Por favor, escribe un número de tarjeta válido.",
			equalTo: "Por favor, escribe el mismo valor de nuevo.",
			accept: "Por favor, escribe un valor con una extensión aceptada.",
			maxlength: $.validator.format("Por favor, no escribas más de {0} caracteres."),
			minlength: $.validator.format("Por favor, no escribas menos de {0} caracteres."),
			rangelength: $.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
			range: $.validator.format("Por favor, escribe un valor entre {0} y {1}."),
			max: $.validator.format("Por favor, escribe un valor menor o igual a {0}."),
			min: $.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
			});}(jQuery));var $mcj = jQuery.noConflict(true);</script>
			<!--End mc_embed_signup-->

		<?php
			$html = ob_get_contents();
			ob_end_clean(); 

			return $html;
		}			

		static function this() {
		
			return self::$_this;
		
		}

	}

}

new Project041_Configuration();