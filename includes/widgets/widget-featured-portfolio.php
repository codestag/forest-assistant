<?php
/**
 * Widget: Featured Portfolio
 *
 * @package Stag_Customizer
 */
class Stag_Widget_Featured_Portfolio extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'widget-featured-portfolio',
			'description' => __( 'Displays testimonials.', 'forest-assistant' ),
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'stag_widget_featured_portfolio',
		);
		parent::__construct( 'stag_widget_featured_portfolio', __( 'Featured Portfolio', 'forest-assistant' ), $widget_ops, $control_ops );
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
		$title   = apply_filters( 'widget_title', $instance['title'] );
		$post_id = $instance['post_id'];

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		$posts = get_posts(
			array(
				'posts_per_page' => 1,
				'post_type'      => 'portfolio',
				'include'        => $post_id,
			)
		);

		foreach ( $posts as $post ) {
			if ( ! has_post_thumbnail( $post->ID ) ) {
				return;
			}
			$portfolio_sub_title = get_post_meta( $post->ID, '_stag_portfolio_subtitle', true );
			?>


			<a href="<?php echo get_permalink( $post->ID ); ?>">
				<figure class="portfolio-thumb">
					<?php echo get_the_post_thumbnail( $post->ID, 'portfolio-thumb' ); ?>
				</figure>
				<h3 class="entry-title"><?php echo get_the_title( $post->ID ); ?></h3>
			</a>

			<?php
			if ( $portfolio_sub_title != '' ) {
				echo "<p>{$portfolio_sub_title}</p>";
			}
		}

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
		$instance['title']   = strip_tags( $new_instance['title'] );
		$instance['post_id'] = strip_tags( $new_instance['post_id'] );

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
			'title'   => '',
			'post_id' => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		/* HERE GOES THE FORM */
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'forest-assistant' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_id' ); ?>"><?php _e( 'Post ID:', 'forest-assistant' ); ?></label>
			<input type="text" class="small-text" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>" value="<?php echo $instance['post_id']; ?>" />
			<span class="description"><?php _e( 'Enter the Post ID of portfolio page.', 'forest-assistant' ); ?></span>
		</p>

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

add_action( 'widgets_init', array( 'Stag_Widget_Featured_Portfolio', 'register' ) );
