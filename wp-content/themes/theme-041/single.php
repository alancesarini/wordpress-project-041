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
              <ul class="namedate clearfix">
                <li><p><i class="fa fa-calendar" aria-hidden="true"></i> <?php the_date( 'd-m-Y' ); ?></p></li>
                <li><p><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo do_shortcode('[rt_reading_time label=""]'); ?> <?php _e( 'minutos', 'project-041' ); ?></p></li>
              </ul>

              <div class="post-authors">

              <?php 
              if( function_exists( 'get_coauthors' ) ) { 
                $authors = get_coauthors();
                if( 1 == count( $authors ) ) {
                  $author = $authors[0];
                  $author_meta = get_user_meta( $author->ID );
                ?>
                    <div class="articulas_autor clearfix">
                          <div class="author-img">
                            <div class="articulas_autor_img"><?php echo get_avatar( $author->ID, 140 ); ?></div>
                          </div>
                          <div class="articulas_autor_details">
                              <div class="author-left">
                                <h3><?php _e( 'Sobre el autor', 'project-041' ); ?></h3>
                                <h6><?php echo $author_meta['first_name'][0] . ' ' . $author_meta['last_name'][0]; ?></h6>
                                <p><?php echo $author_meta['description'][0]; ?></h6></p>
                              </div>
                              <div class="author-right">
                                <a href="<?php echo get_author_posts_url( $author->ID ); ?>"><?php _e( 'Más artículos', '' ); ?></a>
                              </div>
                          </div>
                    </div>
                <?php 
                } else {
                  foreach( $authors as $author ) {
                    $author_meta = get_user_meta( $author->ID );
              ?>
                    <div class="col-lg-6 col-md-12 post-author-col">
                    <div class="articulas_autor clearfix">
                          <div class="author-img">
                            <div class="articulas_autor_img"><?php echo get_avatar( $author->ID, 140 ); ?></div>
                          </div>
                          <div class="articulas_autor_details">
                              <div class="author-left">
                                <h3><?php _e( 'Sobre el autor', 'project-041' ); ?></h3>
                                <h6><?php echo $author_meta['first_name'][0] . ' ' . $author_meta['last_name'][0]; ?></h6>
                                <?php if( $author_meta['description'][0] != '' ) { ?>
                                  <p><?php echo $author_meta['description'][0]; ?></h6></p>
                                <?php } ?>
                              </div>
                              <div class="author-right">
                                <a href="<?php echo get_author_posts_url( $author->ID ); ?>"><?php _e( 'Más artículos', '' ); ?></a>
                              </div>
                          </div>
                    </div>
                    </div>

                <?php } } } ?>
                
                </div>

              <?php echo do_shortcode( '[ssba]' ); ?>
              
              <!--
              <div class="rwc_img">
                  <?php the_post_thumbnail( 'project041-gallery' ); ?>
              </div>
              -->
              
              <?php the_content(); ?>

              <div class="addbanner">
                <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner artículo footer' ) ) : ?>
                <?php endif; ?>
              </div>              
              
              <?php if( has_tag() ) { ?>
              <h5 class="post-tags"><?php _e( 'Etiquetas', 'project-041' ); ?>: <?php the_tags( '', ' | ', '' ); ?></h5>
              <?php } ?>
              
            </div>
          </div>
          
        </div>
        <div class="col-sm-4">

            <?php get_sidebar( 'articulo' ); ?>

        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
