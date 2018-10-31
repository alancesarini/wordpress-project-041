<?php 

/**
 * Template name: Portada
 */

get_header(); 

$stats_cat_id = get_cat_ID( 'Estadísticas' );
$stats_url = get_category_link( $stats_cat_id );
$interviews_cat_id = get_cat_ID( 'Entrevistas' );
$interviews_url = get_category_link( $interviews_cat_id );

?>

  <div class="container">
    <div class="row">
      <div class="col-md-12">      
        <div class="col-md-8">
          <div class="row">

            <?php Project041_Configuration::render_featured_articles(); ?>

          </div>

          <div class="row row-extra-articles">

            <?php Project041_Configuration::render_extra_articles(); ?>

          </div>

          <div class="row section_01">
            <h1><?php _e( 'Próximos eventos', 'project-041' ); ?></h1>
            
            <?php Project041_Event::render_custom_events(); ?>

            <ul class="two_btn">
              <li><a class="evanto" href="<?php echo home_url( 'proximos-eventos' ); ?>"><?php _e( 'Próximos Eventos', 'project-041' ); ?></a></li>
              <li><a class="pasados" href="<?php echo home_url( 'eventos-pasados' ); ?>"><?php _e( 'Eventos Pasados', 'project-041' ); ?></a></li>
            </ul>
          </div>

          <?php 
          $mobile_detect = new Mobile_Detect();
          if( !$mobile_detect->isMobile() ) {
          ?>
            <div class="addbanner">
              <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner portada 468x60' ) ) : ?>
              <?php endif; ?>
            </div>
          <?php } ?>
          
          <div class="row news_list_01">
            <div class="left_news">
              <h1><?php _e( 'Últimas entrevistas', 'project-041' ); ?></h1>
              
              <?php Project041_Configuration::render_latest_category( 'entrevistas', 3 ); ?>

              <a href="<?php echo $interviews_url; ?>" class="more-link"><?php _e( 'Más entrevistas', 'project-041' ); ?></a>
            </div>
            <div class="right_news">
                <h1><?php _e( 'Últimas estadísticas', 'project-041' ); ?> </h1>
                
                <?php Project041_Configuration::render_latest_category( 'estadísticas', 3 ); ?>

                <a href="<?php echo $stats_url; ?>" class="more-link"><?php _e( 'Más estadísticas', 'project-041' ); ?></a>
            </div>
          </div>

          <div class="row news_list_02">
            <div class="left_news">
              <h1><?php _e( 'Lo más leído esta semana', 'project-041' ); ?></h1>

              <?php Project041_Configuration::render_most_popular( 'weekly', 5 ); ?>

            </div>
            <div class="right_news">
              <h1><?php _e( 'Lo más leído este mes', 'project-041' ); ?></h1>
              
              <?php Project041_Configuration::render_most_popular( 'monthly', 5 ); ?>

            </div>
          </div>

              <div class="addbanner">
                <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner artículo footer' ) ) : ?>
                <?php endif; ?>
              </div>   
                        
        </div>
        <div class="col-sm-4">

            <?php get_sidebar( 'portada' ); ?>
        
        </div>
      </div>
    </div>
  </div>


<?php get_footer(); ?>