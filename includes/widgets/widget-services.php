<?php
/**
 * Widget: Service Box
 *
 * @package Stag_Customizer
 */
class Stag_Widget_Services extends WP_Widget {

    /**
     * Constructor
     */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'service',
			'description' => __( 'Display latest posts from blog.', 'forest-assistant' ),
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'stag_widget_services',
		);
		parent::__construct( 'stag_widget_services', __( 'Service Box', 'forest-assistant' ), $widget_ops, $control_ops );
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
		$title       = apply_filters( 'widget_title', $instance['title'] );
		$description = $instance['description'];
		$custom_icon = $instance['customicon'];

		echo $before_widget;

	?>


		<?php
		if ( ! empty( $custom_icon ) ) {
			echo '<div class="custom-icon"><img src="' . $custom_icon . '" alt=""></div>';
		}
		?>

		<div class="service-content">
			<?php
			echo "\n\t" . $before_title . htmlspecialchars_decode( $title ) . $after_title;
			if ( ! empty( $description ) ) {
				echo "\n\t<div class='service__description'>" . htmlspecialchars_decode( $description ) . '</div>';
			}
			?>
		</div>

		<?php

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
		$instance['title']       = $new_instance['title'];
		$instance['description'] = $new_instance['description'];
		$instance['customicon']  = strip_tags( $new_instance['customicon'] );

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
			'title'       => '',
			'customicon'  => '',
			'description' => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		/* HERE GOES THE FORM */
	?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'forest-assistant' ); ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:', 'forest-assistant' ); ?></label>
		<textarea rows="5" class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo @$instance['description']; ?></textarea>
		<span class="description"><?php _e( 'Some HTML would not harm.', 'forest-assistant' ); ?></span>
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'customicon' ); ?>"><?php _e( 'Custom Icon URL:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'customicon' ); ?>" name="<?php echo $this->get_field_name( 'customicon' ); ?>" value="<?php echo @$instance['customicon']; ?>" />
	  <span class="description"><?php _e( 'Enter the custom icon URL if you want to use your own icon or choose one below. Recommended size 100x100px.', 'forest-assistant' ); ?></span>
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

add_action( 'widgets_init', array( 'Stag_Widget_Services', 'register' ) );
