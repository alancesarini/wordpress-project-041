<?php get_header(); ?>

<section class="bodycontent">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
        <div class="row">
        	<div class="actual_area">
                
                <h1>
                    <?php
                        if( is_tag() ) {
                            _e( 'Artículos con la etiqueta', 'project-041' );
                            echo ' "';
                            single_cat_title();
                            echo '"';
                        } elseif( is_search() ) {
                            _e( 'Artículos relacionados con');
                            echo ' "' . get_search_query() . '"';
                        } else {
                            single_cat_title();
                        }
                    ?>
                </h1>
                
                <?php if( have_posts() ) { ?>
                
                <ul class="actual_list clearfix">

                    <?php while( have_posts() ) { 
                            the_post();
                            get_template_part( 'partials/content', 'post' );
                    } ?>
                    
                </ul>

                <?php if( function_exists( 'wp_pagenavi' ) ) { wp_pagenavi(); } ?>

                <?php } else { ?>
                    
                    <p><?php _e( 'Lo sentimos, no hemos encontrado ningún artículo.', 'project-041' ); ?></p>
                
                <?php } ?>
                
                
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