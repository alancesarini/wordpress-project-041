<?php 

/**
 * Template name: Próximos eventos
 */

get_header(); 

if( have_posts() ) {
    the_post();
}
?>

<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
          <div class="row">
            <div class="conference_div">
              <div class="conference_top">

                <h1><?php _e( 'Eventos', 'project-041' ); ?></h1>
                
                <?php the_content(); ?>

                <p>&nbsp;</p>

                <?php Project041_Event::render_featured_events( 3 ); ?>                

                <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-pro.png" />&nbsp;&nbsp;<?php _e( 'Evento exclusivo para profesionales', 'project-041' ); ?></p>

              </div>

              <ul class="tab-list" role="tablist">
                    <li class="tab active-tab" id="tablist1-tab1" aria-controls="tablist1-panel1" role="tab" tabindex="0"><?php _e( 'Próximos eventos', 'project-041' ); ?></li>
                    <li class="tab" id="tablist1-tab2" aria-controls="tablist1-panel2" role="tab" tabindex="0"><a href="<?php echo home_url( 'eventos-pasados' ); ?>"><?php _e( 'Eventos pasados', 'project-041' ); ?></a></li>
              </ul>

              <div class="events-calendar">

                <?php Project041_Calendar::show(); ?>

              </div>

            </div>
          </div>

              <div class="addbanner">
                <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner artículo footer' ) ) : ?>
                <?php endif; ?>
              </div>             
          
        </div>
        <div class="col-sm-4">

          <?php get_sidebar(); ?>

        </div>
      </div>
    </div>
  </div>


<?php get_footer(); ?>
