<?php
/*
Plugin Name: Project041 - Suscripción a newsletter
Description: Muestra el form de suscripción a la newsletter
Author: Blogestudio
Version: 1.0
*/


class project041_widget_subscription extends WP_Widget {

	function project041_widget_subscription() {

		$widget_ops = array( 'classname' => 'project041_widget_subscription', 'description' => 'Muestra el form de suscripción a la newsletter' );
		$this->WP_Widget( 'project041_widget_subscription', 'Project041 - Suscripción a newsletter', $widget_ops );

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
            <div class="newslater_area">

                <!-- Begin MailChimp Signup Form -->
                <div id="mc_embed_signup">
                    <form action="https://project041.us17.list-manage.com/subscribe/post?u=9039f9db50c0fbd27be411ff8&amp;id=c16c07506f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <div id="mc_embed_signup_scroll">
                            <div class="mc-field-group">
                                <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="<?php _e( 'Tu email', 'project-041' ); ?>" />
                            </div>
                            <div class="mc-checkbox">
                                <label>Acepto las condiciones de Project041</label>
                            </div>
                            <div id="mce-responses" class="clear">
                                <div class="response" id="mce-error-response" style="display:none"></div>
                                <div class="response" id="mce-success-response" style="display:none"></div>
                            </div>    
                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_9039f9db50c0fbd27be411ff8_c16c07506f" tabindex="-1" value=""></div>
                            <div class="clear"><input type="submit" value="<?php _e( 'Enviar', 'project-041' ); ?>" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                        </div>
                    </form>
                </div>
                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='BIRTHDAY';ftypes[3]='birthday'; /*
                * Translated default messages for the $ validation plugin.
                * Locale: ES
                */
                $.extend($.validator.messages, {
                required: "<?php _e( 'Por favor, introduce tu email', 'project-041' ); ?>",
                remote: "<?php _e( 'Por favor, rellena este campo', 'project-041' ); ?>",
                email: "<?php _e( 'Por favor, escribe una dirección de correo válida', 'project-041' ); ?>"
                });}(jQuery));var $mcj = jQuery.noConflict(true);</script>

                <!-- End mc_embed_signup-->


            </div>
          </div>

		<?php // ************************ End Widget Code ******************************************/ ?>

		<?php

        }

		echo $after_widget;

	}

}


?>
