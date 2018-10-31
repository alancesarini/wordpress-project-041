<?php
/*
Plugin Name: Project041 - Sponsors de un evento
Description: Muestra los logos de los sponsors de un evento
Author: Blogestudio
Version: 1.0
*/


class project041_widget_sponsors_event extends WP_Widget {

	function project041_widget_sponsors_event() {

		$widget_ops = array( 'classname' => 'project041_widget_sponsors_event', 'description' => 'Muestra los logos de los patrccinadores de un evento' );
		$this->WP_Widget( 'project041_widget_sponsors_event', 'Project041 - Patrocinadores de un evento', $widget_ops );

	}

	function form( $instance ) {
        
        $instance = wp_parse_args( (array) $instance, array( 'title' => _x( 'Patrocinado por', 'project-041' ) ) );
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

		global $post;
		
		?>

		<?php // ************************ Begin Widget Code ******************************************/ ?>

		<div class="widget right_bt">
            <h2><?php echo $title; ?></h2>
            <ul class="client_logo clearfix">
			<?php while( have_rows('_project041_event_sponsors', $post->ID ) ): the_row();  ?>
			
				<img src="<?php echo the_sub_field( 'sponsor', $post->ID ); ?>" />
			
			<?php endwhile; ?>
            </ul>
		</div>

		<?php // ************************ End Widget Code ******************************************/ ?>

		<?php

		echo $after_widget;

	}

}


?>
