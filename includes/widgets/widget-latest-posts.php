<?php
/**
 * Widget: Latest Posts
 *
 * @package Stag_Customizer
 */
class Stag_Widget_Latest_Posts extends WP_Widget {

    /**
     * Constructor
     */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'section-latest-posts',
			'description' => __( 'Displays latest blog posts.', 'forest-assistant' ),
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'stag_widget_latest_posts',
		);
		parent::__construct( 'stag_widget_latest_posts', __( 'Section: Latest Posts', 'forest-assistant' ), $widget_ops, $control_ops );
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
		$button_text = $instance['button_text'];

		echo $before_widget;

		if ( $button_text ) {
			?>
			<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="button"><?php echo $button_text; ?></a>
			<?php
		}

		if ( $title ) {
			echo $before_title . $title . $after_title; }

		?>

		<div class="grids">
			<?php

			query_posts(
				array(
					'post_type'           => 'post',
					'posts_per_page'      => 2,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
				)
			);

			while ( have_posts() ) :
				the_post();
			?>

			<article <?php post_class( 'grid-6' ); ?> id="post-<?php the_ID(); ?>">

				<?php if ( has_post_thumbnail() ) : ?>
				<figure class="entry-image">
					<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'forest-assistant' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_post_thumbnail(); ?></a>
				</figure>
				<?php endif; ?>

				<div class="inner">
					<h3 class="entry-title">
						<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'forest-assistant' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h3>
					<?php

					echo sprintf(
						'<div class="entry-metadata"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a> in category <span class="category">%5$s</span></div>',
						esc_url( get_permalink() ),
						esc_attr( sprintf( __( 'Permalink to %s', 'forest-assistant' ), the_title_attribute( 'echo=0' ) ) ),
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date() ),
						get_the_category_list( __( ', ', 'forest-assistant' ) )
					);

					?>
					<div class="entry-content">
						<?php the_excerpt(); ?>
					</div>
				</div>
			</article>

			<?php
			endwhile;
			wp_reset_query();

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
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );

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
			'button_text' => 'Go to Blog',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		/* HERE GOES THE FORM */
	?>

	<p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo @$instance['title']; ?>" />
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button Text:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo @$instance['button_text']; ?>" />
	  <span class="description"><?php _e( 'Enter the text for blog button', 'forest-assistant' ); ?></span>
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

add_action( 'widgets_init', array( 'Stag_Widget_Latest_Posts', 'register' ) );