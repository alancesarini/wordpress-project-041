<?php 

/**
 * Template name: Conference calls pasadas
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

                <h1><?php _e( 'Conference calls', 'project-041' ); ?></h1>
                
                <?php the_content(); ?>
              
              </div>

              <ul class="tab-list" role="tablist">
                    <li class="tab" id="tablist1-tab1" aria-controls="tablist1-panel1" role="tab" tabindex="0"><a href="<?php echo home_url( 'conference-calls' ); ?>"><?php _e( 'Próximas conferencias', 'project-041' ); ?></a></li>
                    <li class="tab active-tab" id="tablist1-tab2" aria-controls="tablist1-panel2" role="tab" tabindex="0"><?php _e( 'Conferencias ya realizadas', 'project-041' ); ?></li>
              </ul>
      
              <ul class="conflist">
              <?php 
                  $paged = ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );                        
                  $now = new DateTime();
                  $args = array(
                      'post_type' => 'conference',
                      'post_status' => 'publish',
                      'paged' => $paged,
                      'meta_query' => array(
                        array(
                            'key'     => '_project041_conference_date',
                            'value'   => $now->format( 'Y-m-d' ),
                            'compare' => '<',
                        )
                      ),                      
                      'meta_key' => '_project041_conference_date',
                      'orderby' => 'meta_value',
                      'order' => 'DESC'
                  );

                  $conferences = new WP_Query( $args );
                  
                  while( $conferences->have_posts() ) {
                      $conferences->the_post();
                      $offline = get_field( '_project041_conference_offline' );
                  ?>
                      <li>
                          <?php the_post_thumbnail( 'project041-list' ); ?>
                          <h2><?php the_title(); ?></h2>
                          <span>
                              <?php 
                                  echo get_field( '_project041_conference_date', get_the_ID() );
                                  while( have_rows('_project041_conference_speakers') ) { 
                                      the_row();
                                      echo ' | ';
                                      the_sub_field( 'name' );
                                  }
                              ?> 
                          </span>
                          <p><?php the_content(); ?></p>
                          <?php if( $offline ) { ?>
                            <a href="<?php echo $offline; ?>" class="button-red" target="_BLANK">Ver resumen</a>
                          <?php } ?>
                      </li>
                  <?php
                  }
              ?>
              </ul>

              <?php if( function_exists( 'wp_pagenavi' ) ) { wp_pagenavi( array( 'query' => $conferences ) ); } ?>

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
