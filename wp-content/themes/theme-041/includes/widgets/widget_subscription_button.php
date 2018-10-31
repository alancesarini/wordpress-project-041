<?php
/*
Plugin Name: Project041 - Botón de suscripción a newsletter
Description: Muestra el botón de suscripción a la newsletter
Author: Blogestudio
Version: 1.0
*/


class project041_widget_subscription_button extends WP_Widget {

	function project041_widget_subscription_button() {

		$widget_ops = array( 'classname' => 'project041_widget_subscription_button', 'description' => 'Muestra el botón de suscripción a la newsletter' );
		$this->WP_Widget( 'project041_widget_subscription_button', 'Project041 - Botón de suscripción a newsletter', $widget_ops );

	}

	function form( $instance ) {
        
        $instance = wp_parse_args( (array) $instance, array( 'title' => _x( 'Recibe la newsletter de los profesionales de fondos', 'project-041' ) ) );
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
        $page_slug = $post->post_name;

        if( $page_slug != 'newsletter' ) {
		?>

		<?php // ************************ Begin Widget Code ******************************************/ ?>

        <div class="widget newslater">
            <p><?php echo $title; ?></p>
            <p><br/><a href="<?php echo home_url( 'newsletter' ); ?>" class="button-red">Suscribirse</a></p>
        </div>

		<?php // ************************ End Widget Code ******************************************/ ?>

		<?php

        }

		echo $after_widget;

	}

}


?>
