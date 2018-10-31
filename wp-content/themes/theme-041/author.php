<?php get_header(); ?>

<section class="bodycontent">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
        <div class="row">
        	<div class="actual_area">

            <?php if( have_posts() ) { ?>
                <h1><?php _e( 'Artículos de', 'project-041' ); ?> <?php echo get_the_author_meta( 'display_name' ); ?></h1>
                <div class="articulas_autor clearfix">
                	<div class="articulas_autor_img"><?php echo get_avatar( get_the_author_meta( 'ID' ), 140 ); ?></div>
                    <div class="articulas_autor_details">
                    	<h3><?php _e( 'Sobre el autor', 'project-041' ); ?></h3>
                        <p><?php echo get_the_author_meta( 'description' ); ?></p>
                    </div>
                </div>

                <h1><?php single_cat_title(); ?></h1>
                
                
                
                <ul class="actual_list clearfix">

                    <?php while( have_posts() ) { 
                            the_post();
                            get_template_part( 'partials/content', 'post' );
                    } ?>
                    
                </ul>

                <?php if( function_exists( 'wp_pagenavi' ) ) { wp_pagenavi(); } ?>

                <?php } else { ?>
                    
                    <p><?php _e( 'Lo sentimos, en estos momentos no disponemos de ningún artículo en esta categoría.', 'project-041' ); ?></p>
                
                <?php } ?>
                
                
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