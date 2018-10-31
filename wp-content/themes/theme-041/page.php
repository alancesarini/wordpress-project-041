<?php get_header(); ?>

<?php 
    if( have_posts() ) {
        the_post();
    }
?>

<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
          <div class="row">
            <div class="actualdetails_area">
              <h1><?php the_title(); ?></h1>
              
              <?php the_content(); ?>
                            
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
</section>

<?php get_footer(); ?>
