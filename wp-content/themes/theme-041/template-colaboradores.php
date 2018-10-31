<?php 

/**
 * Template name: Colaboradores
 */

get_header(); 

if( have_posts() ) {
    the_post();
		$authors = get_users( array( 'role__in' => array( 'author', 'editor', 'contributor' ), 'orderby' => 'display_name' ) );
    $letters = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
    $letters_with_authors = array();
    foreach( $letters as $letter ) {
      $has_authors = false;
      foreach( $authors as $author ) {
        if( strtoupper( substr( $author->display_name, 0, 1 ) ) == $letter ) {
          $has_authors = true;
        }
      }
      if( $has_authors ) {
        $letters_with_authors[] = $letter;
      }
    }
  }
?>

<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8">
          <div class="row">
            <div class="actual_area">
                <h1><?php the_title(); ?></h1>   
                <div class="authors-initials">
                  <?php foreach( $letters_with_authors as $letter ) { ?>
                    <a href="#authors-<?php echo $letter; ?>" class="link-to-letter"><?php echo $letter; ?></a>
                  <?php } ?>
                </div>         
                <ul class="actual_list clearfix">
                
                    <?php Project041_Functions::render_all_authors(); ?>

                </ul>

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
