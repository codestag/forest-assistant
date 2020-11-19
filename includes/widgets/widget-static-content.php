<?php
/**
 * Widget: Static Content
 *
 * @package Stag_Customizer
 */
class Stag_Widget_Static_Content extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'static-content',
			'description' => __( 'Displays content from a specific page.', 'forest-assistant' ),
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'stag_widget_static_content',
		);
		parent::__construct( 'stag_widget_static_content', __( 'Section: Static Content', 'forest-assistant' ), $widget_ops, $control_ops );
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
		$title      = apply_filters( 'widget_title', $instance['title'] );
		$page       = $instance['page'];
		$bg         = $instance['bg'];
		$bg_image   = $instance['bg_image'];
		$bg_opacity = $instance['bg_opacity'];
		$color      = $instance['color'];
		$link       = $instance['link'];

		echo $before_widget;

		$the_page = get_page( $page );

		$query_args = array(
			'page_id'        => $page,
			'posts_per_page' => 1,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) :
			$query->the_post();

			?>

		<article <?php post_class(); ?> data-bg-color="<?php echo $bg; ?>" data-bg-image="<?php echo $bg_image; ?>" data-bg-opacity="<?php echo $bg_opacity; ?>" data-text-color="<?php echo $color; ?>" data-link-color="<?php echo $link; ?>">
			<?php
			if ( $title != '' ) {
				echo $before_title . $title . $after_title;
			}
			?>
			<div class="entry-content">
				<?php
					global $more;
					$more = false;
					the_content( __( 'Continue Reading', 'forest-assistant' ) );
					wp_link_pages(
						array(
							'before'         => '<p><strong>' . __( 'Pages:', 'forest-assistant' ) . '</strong> ',
							'after'          => '</p>',
							'next_or_number' => 'number',
						)
					);
				?>
			</div>
		</article>

			<?php

		endwhile;

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
		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['page']       = strip_tags( $new_instance['page'] );
		$instance['bg']         = strip_tags( $new_instance['bg'] );
		$instance['bg_image']   = strip_tags( $new_instance['bg_image'] );
		$instance['bg_opacity'] = strip_tags( $new_instance['bg_opacity'] );
		$instance['color']      = strip_tags( $new_instance['color'] );
		$instance['link']       = strip_tags( $new_instance['link'] );

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
			'title'      => '',
			'page'       => 0,
			'bg'         => '',
			'bg_image'   => '',
			'bg_opacity' => 50,
			'color'      => '',
			'link'       => '',

		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		/* HERE GOES THE FORM */
		?>

	<p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'page' ); ?>"><?php _e( 'Select Page:', 'forest-assistant' ); ?></label>

	  <select id="<?php echo $this->get_field_id( 'page' ); ?>" name="<?php echo $this->get_field_name( 'page' ); ?>" class="widefat">
		<?php

		$args  = array(
			'sort_order'  => 'ASC',
			'sort_column' => 'post_title',
		);
		$pages = get_pages( $args );

		foreach ( $pages as $paged ) {
			?>
			<option value="<?php echo $paged->ID; ?>"
										<?php
										if ( $instance['page'] == $paged->ID ) {
											echo 'selected';}
										?>
><?php echo $paged->post_title; ?></option>
			<?php
		}

		?>
	 </select>
	 <span class="description">This page will be used to display static content.</span>
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'bg' ); ?>"><?php _e( 'Background Color:', 'forest-assistant' ); ?></label><br>
	  <input type="text" name="<?php echo $this->get_field_name( 'bg' ); ?>" id="<?php echo $this->get_field_id( 'bg' ); ?>" value="<?php echo $instance['bg']; ?>" />
	  <script>jQuery('#<?php echo $this->get_field_id( 'bg' ); ?>').wpColorPicker();</script>
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'bg_image' ); ?>"><?php _e( 'Background Image URL:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'bg_image' ); ?>" name="<?php echo $this->get_field_name( 'bg_image' ); ?>" value="<?php echo $instance['bg_image']; ?>" />
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'bg_opacity' ); ?>"><?php _e( 'Background Opacity:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'bg_opacity' ); ?>" name="<?php echo $this->get_field_name( 'bg_opacity' ); ?>" value="<?php echo $instance['bg_opacity']; ?>" />
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'color' ); ?>"><?php _e( 'Text Color:', 'forest-assistant' ); ?></label><br>
	  <input type="text" name="<?php echo $this->get_field_name( 'color' ); ?>" id="<?php echo $this->get_field_id( 'color' ); ?>" value="<?php echo $instance['color']; ?>" />
	  <script>jQuery('#<?php echo $this->get_field_id( 'color' ); ?>').wpColorPicker();</script>
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link Color:', 'forest-assistant' ); ?></label><br>
	  <input type="text" name="<?php echo $this->get_field_name( 'link' ); ?>" id="<?php echo $this->get_field_id( 'link' ); ?>" value="<?php echo $instance['link']; ?>" />
	  <script>jQuery('#<?php echo $this->get_field_id( 'link' ); ?>').wpColorPicker();</script>
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

add_action( 'widgets_init', array( 'Stag_Widget_Static_Content', 'register' ) );
