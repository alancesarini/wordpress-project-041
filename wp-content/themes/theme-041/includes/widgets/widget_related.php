<?php
/*
Plugin Name: Project041 - Artículos relacionados
Description: Muestra los artículos relacionados por etiquetas
Author: Blogestudio
Version: 1.0
*/


class project041_widget_related extends WP_Widget {

	function project041_widget_related() {

		$widget_ops = array( 'classname' => 'project041_widget_related', 'description' => 'Muestra los artículos relacionados' );
		$this->WP_Widget( 'project041_widget_related', 'Project041 - Artículos relacionados', $widget_ops );

	}

	function form( $instance ) {
        
        $instance = wp_parse_args( (array) $instance, array( 'title' => _x( 'Artículos relacionados', 'project-041' ) ) );
        $title = $instance[ 'title' ];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título: 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo attribute_escape( $title ); ?>" />
            </label>			
        </p>		
        <?php

    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;	
        $instance[ 'title' ] = $new_instance[ 'title' ];
        return $instance;

    }
	
	function widget( $args, $instance ) {

		echo $before_widget;

		$title = $instance['title'];
		
		?>

		<?php // ************************ Begin Widget Code ******************************************/ ?>

        <div class="widget widget-related right_box">
            <h2><?php echo $title; ?></h2>
            <div class="articulos_area">
              <ul class="articulos_list">
                <?php
					$post_tags = wp_get_post_terms( get_the_ID(), 'post_tag', array( 'fields' => 'term_id' ) );
					$args = array(
						'post_type' => 'post',
						'post_status' => 'publish',
						'tag__in' => $post_tags,
						'post__not_in' => array( get_the_ID() ),
						'posts_per_page' => 3,
						'orderby' => 'date',
						'order' => 'DESC'
					);

					$query = new WP_Query( $args );

					while( $query->have_posts() ) {
						$query->the_post();                
                ?>                  
                        <li>
                            <?php the_post_thumbnail( 'project041-tiny', array( 'class' => 'img-responsive' ) ); ?>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <p> <?php the_author(); ?> | <?php the_time( 'd-m-Y' ); ?></p>
                        </li>
                    <?php } ?>
                
              </ul>
            </div>
          </div>

		<?php // ************************ End Widget Code ******************************************/ ?>

		<?php

		echo $after_widget;

	}

}


?>
