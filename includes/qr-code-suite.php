<?php
/**
PHP version 5
**/

require_once 'phpqrcode.php';
if ( !class_exists( 'QrCodeWidget' ) ) {
	class QrCodeWidget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'qrcode_widget',
				'Qr Code Widget',
				array( 'description' => __( 'A Widget that display QR code\'s', 'qr-code-widget' ),)
			);

		}
		
		public function widget( $args, $instance )
		{
			$allowed_tags     = wp_kses_allowed_html( 'post' );
			extract( $args );
			$title            = apply_filters( 'widget_title', $instance[ 'title' ] );
			$qr_code_bg       = ( $instance[ 'qr_code_bg' ] == '' ? '#ffffff' : $instance[ 'qr_code_bg' ] );
			$qr_code_fg       = ( $instance[ 'qr_code_fg' ] == '' ? '#000000' : $instance[ 'qr_code_fg' ] );
			$qr_code_trans_bg = ( $instance[ 'qr_code_trans_bg' ] == 1 ? 1 : 0 );
			$qr_code_format   = ( $instance[ 'qr_code_format' ] == 'jpg' ? 'jpg' : 'png' );
			$qr_code_ecc      = ( $instance[ 'qr_code_ecc' ] == '' ? 'M' : $instance[ 'qr_code_ecc' ] );
			$qr_code_size     = ( $instance[ 'qr_code_size' ] == '' ? 2 : $instance[ 'qr_code_size' ] );
			$pre_code         = wp_kses( $instance[ 'pre_code' ], $allowed_tags );
			$post_code        = wp_kses( $instance[ 'post_code' ], $allowed_tags );
			$no_cache         = ( $instance[ 'no_cache' ] == 1 ? 1 : 0 );
			$qr_code_data     = wp_kses( $instance[ 'qr_code_data' ], $allowed_tags );
			if ( is_string($before_widget)){
				echo $before_widget;
			}
			if ( $title ) {
				if (is_string($before_title))
					echo $before_title;

				echo $title;

				if (is_string($after_title))
					echo $after_title;
			}

			echo wp_kses( $pre_code, $allowed_tags );

			$protocol = (is_ssl() ? 'https://' : 'http://');
			$data = trim($protocol . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ]);

			if ( is_home() ) {
				$data = get_bloginfo( 'url' );
			}

			if ( $qr_code_data != '' ) {
				$data = $qr_code_data;
			} 
			
			echo qr_prepare_image(
				$data, $qr_code_ecc,
				$qr_code_format,
				$qr_code_size,
				$qr_code_trans_bg,
				$qr_code_bg,
				$qr_code_fg,
				$no_cache
			);
			echo wp_kses( $post_code, $allowed_tags );
			if (is_string($after_widget))
				echo $after_widget;

		}

		public function form( $instance )
		{
			$defaults = get_option( 'qr_code_wg' );
			$instance = wp_parse_args( (array)$instance, $defaults );
			$selected = 'selected=\'selected\'';
			$checked  = 'checked=\'checked\'';
			//$allowed_tags = wp_kses_allowed_html( 'post' );
			$qr_code_data = "";
			if ( isset( $instance[ 'qr_code_data' ] ) ){
				$qr_code_data = $instance[ 'qr_code_data' ];
			};
			?>
			<script type='text/javascript' language='javascript'>
				jQuery.noConflict();
				jQuery(document).ready(function ($) {
					$('#<?php echo $this->get_field_id('qr_code_bg'); ?>, #<?php echo $this->get_field_id('qr_code_fg'); ?>').ColorPicker({
						onSubmit: function (hsb, hex, rgb, el) {
							$(el).val(hex);
							$(el).ColorPickerHide();
						},
						onBeforeShow: function () {
							$(this).ColorPickerSetColor(this.value);
						}
					})
						.bind('keyup', function () {
							$(this).ColorPickerSetColor(this.value);
						});
				});
			</script>
			<p>
				<label for='<?php echo $this->get_field_id( 'title' ); ?>'><?php _e( 'Title', 'qr-code-widget' ); ?>:</label>
				<input id='<?php echo $this->get_field_id( 'title' ); ?>' name='<?php echo $this->get_field_name( 'title' ); ?>' value='<?php echo $instance[ 'title' ]; ?>' style='width: 100%;'/>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'qr_code_format' ); ?>'><?php _e( 'QR code format', 'qr-code-widget' ); ?>:</label>
				<input type='radio' <?php echo( $instance[ 'qr_code_format' ] == 'png' ? $checked : '' ) ?> id='<?php echo $this->get_field_id( 'qr_code_format' ); ?>' name='<?php echo $this->get_field_name( 'qr_code_format' ); ?>' value='png'> <?php _e( 'PNG', 'qr-code-widget' ); ?>
				<input type='radio' <?php echo( $instance[ 'qr_code_format' ] == 'jpg' ? $checked : '' ) ?> id='<?php echo $this->get_field_id( 'qr_code_format' ); ?>' name='<?php echo $this->get_field_name( 'qr_code_format' ); ?>' value='jpg'> <?php _e( 'Jpeg', 'qr-code-widget' ); ?>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'qr_code_trans_bg' ); ?>'> <?php _e( 'Transparent background', 'qr-code-widget' ); ?> <?php _e( '(png only)', 'qr-code-widget' ) ?>:</label>
				<input type='radio' <?php echo( $instance[ 'qr_code_trans_bg' ] == '0' ? $checked : '' ) ?> name='<?php echo $this->get_field_name( 'qr_code_trans_bg' ); ?>' value='0'>form <?php _e( 'No', 'qr-code-widget' ); ?>
				<input type='radio' <?php echo( $instance[ 'qr_code_trans_bg' ] == '1' ? $checked : '' ) ?> name='<?php echo $this->get_field_name( 'qr_code_trans_bg' ); ?>' value='1'> <?php _e( 'Yes', 'qr-code-widget' ); ?>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'qr_code_bg' ); ?>'> <?php _e( 'Background Color', 'qr-code-widget' ); ?>:</label> #
				<input id='<?php echo $this->get_field_id( 'qr_code_bg' ); ?>' name='<?php echo $this->get_field_name( 'qr_code_bg' ); ?>' value='<?php echo $instance[ 'qr_code_bg' ]; ?>' class='<?php echo $this->get_field_id( 'qr_code_bg' ); ?>' size='7' maxlength='7'/>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'qr_code_fg' ); ?>'> <?php _e( 'Code Color:', 'qr-code-widget' ); ?></label>
				#
				<input id='<?php echo $this->get_field_id( 'qr_code_fg' ); ?>' name='<?php echo $this->get_field_name( 'qr_code_fg' ); ?>' value='<?php echo $instance[ 'qr_code_fg' ]; ?>' class='<?php echo $this->get_field_id( 'qr_code_fg' ); ?>' size='7' maxlength='7'/>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'qr_code_ecc' ); ?>'> <?php _e( 'Level of error correction', 'qr-code-widget' ); ?> :</label>
				<select id='<?php echo $this->get_field_id( 'qr_code_ecc' ); ?>' name='<?php echo $this->get_field_name( 'qr_code_ecc' ); ?>' class='widefat'>
					<option value='0' <?php echo( $instance[ 'qr_code_ecc' ] == 0 ? $selected : '' ); ?>>L</option>
					<option value='1' <?php echo( $instance[ 'qr_code_ecc' ] == 1 ? $selected : '' ); ?>>M</option>
					<option value='2' <?php echo( $instance[ 'qr_code_ecc' ] == 2 ? $selected : '' ); ?>>Q</option>
					<option value='3' <?php echo( $instance[ 'qr_code_ecc' ] == 3 ? $selected : '' ); ?>>H</option>
				</select>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'qr_code_size' ); ?>'><?php _e( 'QR code size', 'qr-code-widget' ); ?>:</label>
				<select id='<?php echo $this->get_field_id( 'qr_code_size' ); ?>' name='<?php echo $this->get_field_name( 'qr_code_size' ); ?>' class='widefat'>
					<option value='2' <?php echo( $instance[ 'qr_code_size' ] == 2 ? $selected : '' ); ?>><?php _e( 'Small', 'qr-code-widget' ); ?></option>
					<option value='4' <?php echo( $instance[ 'qr_code_size' ] == 4 ? $selected : '' ); ?>><?php _e( 'Medium', 'qr-code-widget' ); ?></option>
					<option value='6' <?php echo( $instance[ 'qr_code_size' ] == 6 ? $selected : '' ); ?>><?php _e( 'Large', 'qr-code-widget' ); ?></option>
					<option value='8' <?php echo( $instance[ 'qr_code_size' ] == 8 ? $selected : '' ); ?>><?php _e( 'XXL', 'qr-code-widget' ); ?></option>
				</select>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'pre_code' ); ?>'><?php _e( 'Code to display before Widget', 'qr-code-widget' ); ?>:</label>
				<textarea class='widefat' rows='6' cols='20' id='<?php echo $this->get_field_id( 'pre_code' ); ?>' name='<?php echo $this->get_field_name( 'pre_code' ); ?>'><?php echo stripslashes( $instance[ 'pre_code' ] ); ?></textarea>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'post_code' ); ?>'><?php _e( 'Code to display after Widget', 'qr-code-widget' ); ?>:</label>
				<textarea class='widefat' rows='6' cols='20' id='<?php echo $this->get_field_id( 'post_code' ); ?>' name='<?php echo $this->get_field_name( 'post_code' ); ?>'><?php echo stripslashes( $instance[ 'post_code' ] ); ?></textarea>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'qr_code_data' ); ?>'><?php _e( 'Alternative text for this QR code', 'qr-code-widget' ); ?>:</label>
				<textarea class='widefat' rows='6' cols='20' id='<?php echo $this->get_field_id( 'qr_code_data' ); ?>' name='<?php echo $this->get_field_name( 'qr_code_data' ); ?>'><?php echo stripslashes( $qr_code_data ); ?></textarea>
			</p>
			<p>
				<label for='<?php echo $this->get_field_id( 'no_cache' ); ?>'><?php _e( 'Disable Cache', 'qr-code-widget' ); ?>:</label>
				<input type='radio' <?php echo( $instance[ 'no_cache' ] == '0' ? $checked : '' ) ?> id='<?php echo $this->get_field_name( 'no_cache' ); ?>' name='<?php echo $this->get_field_name( 'no_cache' ); ?>' value='0'> <?php _e( 'NO', 'qr-code-widget' ); ?>
				<input type='radio' <?php echo( $instance[ 'no_cache' ] == '1' ? $checked : '' ) ?> id='<?php echo $this->get_field_name( 'no_cache' ); ?>' name='<?php echo $this->get_field_name( 'no_cache' ); ?>' value='1'> <?php _e( 'Yes', 'qr-code-widget' ); ?>
			</p>
		<?php
		}

		public function update( $new_instance, $old_instance )
		{
			$instance = $old_instance;
			$instance[ 'title' ] = $new_instance[ 'title' ];
			$instance[ 'qr_code_bg' ] = strip_tags( $new_instance[ 'qr_code_bg' ] );
			$instance[ 'qr_code_fg' ] = strip_tags( $new_instance[ 'qr_code_fg' ] );
			$instance[ 'qr_code_trans_bg' ] = $new_instance[ 'qr_code_trans_bg' ];
			$instance[ 'qr_code_format' ] = $new_instance[ 'qr_code_format' ];
			$instance[ 'qr_code_ecc' ] = $new_instance[ 'qr_code_ecc' ];
			$instance[ 'qr_code_size' ] = $new_instance[ 'qr_code_size' ];
			$instance[ 'pre_code' ] = $new_instance[ 'pre_code' ];
			$instance[ 'post_code' ] = $new_instance[ 'post_code' ];
			$instance[ 'qr_code_data' ] = $new_instance[ 'qr_code_data' ];
			$instance[ 'no_cache' ] = $new_instance[ 'no_cache' ];
			return $instance;
		}
	}

	function qr_generate_image(
		$text, $file_ident,
		$format = 'png',
		$bg_color = '#ffffff',
		$fg_color = '#000000',
		$transparent_bg = 0,
		$level = QR_ECLEVEL_M,
		$size = 2, $margin = 4, $quality = 85
	)
	{
		$enc     = QRencode::factory( $level, $size, $margin );
		$data    = $enc->encode( $text );
		$maxSize = (int)( QR_PNG_MAXIMUM_SIZE / ( count( $data ) + 2 * $margin ) );
		$img     = QRimage::image( $data, min( max( 1, $size ), $maxSize ), 4 );

		$fg_index = imagecolorclosest( $img, 0, 0, 0 );
		$bg_index = imagecolorclosest( $img, 255, 255, 255 );

		$bg = html2rgb( $bg_color );
		$fg = html2rgb( $fg_color );

		imagecolorset( $img, $bg_index, $bg[ 0 ], $bg[ 1 ], $bg[ 2 ] );
		imagecolorset( $img, $fg_index, $fg[ 0 ], $fg[ 1 ], $fg[ 2 ] );

		$fs=($file_ident ? $file_ident . '.' . $format : false);

		switch ($format) {
			case 'jpg':
				imagejpeg( $img, $fs, $quality );
				break;
			default:
				$quality = ( $quality > 9 ? 1 : $quality );
				if ($transparent_bg == 1){
					$quality = ( $quality > 9 ? 0 : $quality );
					imagecolortransparent( $img, $bg_index );
				}
				imagepng( $img, $fs, $quality );
				break;
		}
	}

	function qr_prepare_image(
		$content, $qr_code_ecc = '1',
		$qr_code_format = 'png',
		$qr_code_size = '3',
		$qr_code_trans_bg = '0',
		$qr_code_bg = 'ffffff',
		$qr_code_fg = '000000',
		$no_cache = 0
	)
	{
		$params = array(
			'd' => $content,
			'e' => $qr_code_ecc,
			't' => $qr_code_format,
			's' => $qr_code_size,
			'trans' => $qr_code_trans_bg,
			'bg' => $qr_code_bg,
			'fg' => $qr_code_fg,
		);
		$cache_id = md5( serialize( $params ) );
		$file_identifier = QCW_IMAGE_CACHE . $cache_id;
		$cached_file_url = QCW_URLPATH . 'cache/' . $cache_id . '.' . $qr_code_format;

		switch ($no_cache) {
			case 1:
				$img_tag = '<img src="' . 
				no_cached_image($content, $qr_code_format,$qr_code_bg, $qr_code_fg, $qr_code_trans_bg, $qr_code_ecc, $qr_code_size) . 
				'" alt="qr_code" />';
				break;
			
			default:
				if ( is_readable( $file_identifier . '.' . $qr_code_format )){
					$img_tag = '<img src="' . $cached_file_url . '" alt="qr code" />';
					break;
				}

				if ( !is_writeable( QCW_IMAGE_CACHE )) {
					$coded_image = no_cached_image($content, $qr_code_format,$qr_code_bg, $qr_code_fg, $qr_code_trans_bg, $qr_code_ecc, $qr_code_size);
					$img_tag = '<img src="' . $coded_image . '" alt="qr_code" />';
					break;
				}

				qr_generate_image(
					$content, $file_identifier,
					$qr_code_format, $qr_code_bg,
					$qr_code_fg, $qr_code_trans_bg,
					$qr_code_ecc, $qr_code_size
				);
				$img_tag = '<img src=' . $cached_file_url . ' alt=\'qr code\' />';
				break;
		}
		return $img_tag;
	}

	function no_cached_image(
		$content, $qr_code_format,	$qr_code_bg, 
		$qr_code_fg, $qr_code_trans_bg, $qr_code_ecc, $qr_code_size
		){
		ob_start();
		qr_generate_image(
			$content, false,
			$qr_code_format,
			$qr_code_bg, $qr_code_fg,
			$qr_code_trans_bg, $qr_code_ecc,
			$qr_code_size
		);
		$image_data = ob_get_contents();
		ob_end_clean();
		$coded_image = 'data:image/'
					. ( $qr_code_format == 'jpg' ? 'jpeg' : 'png' )
					. ';base64,' . chunk_split( base64_encode( $image_data ) );
		return $coded_image;
	}

	function qr_shortcode( $instance )
	{
		global $wp_query;
		$defaults = get_option( 'qr_code_sc' );
		$instance = wp_parse_args( (array)$instance, $defaults );
		$qr_code_bg = ( $instance[ 'qr_code_bg' ] == '' ? '#ffffff' : $instance[ 'qr_code_bg' ] );
		$qr_code_fg = ( $instance[ 'qr_code_fg' ] == '' ? '#000000' : $instance[ 'qr_code_fg' ] );
		$qr_code_trans_bg = ( $instance[ 'qr_code_trans_bg' ] == 1 ? 1 : 0 );
		$qr_code_format = ( $instance[ 'qr_code_format' ] == 'jpg' ? 'jpg' : 'png' );
		$qr_code_ecc = ( $instance[ 'qr_code_ecc' ] == '' ? 'M' : $instance[ 'qr_code_ecc' ] );
		$qr_code_size = ( $instance[ 'qr_code_size' ] == '' ? 2 : $instance[ 'qr_code_size' ] );
		$no_cache = ( $instance[ 'no_cache' ] == 1 ? 1 : 0 );
		//$cache_days = ( $instance[ 'cache_days' ] == 0 ? 0 : 7 );
		$qr_code_data = ( isset( $instance[ 'qr_text' ] ) ? $instance[ 'qr_text' ] : false );


		$protocol = (is_ssl() ? 'https://' : 'http://');
		$data = trim($protocol . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ]);

		if ( is_home() ) {
			$data = get_bloginfo( 'url' );
		}

		if ( !empty( $wp_query->post ) ) {
			$data = get_permalink( $wp_query->post->ID);
		}

		if ( $qr_code_data != '' ) {
			$data = $qr_code_data;
		} 
		
		return qr_prepare_image(
			$data, $qr_code_ecc,
			$qr_code_format,
			$qr_code_size,
			$qr_code_trans_bg, $qr_code_bg,
			$qr_code_fg, $no_cache
		);
	}

	/**
	 * Convert html color code to rgb array
	 * @param string $color html color code
	 * @return array rgb code array
	 */
	function html2rgb( $color )
	{
		if ( $color[ 0 ] == '#' ) {
			$color = substr( $color, 1 );
		}

		$clen = strlen( $color );
		switch ($clen) {
			case 6:
				list( $r, $g, $b ) = array( 
					$color[ 0 ] . $color[ 1 ],
					$color[ 2 ] . $color[ 3 ],
					$color[ 4 ] . $color[ 5 ] );
				break;
			case 3:
				list( $r, $g, $b ) = array(
					$color[ 0 ] . $color[ 0 ],
					$color[ 1 ] . $color[ 1 ],
					$color[ 2 ] . $color[ 2 ]
				);
				break;

			default:
				return false;
				break;
		}

		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );
		
		return array( $r, $g, $b );
	}
}


function widget_QrCodeWidget_init()
{
	register_widget( 'QrCodeWidget' );
}

?>
