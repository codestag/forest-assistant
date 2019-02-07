<?php
/**
 * Widget base class.
 *
 * @package Stag_Customizer
 * @category Widget
 */

/**
 * Widget base
 */
class Forest_Widget extends WP_Widget {

	/**
	 * Widget ID.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $widget_id;

	/**
	 * Widget class.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $widget_class;

	/**
	 * Widget description.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $widget_description;

	/**
	 * Widget name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $widget_name;

	/**
	 * Widget areas this widget can appear on.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $widget_areas = array();

	/**
	 * Notice to display when a widget is in the incorrect location.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $widget_notice;

	/**
	 * Widget settings.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $settings;

	/**
	 * Widget control.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $control_ops;

	/**
	 * Enable selective refresh.
	 *
	 * @since 1.10.0
	 * @var bool
	 */
	public $selective_refresh = true;

	/**
	 * Register a widget.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => $this->widget_class ? $this->widget_class : $this->widget_id,
			'description'                 => $this->widget_description,
			'customize_selective_refresh' => true,
		);

		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops, $this->control_ops );

	}

	/**
	 * Add hooks while registering all widget instances of this widget class.
	 *
	 * @access public
	 */
	public function _register() {
		// Display a notice if widget is in the wrong area.
		add_action( 'admin_print_styles', array( $this, 'widget_screen_notice_css' ) );
		add_action( 'customize_controls_print_styles', array( $this, 'widget_customizer_notice_css' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		parent::_register();
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0
	 *
	 * @param string $hook_suffix enqueue scripts.
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		if ( 'widgets.php' !== $hook_suffix ) {
			return;
		}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
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

		if ( ! $this->settings ) {
			return $instance;
		}

		do_action( 'forest_widget_update_before', $instance, $new_instance, $this );

		foreach ( $this->settings as $key => $setting ) {
			switch ( $setting['type'] ) {
				case 'textarea':
					if ( current_user_can( 'unfiltered_html' ) ) {
						$instance[ $key ] = $new_instance[ $key ];
					} else {
						$instance[ $key ] = wp_kses_data( $new_instance[ $key ] );
					}
					break;

				case 'multicheck':
					$instance[ $key ] = maybe_serialize( $new_instance[ $key ] );
					break;

				case 'text':
				case 'checkbox':
				case 'select':
				case 'number':
				case 'colorpicker':
					$instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
					break;

				default:
					$instance[ $key ] = apply_filters( 'forest_widget_update_type_' . $setting['type'], $new_instance[ $key ], $key, $setting );
					break;
			}
		}

		do_action( 'forest_widget_update_after', $instance, $new_instance, $this );

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
		// Display widget areas notice if available.
		echo $this->widget_areas_notice(); // WPCS: XSS ok.

		// Bail, if no settings.
		if ( ! $this->settings ) {
			return;
		}

		foreach ( $this->settings as $key => $setting ) {

			$value = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];

			switch ( $setting['type'] ) {
				case 'description':
					?>
					<p class="description customize-control-description"><?php echo wp_kses_post( $value ); ?></p>
					<?php
					break;

				case 'text':
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
					</p>
					<?php
					break;

				case 'image':
					wp_enqueue_media();
					wp_enqueue_script( 'app-image-widget-admin', get_template_directory_uri() . '/js/app-image-widget-admin.js', array( 'jquery' ), '', true );
					$id_prefix = $this->get_field_id( '' );
				?>
					<p style="margin-bottom: 0;">
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					</p>

					<p style="margin-top: 3px;">
						<div id="<?php echo esc_attr( $id_prefix ); ?>preview" class="stag-image-preview">
							<style type="text/css">
								.stag-image-preview img { max-width: 100%; border: 1px solid #e5e5e5; padding: 2px; margin-bottom: 5px;  }
							</style>
							<?php if ( ! empty( $value ) ) : ?>
							<img src="<?php echo esc_url( $value ); ?>" alt="">
							<?php endif; ?>
						</div>

						<input type="hidden" class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"value="<?php echo esc_attr( $value ); ?>" placeholder="http://" />
						<a href="#" class="button-secondary <?php echo esc_attr( $this->get_field_id( $key ) ); ?>-add" onclick="imageWidget.uploader( '<?php echo esc_js( $this->id ); ?>', '<?php echo esc_js( $id_prefix ); ?>', '<?php echo esc_js( $key ); ?>' ); return false;"><?php esc_html_e( 'Choose Image', 'forest-assistant' ); ?></a>
						<a href="#" style="display:inline-block;margin:5px 0 0 3px;<?php if ( empty( $value ) ) echo 'display:none;'; ?>" id="<?php echo esc_attr( $id_prefix ); ?>remove" class="button-link-delete" onclick="imageWidget.remove( '<?php echo esc_js( $this->id ); ?>', '<?php echo esc_js( $id_prefix ); ?>', '<?php echo esc_js( $key ); ?>' ); return false;"><?php esc_html_e( 'Remove', 'forest-assistant' ); ?></a>
					</p>
				<?php
					break;

				case 'checkbox':
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>">
							<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="1" <?php checked( 1, esc_attr( $value ) ); ?>/>
							<?php echo esc_html( $setting['label'] ); ?>
						</label>
					</p>
					<?php
					break;

				case 'multicheck':
					$value = maybe_unserialize( $value );

					if ( ! is_array( $value ) ) {
						$value = array();
					}
					?>
					<p><?php echo esc_attr( $setting['label'] ); ?></p>
					<p>
						<?php foreach ( $setting['options'] as $id => $label ) : ?>
						<label for="<?php echo esc_attr( sanitize_title( $label ) ); ?>-<?php echo esc_attr( $id ); ?>">
							<input type="checkbox" id="<?php echo esc_attr( sanitize_title( $label ) ); ?>-<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>[]" value="<?php echo esc_attr( $id ); ?>" <?php if ( in_array( $id, $value, true ) ) echo 'checked="checked"'; ?>  />
							<?php echo esc_attr( $label ); ?><br />
						</label>
						<?php endforeach; ?>
					</p>
					<?php
					break;

				case 'select':
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>">
							<?php foreach ( $setting['options'] as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $value ); ?>><?php echo esc_attr( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<?php
					break;

				case 'page':
					$exclude_ids = implode( ',', array( get_option( 'page_for_posts' ), get_option( 'page_on_front' ) ) );
					$pages       = get_pages( 'sort_order=ASC&sort_column=post_title&post_status=publish&exclude=' . $exclude_ids );
					?>
					<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>">
						<?php foreach ( $pages as $page ) : ?>
							<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $page->ID, $value ); ?>><?php echo esc_attr( $page->post_title ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php
					break;

				case 'number':
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number" step="<?php echo esc_attr( $setting['step'] ); ?>" min="<?php echo esc_attr( $setting['min'] ); ?>" max="<?php echo esc_attr( $setting['max'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
					</p>
					<?php
					break;

				case 'textarea':
					?>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						<textarea
							class="widefat"
							id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
							rows="<?php echo isset( $setting['rows'] ) ? $setting['rows'] : 3; // WPCS: XSS ok. ?>"><?php echo esc_html( $value ); ?></textarea>
					</p>
					<?php
					break;

				case 'colorpicker':
						wp_enqueue_script( 'wp-color-picker' );
						wp_enqueue_style( 'wp-color-picker' );
						wp_enqueue_style( 'underscore' );
					?>
						<p style="margin-bottom: 0;">
							<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
						</p>
						<input type="text" class="widefat color-picker" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" data-default-color="<?php echo esc_attr( $setting['std'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
						<script>
							( function( $ ) {
								function initColorPicker( widget ) {
									widget.find( '.color-picker' ).wpColorPicker({
										change: _.throttle( function() { // For Customizer
											$( this ).trigger( 'change' );
										}, 3000 )
									});
								}

								function onFormUpdate( event, widget ) {
									initColorPicker( widget );
								}

								$( document ).on( 'widget-added widget-updated', onFormUpdate );

								$( document ).ready( function() {
									$( '#widgets-right .widget:has(.color-picker)' ).each( function() {
										initColorPicker( $( this ) );
									});
								});
							}( jQuery ) );
						</script>
						<p></p>
					<?php
					break;

				case 'category':
					?>
					<p>
					<?php
					$categories_dropdown = wp_dropdown_categories( array(
						'name'             => $this->get_field_name( $key ),
						'selected'         => $value,
						'show_option_all'  => esc_html__( 'All Categories', 'forest-assistant' ),
						'show_option_none' => esc_html__( 'No Categories', 'forest-assistant' ),
						'show_count'       => true,
						'orderby'          => 'slug',
						'hierarchical'     => true,
						'class'            => 'widefat',
						'echo'             => false,
					) );
					?>

					<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					<?php echo $categories_dropdown; // WPCS: XSS ok.  ?>
					</p>

					<?php
					break;

				case 'tags':
					?>
					<p>
					<?php
					$tags_dropdown = wp_dropdown_categories( array(
						'name'             => $this->get_field_name( $key ),
						'selected'         => $value,
						'show_option_all'  => esc_html__( 'All Tags', 'forest-assistant' ),
						'show_option_none' => esc_html__( 'No Tags', 'forest-assistant' ),
						'show_count'       => true,
						'orderby'          => 'slug',
						'hierarchical'     => true,
						'class'            => 'widefat',
						'echo'             => false,
						'taxonomy'         => 'post_tag',
					) );
					?>

					<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					<?php echo $tags_dropdown; // WPCS: XSS ok.  ?>
					</p>

					<?php
					break;

				default:
					do_action( 'forest_widget_type_' . $setting['type'], $this, $key, $setting, $instance );
					break;
			} // End switch().
		} // End foreach().
	}

	/**
	 * Widget Areas Notice HTML
	 *
	 * @since 1.0.0
	 */
	public function widget_areas_notice() {
		if ( $this->widget_notice && current_user_can( 'edit_theme_options' ) ) {
			return '<div class="widget-areas-notice ' . esc_attr( $this->widget_id ) . '">' . wpautop( $this->widget_notice ) . '</div>';
		}

		return false;
	}

	/**
	 * Widget Screen Notice CSS
	 *
	 * @since 1.0.0
	 */
	public function widget_screen_notice_css() {
		global $hook_suffix, $_widget_notice_css;

		// This hook is loaded multiple times in widget.php, limit only once.
		$_widget_notice_css = is_array( $_widget_notice_css ) ? $_widget_notice_css : array();

		// Only load if needed.
		if ( 'widgets.php' === $hook_suffix && ! isset( $_widget_notice_css[ $this->widget_id ] ) && $this->widget_areas && $this->widget_notice ) {
			$_widget_notice_css[ $this->widget_id ] = $this->widget_id; // Add in global as identifier.
?>

<style id="widget-areas-notice-<?php echo esc_attr( $this->widget_id ); ?>" type="text/css">
	.widget-areas-notice.<?php echo esc_attr( $this->widget_id ); ?>{
		display: block;
		color: #72777c;
	}

<?php
foreach ( $this->widget_areas as $sidebar_id ) {
	if ( 'widget-area-page' === $sidebar_id ) {
		printf( 'div[id^="%s"] .widget-areas-notice.%s { display: none; }', esc_attr( $sidebar_id ), esc_attr( $this->widget_id ) );
	} else {
		printf( '#%s .widget-areas-notice.%s { display: none; }', esc_attr( $sidebar_id ), esc_attr( $this->widget_id ) );
	}
}
?>

</style>

<?php
		}
	}

	/**
	 * Widget Customizer Notice CSS
	 *
	 * @since 1.0.0
	 */
	public function widget_customizer_notice_css() {
		global $_widget_notice_css;

		/* This hook is loaded multiple times in widget.php, limit only once. */
		$_widget_notice_css = is_array( $_widget_notice_css ) ? $_widget_notice_css : array();

		if ( ! isset( $_widget_notice_css[ $this->widget_id ] ) && $this->widget_areas && $this->widget_notice ) {
			$_widget_notice_css[ $this->widget_id ] = $this->widget_id; // Add in global as identifier.
?>

<style id="widget-areas-notice-<?php echo esc_attr( $this->widget_id ); ?>" type="text/css">
	.widget-areas-notice.<?php echo esc_attr( $this->widget_id ); ?>{
		display: block;
		color: gray;
	}

<?php
foreach ( $this->widget_areas as $sidebar_id ) {
	if ( 'widget-area-page' === $sidebar_id ) {
		printf( 'div[id^="sub-accordion-section-sidebar-widgets-%s"] .widget-areas-notice.%s { display: none; }', esc_attr( $sidebar_id ), esc_attr( $this->widget_id ) );
	} else {
		printf( '#sub-accordion-section-sidebar-widgets-%s .widget-areas-notice.%s { display: none; }', esc_attr( $sidebar_id ), esc_attr( $this->widget_id ) );
	}
}
?>
</style>

<?php
		}
	}

	/**
	 * Display the widget.
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {}
}
