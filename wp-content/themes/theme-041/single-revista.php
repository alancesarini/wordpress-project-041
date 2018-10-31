<?php get_header(); ?>

<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
          <div class="row">
            <div class="revista_area">
              
              <h1><?php _e( 'Revista', 'project-041' ); ?></h1>
              
              <?php 
                while( have_posts() ) {
                    the_post();

                    the_content();
                    $pdf_extract = get_field( '_project041_magazine_pdf1', get_the_ID() );
                    $pdf_full = get_field( '_project041_magazine_pdf2', get_the_ID() );

                    $action = '';
                    if( !is_user_logged_in() ) {
                        $wp_session = WP_Session::get_instance();
                        $wp_session['post_id'] = get_the_ID();
                        $wp_session[ 'login_type' ] = 'magazine';
                        $action = 'login';
                    } else {
                        if( !Project041_Magazine::is_user_subscribed( get_the_ID(), get_current_user_id() ) ) {
                            $action = 'subscribe';
                        }    
                    }

                    switch( $action ) {
                        case 'login':
                            echo '<p><strong>';
                            _e( 'Para poder ver la revista completa, debes suscribirte. Haz click en el botón "Acceder con LinkedIn" para identificarte y suscribirte.' );                        
                            echo '</strong></p>';                          
                            //do_action( 'wordpress_social_login' );   
                            echo do_shortcode( '[wpli_login_link text="Acceder con LinkedIn"]' );   
                            echo '<div class="check-revista"><label>Acepto las condiciones de Project041</label></div>';               
                            echo do_shortcode( '[flipbook width="100%" height="600px" pdf="' . $pdf_extract . '"]' );
                            break;
                        case 'subscribe':
                           echo '<p><strong>';
                            _e( 'Para poder ver la revista completa, debes suscribirte. Haz click en el botón "Suscribirse" para suscribirte.' );                        
                            echo '</strong></p>';                           
                            echo '<a href="#" class="subscribe-to-magazine" data-mid="' . get_the_ID() . '">' . _x( 'Suscribirse', 'project-041' ) . '</a><br><br>';
                            echo do_shortcode( '[flipbook width="100%" height="600px" pdf="' . $pdf_extract . '"]' );
                            break;
                        default:
                            echo '<p><strong>';
                            _e( 'Estás suscrito a esta revista. Puedes verla online, o descargártela haciendo click en el botón "Descargar revista".' );                        
                            echo '</strong></p>';                                                        
                            echo '<a href="' . $pdf_full . '">' . _x( 'Descargar revista', 'project-041' ) . '</a><br><br>';
                            echo do_shortcode( '[flipbook width="100%" height="600px" pdf="' . $pdf_full . '"]' );
                    }
              ?>
                            
            <?php } ?>

            </div>
          </div>

          <div class="addbanner">
                <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner artículo footer' ) ) : ?>
                <?php endif; ?>
          </div>   
          
          <!--
          <div class="col-lg-12 col-magazines">
            <h2>Revistas anteriores</h2>
            <?php //get_template_part( 'partials/list-revistas' ); ?>            
          </div>
          -->  

        </div>
        <div class="col-sm-4">
          
          <?php get_sidebar(); ?>

        </div>
      </div>
    </div>
  </div>


<?php get_footer(); ?>