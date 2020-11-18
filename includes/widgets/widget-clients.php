<?php
/**
 * Widget: Clients
 *
 * @package Stag_Customizer
 */
class Stag_Widget_Clients extends WP_Widget {

    /**
     * Constructor
     */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'section-clients',
			'description' => __( 'Displays multiple images as a showcase.', 'forest-assistant' ),
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'stag_widget_clients',
		);
		parent::__construct( 'stag_widget_clients', __( 'Section: Clients', 'forest-assistant' ), $widget_ops, $control_ops );
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
		$urls  = $instance['urls'];

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title; }

		$urls = explode( "\n", $urls );
	?>

	  <div class="grids">
		<?php foreach ( $urls as $url ) : ?>
	  <figure class="grid-3">
		<img src="<?php echo esc_url( $url ); ?>" alt="">
	  </figure>
		<?php endforeach; ?>
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
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['urls']  = strip_tags( $new_instance['urls'] );

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
			'urls'  => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		/* HERE GOES THE FORM */
	?>

	<p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'urls' ); ?>"><?php _e( 'Image URLs:', 'forest-assistant' ); ?></label>
		<textarea rows="16" cols="20" class="widefat" id="<?php echo $this->get_field_id( 'urls' ); ?>" name="<?php echo $this->get_field_name( 'urls' ); ?>"><?php echo @$instance['urls']; ?></textarea>
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

add_action( 'widgets_init', array( 'Stag_Widget_Clients', 'register' ) );
