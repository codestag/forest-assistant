<?php
/**
 * Widget: Services Section
 *
 * @package Stag_Customizer
 */
class Stag_Widget_Services_Section extends WP_Widget {

    /**
     * Constructor
     */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'services-section',
			'description' => __( 'Display widgets from Services Sidebar.', 'forest-assistant' ),
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'stag_widget_services_section',
		);
		parent::__construct( 'stag_widget_services_section', __( 'Section: Services Section', 'forest-assistant' ), $widget_ops, $control_ops );
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
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title; }

		echo '<div class="grids all-services">';
		dynamic_sidebar( 'sidebar-services' );
		echo '</div>';

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

		$instance['title'] = strip_tags( $new_instance['title'] );

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
			'title' => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		/* HERE GOES THE FORM */
		?>

	  <p>
		<span class="description"><?php _e( 'Configure this area by adding "Service Box" widget at "Service Boxes Section".', 'forest-assistant' ); ?></span>
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
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
add_action( 'widgets_init', array( 'Stag_Widget_Services_Section', 'register' ) );
