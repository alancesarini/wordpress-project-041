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
              <h1><?php _e( 'Error 404 - Página no encontrada', 'project-041' ); ?></h1>
              
              <p>
                <?php _e( 'Lo sentimos, no hemos encontrado lo que buscabas.', 'project-041' ); ?>
              </p>
                            
            </div>
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
