<?php
/*
Plugin Name: Project041 - Próximos eventos
Description: Muestra los próximos eventos
Author: Blogestudio
Version: 1.0
*/


class project041_widget_events extends WP_Widget {

	function project041_widget_events() {

		$widget_ops = array( 'classname' => 'project041_widget_events', 'description' => 'Muestra los próximos eventos' );
		$this->WP_Widget( 'project041_widget_events', 'Project041 - Eventos', $widget_ops );

	}

	function form( $instance ) {
        
        $instance = wp_parse_args( (array) $instance, array( 'title' => _x( 'Próximos eventos', 'project-041' ), 'number' => 5 ) );
        $title = $instance[ 'title' ];
        $number = $instance[ 'number' ];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título: 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo attribute_escape( $title ); ?>" />
            </label>			
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>">Nº de eventos: <br/>
                <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo attribute_escape( $number ); ?>" />
            </label>
        </p>			
        <?php

    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;	
        $instance[ 'title' ] = $new_instance[ 'title' ];
        $instance[ 'number' ] = $new_instance[ 'number' ];
        return $instance;

    }
	
	function widget( $args, $instance ) {

        echo $before_widget;
        
        $title = $instance['title'];
        $number = ( intval( $instance['number'] ) > 0 ? $instance['number'] : 5 ); 

		$next_events = Project041_Event::get_next_events( $number );

		?>

		<?php // ************************ Begin Widget Code ******************************************/ ?>

            <div class="widget right_bt">
            <h2><?php echo $title; ?></h2>
            <div class="evanto_area">
              <ul class="evanto_list clearfix">
                
              <?php foreach( $next_events as $event ) { ?>

					<li>
                        <h3><a href="<?php echo $event->url; ?>" /><?php echo $event->title; ?></a></h3>
                        <p><?php echo $event->date; ?> | <?php echo $event->place; ?></p>
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
