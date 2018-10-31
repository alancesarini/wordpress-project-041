<?php

if( !class_exists( 'Project041_Functions' ) ) {

	class Project041_Functions {

		private static $_this;

		private static $_version;

		function __construct() {
		
			self::$_this = $this;

			self::$_version = '1.0.0';	

		}

		static function this() {
		
			return self::$_this;
		
		}

		public static function render_all_authors() {

			$authors = get_users( array( 'role__in' => array( 'author', 'editor', 'contributor' ), 'orderby' => 'display_name' ) );
			$featured_authors = get_option( Project041_Configuration::$prefix . 'featured_authors' );

			if( is_array( $featured_authors ) ) {
				foreach( $featured_authors as $author_id ) {
					$author = get_user_by( 'id', $author_id ); 
					$ordered_authors[] = array( 'letter' => '', 'data' => $author );
				}
			} else {
				$ordered_authors = array();
			}
			$first_letters = array();
			foreach( $authors as $author ) {
				$first_letter = strtoupper( substr( $author->display_name, 0, 1 ) );
				if( !in_array( $first_letter, $first_letters ) ) {
					if( !in_array( $author->ID, $featured_authors ) ) {
						$ordered_authors[] = array( 'letter' => $first_letter, 'data' => $author );
						$first_letters[] = $first_letter;
					}
				} else {
					if( !in_array( $author->ID, $featured_authors ) ) {
						$ordered_authors[] = array( 'letter' => '', 'data' => $author );
					}
				}
			}

			foreach( $ordered_authors as $author ) {
			?>

				<li>
					<?php if( $author['letter'] != '' ) { ?>
						<a name="authors-<?php echo $author['letter']; ?>"></a>
					<?php } ?>
					
					<div class="colab_img">
						<?php echo get_avatar( $author['data']->ID, 140 ); ?>
					</div>						
					<div class="colab_details">
						<h2><?php echo $author['data']->display_name; ?></h2>
						<p><?php echo get_the_author_meta( 'description', $author['data']->ID ); ?></p>
						<p><br><a href="<?php echo get_author_posts_url( $author['data']->ID ); ?>" class="button-red"><?php _e( 'Ver artículos', 'project-041' ); ?></a></p>
					</div>
				</li>				
			<?php
			}

		}

	}

}

new Project041_Functions();