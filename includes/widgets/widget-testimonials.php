<?php
/**
 * Widget: Testimonials
 *
 * @package Stag_Customizer
 */

if ( ! class_exists( 'Forest_Widget_Testimonials' ) ) :
	/**
	 * Display testimonials.
	 */
	class Forest_Widget_Testimonials extends Forest_Widget {
		/**
		 * Constructor
		 */
		public function __construct() {
			$this->widget_id          = 'stag_widget_testimonials';
			$this->widget_class       = 'section-testimonials';
			$this->widget_name        = esc_html__( 'Section: Testimonials', 'forest-assistant' );
			$this->widget_description = esc_html__( 'Displays testimonials.', 'forest-assistant' );
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Title:', 'forest-assistant' ),
				),
				'count' => array(
					'type'  => 'number',
					'std'   => '3',
					'step'  => '1',
					'min'   => '1',
					'max'   => '50',
					'label' => esc_html__( 'Testimonials count:', 'forest-assistant' ),
				),
				'cycle_speed' => array(
					'type'  => 'number',
					'std'   => '4000',
					'step'  => '1000',
					'min'   => '1000',
					'max'   => '100000000',
					'label' => esc_html__( 'Slideshow speed:', 'forest-assistant' ),
				),
			);

			parent::__construct();
		}

		/**
		 * Output the widget content on the page.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current widget instance.
		 */
		public function widget( $args, $instance ) {
			$title       = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$count       = isset( $instance['count'] ) ? esc_attr( $instance['count'] ) : '3';
			$cycle_speed = isset( $instance['cycle_speed'] ) ? esc_attr( $instance['cycle_speed'] ) : '4000';

			$testimonials = new WP_Query( array(
				'post_type'      => 'testimonials',
				'post_status'    => 'publish',
				'posts_per_page' => $count,
			) );

			if ( $testimonials->have_posts() ) :
				echo $args['before_widget']; // WPCS: XSS ok.

				echo $args['before_title'] . $title . $args['after_title']; // WPCS: XSS ok.

				echo "<div class='testimonials-slideshow' data-cycle-timeout='{$cycle_speed}' data-cycle-speed='400' data-cycle-swipe='true'>"; // WPCS: XSS ok.

				while ( $testimonials->have_posts() ) :
					$testimonials->the_post();
					?>

					<blockquote>
						<i class="icon-testimonial"></i>
						<?php the_content(); ?>
						<footer><?php the_title(); ?></footer>
					</blockquote>

					<?php
				endwhile;

				echo '<div class="cycle-pager"></div></div>';

				wp_reset_postdata();

				echo $args['after_widget']; // WPCS: XSS ok.
			endif;
		}

		/**
		 * Registers the widget with the WordPress Widget API.
		 *
		 * @return mixed
		 */
		public static function register() {
			register_widget( __CLASS__ );
		}
	}
endif;

add_action( 'widgets_init', array( 'Forest_Widget_Testimonials', 'register' ) );
