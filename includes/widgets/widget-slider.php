<?php
/**
 * Widget: Slider
 *
 * @package Stag_Customizer
 */
class Stag_Widget_Slider extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'section-slider',
			'description' => __( 'Displays the slideshow.', 'forest-assistant' ),
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'stag_widget_slider',
		);
		parent::__construct( 'stag_widget_slider', __( 'Section: Slider', 'forest-assistant' ), $widget_ops, $control_ops );
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
		extract( $args );

		// VARS FROM WIDGET SETTINGS
		echo $before_widget;

		query_posts(
			array(
				'post_type'      => 'slides',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		if ( have_posts() ) :
			?>

		<div id="slider" class="flexslider">
			<ul class="slides">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<?php
					$button_link = get_post_meta( get_the_ID(), '_stag_slider_link', true );
					$button_text = get_post_meta( get_the_ID(), '_stag_slider_text', true );
					?>
				<li>
					<div class="flex-caption">
						<div class="flex-caption--inner">
							<div class="flex-content">
								<h2><?php the_title(); ?></h2>
								<?php if ( $button_link ) : ?>
								<a href="<?php echo $button_link; ?>" class="button-secondary"><?php echo $button_text; ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<img src="<?php echo get_post_meta( get_the_ID(), '_stag_slider_image', true ); ?>" alt="">
				</li>
				<?php endwhile; ?>
			</ul>
			<div class="flex-container"></div>
		</div>

			<?php
		endif;

		wp_reset_query();

		echo $after_widget;
	}

	/**
	 * Update function.
	 *
	 * @see WP_Widget->update
	 * @access public
	 * @param array $new_instance New widget settings.
	 * @param array $old_instance Old widget settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// STRIP TAGS TO REMOVE HTML
		// $instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	/**
	 * Display the widget form settings.
	 *
	 * @see WP_Widget->form
	 * @access public
	 * @param array $instance Current widget instance.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			/* Deafult options goes here */
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		/* HERE GOES THE FORM */
		?>

	<p><span class="description"><?php _e( 'Yay! No options to set!', 'forest-assistant' ); ?></span></p>

		<?php
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

add_action( 'widgets_init', array( 'Stag_Widget_Slider', 'register' ) );
