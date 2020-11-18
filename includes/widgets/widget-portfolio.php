<?php
/**
 * Widget: Portfolio
 *
 * @package Stag_Customizer
 */
class Stag_Widget_Portfolio extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'section-portfolio',
			'description' => __( 'Display portfolio items.', 'forest-assistant' ),
		);
		$control_ops = array(
			'width'   => 300,
			'height'  => 350,
			'id_base' => 'stag_widget_portfolio',
		);
		parent::__construct( 'stag_widget_portfolio', __( 'Section: Portfolio', 'forest-assistant' ), $widget_ops, $control_ops );
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
		$button_link = $instance['button_link'];
		$post_count  = $instance['post_count'];

		echo $before_widget;

		if ( $button_link != '' ) {
			?>
		  <a href="<?php echo $button_link; ?>" class="button portfolio-button"><?php echo $button_text; ?></a>
			<?php
		}

		?>

		<?php
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>

	<ul class="portfolio-filter">
	  <li class="button filter" data-filter="all"><?php _e( 'All', 'forest-assistant' ); ?></li>
		<?php

		$terms = get_terms( 'skill' );
		$count = count( $terms );
		$i     = 0;

		if ( $count > 0 ) {
			foreach ( $terms as $term ) {
				echo "<li class='button filter' data-filter='{$term->slug}'>{$term->name}</li>";
			}
		}

		?>
	  </ul>

	  <ul id="portfolio-filter" class="grids portfolios">
		<?php
		query_posts(
			array(
				'post_type'      => 'portfolio',
				'posts_per_page' => $post_count,
			)
		);

		$unique_skills = array();

		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();

				if ( ! has_post_thumbnail() ) {
					continue;
				}

				$skills = get_the_terms( get_the_ID(), 'skill' );
				$skill  = array();
				if ( $skills ) {
					foreach ( $skills as $ski ) {
						$skill[]         = $ski->slug;
						$unique_skills[] = $ski->slug;
					}
					$skill = implode( $skill, ' ' );
				}

				$class = 'grid-4 mix ' . $skill;

				?>

					<li id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>

		  <figure class="portfolio-thumb">

			  <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'forest-assistant' ), get_the_title() ); ?>">
				  <div class="portfolio-preview">
					  <button class="button-secondary"><i class="icon-eye"></i> <?php _e( 'Details', 'forest-assistant' ); ?></button>
				  </div>
					<?php

					$src  = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'portfolio-thumb' );
					$attr = array(
						'src'      => get_template_directory_uri() . '/assets/img/blank.gif',
						'data-src' => $src[0],
						'class'    => 'lazy',
					);

					?>
								<?php echo get_the_post_thumbnail( get_the_ID(), 'portfolio-thumb', $attr ); ?>
							</a>

						</figure>

						<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'forest-assistant' ), get_the_title() ); ?>"> <?php the_title(); ?></a></h3>
						<?php

						$portfolio_sub_title = get_post_meta( get_the_ID(), '_stag_portfolio_subtitle', true );

						if ( $portfolio_sub_title ) {
							echo "<p>{$portfolio_sub_title}</p>";
						}

						?>
					</li>

					<?php

		endwhile;

		endif;

		wp_reset_query();

		$all_terms = array();

		foreach ( $terms as $term ) {
			$all_terms[] = $term->slug;
		}

		?>

		<span id="all-skills" data-all-skills='<?php echo json_encode( array_values( array_unique( $unique_skills ) ) ); ?>' data-all-filters='<?php echo json_encode( array_unique( $all_terms ) ); ?>'></span>

	  </ul>

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
		$instance['button_link'] = strip_tags( $new_instance['button_link'] );
		$instance['post_count']  = strip_tags( $new_instance['post_count'] );

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
			'title'       => 'Featured Work',
			'button_text' => 'Go to Portfolio',
			'button_link' => '',
			'post_count'  => 9,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		/* HERE GOES THE FORM */
		?>

	  <p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'button_link' ); ?>"><?php _e( 'Portfolio Link:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button_link' ); ?>" name="<?php echo $this->get_field_name( 'button_link' ); ?>" value="<?php echo $instance['button_link']; ?>" />
	  <span class="description"><?php _e( 'Enter the portfolio page URL.', 'forest-assistant' ); ?></span>
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button Text:', 'forest-assistant' ); ?></label>
	  <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $instance['button_text']; ?>" />
	  <span class="description"><?php _e( 'Enter text for the portfolio button.', 'forest-assistant' ); ?></span>
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Post Count:', 'forest-assistant' ); ?></label>
	  <input type="number" step="3" class="widefat" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" value="<?php echo $instance['post_count']; ?>" />
	  <span class="description"><?php _e( 'Enter the number of recent portfolio items to display at homepage.', 'forest-assistant' ); ?></span>
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

add_action( 'widgets_init', array( 'Stag_Widget_Portfolio', 'register' ) );
