<?php
/*
Plugin Name: Project041 - Artículos relacionados custom
Description: Muestra los artículos que se han seleccionado como relacionados
Author: Blogestudio
Version: 1.0
*/


class project041_widget_related_custom extends WP_Widget {

	function project041_widget_related_custom() {

		$widget_ops = array( 'classname' => 'project041_widget_related_custom', 'description' => 'Muestra los artículos que se han seleccionado como relacionados' );
		$this->WP_Widget( 'project041_widget_related_custom', 'Project041 - Artículos relacionados custom', $widget_ops );

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
                    <?php if( have_rows( '_project041_post_related' ) ) {
                        while( have_rows( '_project041_post_related' ) ) { 
                            the_row();
                            $related = get_sub_field( 'related' ); 
                            if( $related != null ) { ?>  
                                <li>
                                    <?php echo get_the_post_thumbnail( $related->ID, 'project041-tiny', array( 'class' => 'img-responsive' ) ); ?>
                                    <a href="<?php echo home_url( $related->post_name ); ?>"><?php echo $related->post_title; ?></a>
                                    <p> <?php echo get_the_author_meta( 'display_name', $related->post_author ); ?> | <?php echo get_the_time( 'd-m-Y', $related->ID ); ?></p>
                                </li>
                            <?php }                            
                         } ?>
           
                    <?php } else {  
                    
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
