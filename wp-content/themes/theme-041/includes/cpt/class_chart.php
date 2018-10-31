<?php

if( !class_exists( 'Project041_Chart' ) ) {
	
	class Project041_Chart {

		private static $_version;

		public static $prefix;

		/*-----------------------------------------------------------------------------------*/
		// Class constructor
		/*-----------------------------------------------------------------------------------*/
		public function __construct() {

			self::$_version = '1.0.0';

			self::$prefix = '_project041_chart_';

			// Register CPT
			add_action( 'init', array( $this, 'register_cpt' ) );			

		}

		/*-----------------------------------------------------------------------------------*/
		// Register custom post type "Chart of the week"
		/*-----------------------------------------------------------------------------------*/
		function register_cpt() {

			$labels = array(
				'name'               => __( 'Charts' ),
				'singular_name'      => __( 'Chart' ),
				'add_new'            => __( 'Añade nuevo chart' ),
				'add_new_item'       => __( 'Añade nuevo chart' ),
				'edit_item'          => __( 'Editar' ),
				'new_item'           => __( 'Nuevo' ),
				'all_items'          => __( 'Todos' ),
				'view_item'          => __( 'Ver' ),
				'search_items'       => __( 'Buscar' ),
				'not_found'          => __( 'No se han encontrado charts' ),
				'not_found_in_trash' => __( 'No se han encontrado charts en la papelera' ), 
				'parent_item_colon'  => '',
				'menu_name'          => 'Chart of the week'
			);
			$args = array(
				'labels'        => $labels,
				'description'   => 'Chart of the week',
				'public'        => true,
				'menu_position' => 21,
				'hierarchical'  => true,
				'supports'      => array( 'title', 'editor', 'thumbnail' ),
				'has_archive'   => true,
				'rewrite'		=> array( 'slug' => 'chart', 'with_front' => false )              
			);
			register_post_type( 'chart', $args );	

		}

		/*-----------------------------------------------------------------------------------*/
        // Render latest chart
		/*-----------------------------------------------------------------------------------*/
        public static function render_latest_chart() {

            $args = array(
                'post_type' => 'chart',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC'
            );

            $query = new WP_Query( $args );
            if( $query->have_posts() ) {
				$query->the_post();
			?>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'project041-featured' )?></a>
			<?php
            }
            
        }
	}
}

new Project041_Chart();