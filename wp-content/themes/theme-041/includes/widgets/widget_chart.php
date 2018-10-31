<?php
/*
Plugin Name: Project041 - Chart of the week
Description: Muestra la gráfica de la semana
Author: Blogestudio
Version: 1.0
*/


class project041_widget_chart extends WP_Widget {

	function project041_widget_chart() {

		$widget_ops = array( 'classname' => 'project041_widget_chart', 'description' => 'Muestra la gráfica de la semana' );
		$this->WP_Widget( 'project041_widget_chart', 'Project041 - Chart of the week', $widget_ops );

	}

	function form( $instance ) {
        
        $instance = wp_parse_args( (array) $instance, array( 'title' => _x( 'Chart of the week', 'project-041' ) ) );
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

        <div class="widget">
            <h2><?php echo $title; ?></h2>
            <div class="widget-chart-content">
                <?php echo Project041_Chart::render_latest_chart(); ?>
            </div>
        </div>

		<?php // ************************ End Widget Code ******************************************/ ?>

		<?php

		echo $after_widget;

	}

}


?>
