<?php 

/**
 * Template name: Eventos pasados
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
              
              </div>

              <ul class="tab-list" role="tablist">
                    <li class="tab" id="tablist1-tab1" aria-controls="tablist1-panel1" role="tab" tabindex="0"><a href="<?php echo home_url( 'proximos-eventos' ); ?>"><?php _e( 'Próximos eventos', 'project-041' ); ?></a></li>
                    <li class="tab active-tab" id="tablist1-tab2" aria-controls="tablist1-panel2" role="tab" tabindex="0"><?php _e( 'Eventos pasados', 'project-041' ); ?></li>
              </ul>
      
              <ul class="conflist">
              <?php 
                  $paged = ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );                        
                  $now = new DateTime();
                  $args = array(
                      'post_type' => 'evento',
                      'post_status' => 'publish',
                      'paged' => $paged,
                      'meta_key' => '_project041_event_date',
                      'meta_value' => $now->format( 'Y-m-d' ),
                      'meta_compare' => '<',
                      'order' => 'desc',
                      'orderby' => 'meta_value'
                  );

                  $events = new WP_Query( $args );
                  
                  while( $events->have_posts() ) {
                      $events->the_post();
					  $event_data = Project041_Event::get_event_data( get_the_ID() );
                  ?>
                      <li>
                          <?php the_post_thumbnail( 'project041-list' ); ?>
                          <h2><?php the_title(); ?></h2>
                          <span>
                              <?php 
                                  echo get_field( '_project041_event_date', get_the_ID() );
                                  echo ' | ' . get_field( '_project041_event_place', get_the_ID() );
                                  
                              ?> 
                          </span>
                          <p><?php the_excerpt(); ?></p>
                          <p><a href="<?php echo $event_data->url; ?>" class="button-red"><?php _e( 'Ver evento', 'project-041' ); ?></a></p>
                      </li>
                  <?php
                  }
              ?>
              </ul>

              <?php if( function_exists( 'wp_pagenavi' ) ) { wp_pagenavi( array( 'query' => $events ) ); } ?>

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
