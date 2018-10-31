<?php 

/**
 * Template name: Conference calls
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
                    <li class="tab active-tab" id="tablist1-tab1" aria-controls="tablist1-panel1" role="tab" tabindex="0"><?php _e( 'Próximas conferencias', 'project-041' ); ?></li>
                    <li class="tab" id="tablist1-tab2" aria-controls="tablist1-panel2" role="tab" tabindex="0"><a href="<?php echo home_url( 'conference-calls-anteriores' ); ?>"><?php _e( 'Conferencias ya realizadas', 'project-041' ); ?></a></li>
              </ul>
      
              <ul class="conflist">
              <?php 
                  $now = new DateTime();
                  $args = array(
                      'post_type' => 'conference',
                      'post_status' => 'publish',
                      'posts_per_page' => -1,
                      'meta_key' => '_project041_conference_date',
                      'meta_value' => $now->format( 'Y-m-d' ),
                      'meta_compare' => '>='
                  );

                  $conferences = new WP_Query( $args );
                  
                  while( $conferences->have_posts() ) {
                      $conferences->the_post();
                      $external_url = get_field( '_project041_conference_url', get_the_ID() );
                  ?>
                      <li>
                          <a name="conference-<?php echo get_the_ID(); ?>"></a>
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
                          <?php
                              if( $external_url != null ) {
                                  echo '<a href="' . $external_url . '" class="button-red" target="_BLANK">' . _x( 'Inscribirse', 'project-041' ) . '</a>';
                              } elseif( !is_user_logged_in() ) {
                                  echo '<p class="register-conference-message"><a href="#" class="button-red linkedin-login" data-cid="' . get_the_ID() . '">' . _x( 'Inscribirse', 'project-041' ) . '</a>';
                                  echo '<span style="display:none">' . _x( 'Ya estás inscrito en esta conference call.', 'project-041' ) . '</span></p>';
                              } else {
                                  if( !Project041_Conference::is_user_registered( get_the_ID(), get_current_user_id() ) ) {
                                      echo '<p class="register-conference-message"><a href="#" class="button-red register-in-conference" data-cid="' . get_the_ID() . '">' . _x( 'Inscribirse', 'project-041' ) . '</a>';
                                      echo '<span style="display:none">' . _x( 'Ya estás inscrito en esta conference call.', 'project-041' ) . '</span></p>';                                      
                                  } else {
                                      echo '<p class="register-conference-message"><strong>' . _x( 'Ya estás inscrito en esta conference call.', 'project-041' ) . '</strong></p>';
                                  }
                              }                                
                          ?>
                      </li>
                  <?php
                  }
              ?>
              </ul>

              <?php //do_action( 'wordpress_social_login' ); ?>

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
