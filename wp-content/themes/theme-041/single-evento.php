<?php get_header(); ?>

<?php 

if( have_posts() ) {
	the_post();
	$gallery = get_field( '_project041_event_gallery' );
}

?>

<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
        <div class="row">
        	<div class="evantos_area">
            	<h1><?php the_title(); ?></h1>
               	<h4><span><?php _e( 'Fecha', 'project-041' ); ?>:</span> <?php echo get_field( '_project041_event_date' ); ?> | <span><?php _e( 'Lugar', 'project-041' ); ?>:</span> <?php echo get_field( '_project041_event_place' ); ?> | <span><?php _e( 'Duración', 'project-041' ); ?>:</span> <?php echo get_field( '_project041_event_duration' ); ?></h4>
				
								<?php the_content(); ?>

								<?php if( have_rows('_project041_speakers') ): ?>

									<div class="ponentes_area">
														<h2><?php _e( 'Ponentes', 'project-041' ); ?></h2>
												
														<ul class="ponentes_list clearfix">

											<?php while( have_rows('_project041_speakers') ): the_row(); $image = get_sub_field('pic'); ?> 
											<li>
												<div class="ponentes_img">
													<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php the_sub_field('name'); ?>" title="<?php the_sub_field('name'); ?>" width="140" height="140" />
												</div>
												<div class="ponentes_details">
													<h3><?php the_sub_field('name'); ?></h3>
													<?php the_sub_field('bio'); ?>
													<a href="<?php the_sub_field('pdf'); ?>" target="_BLANK"><?php _e( 'Descargar presentación', 'project-041' ); ?></a>
												</div>
											</li>
											<?php endwhile; ?>
														
														</ul>
									</div>
									
								<?php endif; ?>
                
                <div class="fotos_area">

				<?php if( $gallery != false ) { ?>
                
					<h2><?php _e( 'Galería de fotos', 'project-041' ); ?></h2>
					
					<div class="fotos_slider">
						
						<div id="fotos-demo" class="owl-carousel">

							<?php foreach( $gallery as $pic ) { ?>
								<div class="item"><img src="<?php echo $pic['sizes']['project041-gallery']; ?>" alt="gallery" alt="gallery" /></div>
							<?php } ?>
				
						</div>                
					
					</div>

				<?php } ?>
                
            </div>
                
            </div> 
            </div>  

              <div class="addbanner">
                <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner artículo footer' ) ) : ?>
                <?php endif; ?>
              </div>       
			  			     
		</div>
		
        <div class="col-sm-4">
          
          <?php get_sidebar( 'evento' ); ?>
          
        </div>
      </div>
    </div>
  </div>


<?php get_footer(); ?>