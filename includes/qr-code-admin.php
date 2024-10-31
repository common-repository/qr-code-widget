<?php
/**
PHP version 5
$defaults_wg = array(
	'title' => 'QR Code',
	'qr_code_bg' => 'ffffff',
	'qr_code_fg' => '000000',
	'qr_code_trans_bg' => '0',
	'qr_code_format' => $format,
	'qr_code_ecc' => '1',
	'qr_code_size' => $size,
	'pre_code' => '<div>',
	'no_cache' => '0',
	'post_code' => '</div><p style="padding:0;margin:0;font-size:.8em;">QR code created by<a href="http://www.poluschin.info/">QR code Widget</a></p>',
	'version' => QCW_VERSION,
);

$defaults_sc = array(
	'qr_code_bg' => 'ffffff',
	'qr_code_fg' => '000000',
	'qr_code_trans_bg' => '0',
	'qr_code_format' => 'png',
	'qr_code_ecc' => '1',
	'qr_code_size' => '2',
	'no_cache' => '0',
	'version' => QCW_VERSION,
);

**/

function qr_admin_loader()
{
	wp_enqueue_style( 'qr-code-admin', QCW_URLPATH . '/css/admin.css'	);
	wp_enqueue_style( 'color-picker', QCW_URLPATH . 'colorpicker/css/colorpicker.css' );
	wp_enqueue_script( 'color-picker-qr', QCW_URLPATH . 'colorpicker/js/colorpicker.js', array( 'jquery' ) );
}

function qrcode_dashboard()
{
	get_currentuserinfo();
	add_object_page( 'QrCode', 'QrCode', 'manage_options', 'qrcode', 'qrcode_manage' );
	add_submenu_page( 'qrcode', __( 'QrCode > Shortcode settings', 'qr-code-widget' ), __( 'Shortcode Defaults', 'qr-code-widget' ), 'manage_options', 'qrcode_sc_menu', 'qrcode_sc_manage'	);
	add_submenu_page( 'qrcode', __( 'QrCode > Widget settings', 'qr-code-widget' ), __( 'Widget Defaults', 'qr-code-widget' ), 'manage_options', 'qrcode_wg_menu', 'qrcode_wg_manage' );
}

function qrcode_manage()
{ ?>
	<h2><?php _e( 'QR code', 'qr-code-widget' ); ?></h2>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="postbox-container-1" class="postbox-container" style="width:50%;">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox-qr-code">
						<h3><?php _e( 'QR code status report', 'qr-code-widget' ); ?></h3>
						<div class="inside">
							<div class="versions"> 
								<span id="qr-code-version-message">
									<?php printf( __( 'You are using <strong>QR-Code-Widget %s</strong>', 'qr-code-widget' ), QCW_VERSION );?>
								</span>
							</div>
	<?php
	if ( is_writeable( QCW_IMAGE_CACHE ) ) { ?>
								<div class="info">
									<p><?php _e( 'Cache directory is writable', 'qr-code-widget' ); ?> </p>
									<?php $img_count = ( glob( QCW_IMAGE_CACHE . '*.*' ) ? count( glob( QCW_IMAGE_CACHE . '*.*' ) ) : 0 ); ?>
									<p>
									<?php printf( __( 'You have %d images in cache', 'qr-code-widget' ), $img_count ); ?>
									</p>
									<form name='clear_cache' id='clear_cache' method='post' action=''>
										<?php wp_nonce_field( 'qrcode' ) ?>
										<input type='hidden' name='clear_cache' value='1'>
										<input
										onclick="return confirm('<?php _e( 'You are about to delete all images from Cache. This action can not be undone! OK to continue, Cancel to stop.', 'qr-code-widget' ); ?>')"
										type='submit' id='post-action-submit'
										name='qrcode_action_submit'
										value='<?php _e( 'Clear Cache', 'qr-code-widget' ); ?>'
										class='button-primary'>
										<?php add_thickbox(); ?>
										<a href="<?php echo esc_url( admin_url( 'admin-ajax.php' ) );?>?action=show_qr_cache&width=900" class="thickbox">
											<button class="button-secondary" id="show_cache" name="show_qr_cache" >
												<strong><?php _e( 'Show content of Cache directory','qr-code-widget' ); ?></strong>
											</button>
										</a>
									</form>
								</div>
	<?php 
	} else { ?>
									<div class="error">
										<p><?php printf( __( 'The intermediate storage of images is disabled. Images are re-created every time and embedded in the HTML code. (CPU-heavy!)<br /> In order to save the CPU resources create the cache folder %s and make sure that the web server can write to it.', 'qr-code-widget' ), QCW_IMAGE_CACHE );?></p>
									</div>
									<form name='create_cache' id='post' method='post' action=''>
										<?php
										wp_nonce_field( 'qrcode' )
										?>
										<input type='hidden' name='create_cache' value='1'/>
										<input type='submit' id='post-action-submit' name='qrcode_action_submit'
										       value='<?php _e( 'Try to create Cache', 'qr-code-widget' ); ?>'
										       class='button-primary'/>
									</form>
									</div>
	<?php 
	} ?>
						</div>	
					</div>
				</div>
			</div>
			<div id="postbox-container-3" class="postbox-container" style="width:50%;">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox-qr-code">
						<h3><?php _e( 'Qr-Code-Widget Promotions', 'qr-code-widget' ); ?></h3>
						<div class="inside">
							<strong><?php _e( 'Your support makes a difference', 'qr-code-widget' ); ?></strong>
							<p><?php _e( 'Your awesome gift will ensure the continued development of this Plugin! Thanks for your consideration!', 'qr-code-widget' ); ?></p>
							<a href="http://www.poluschin.info/donate/" target="_blank"><img src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" /></a>
						</div>
					</div>
					<div class="postbox-qr-code">
						<h3><?php _e( 'Useful links', 'qr-code-widget' ); ?></h3>
						<div class="inside">
							<ul>
								<li><?php _e( 'Like the plugin?', 'qr-code-widget' );?> <a href="http://wordpress.org/support/view/plugin-reviews/qr-code-widget?rate=5#postform" target="_blank"><?php _e( 'Rate and review', 'qr-code-widget' );?></a> QR Code Widget.</li>
								<li><?php _e( 'Find my website at', 'qr-code-widget' );?> <a href="http://www.poluschin.info" target="_blank">www.poluschin.info</a>.</li>
								<li><?php _e( 'Found a issue? Submit it at', 'qr-code-widget' );?> <a href="http://scm.poluschin.info/projects/qrcodewidget" target="_blank">scm.poluschin.info</a>.</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php 
}

function qrcode_sc_manage()
{
	$selected = 'selected=selected';
	$checked  = 'checked=checked';
	$option   = get_option( 'qr_code_sc' ); 
	?>
	<div class='wrap'>
	<h2><?php _e( 'QR code default settings for short-codes', 'qr-code-widget' );?></h2>
	<?php qcw_credits(); ?>
	<script type='text/javascript' language='javascript'>
		jQuery.noConflict();
		jQuery(document).ready(function ($) {
			$('#qr_bg, #qr_fg').ColorPicker({
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
	<form name='qrcode_options' id='qrcode_options' method='post' action=''>
		<?php wp_nonce_field( 'QCW_settings' ) ?>
		<input type='hidden' name='qrcode_options_submit' value='qr_code_sc'>
		<table class='form-table'>
			<tr>
				<td colspan='2'><h3>
						<?php _e( 'QR code settings', 'qr-code-widget' ); ?>
					</h3>
                    <span class='description'>
                        <?php _e( 'You can define default values for your parameters by using the <strong>Shortcode Defaults</strong> on the QR code page.', 'qr-code-widget' ); ?>
	                    <br>
	                    <?php _e( 'Default values will be used instead of missed/unset parameters on your short codes.', 'qr-code-widget' ); ?>
	                    <br>
	                    <?php _e( 'For example: ', 'qr-code-widget' ); ?>
	                    [qr_code_display qr_code_fg=FF0000]
	                    <?php _e( 'will be expand to ', 'qr-code-widget' ); ?>
	                    [qr_code_display qr_code_format=png qr_code_size=2 qr_code_ecc=1 qr_code_trans_bg=0 qr_code_bg=ffffff qr_code_fg=FF0000 qr_text=http://yoursite no_cache=0 ].<br/>
                        <br>
	                    <?php _e( 'All Shortcode variables and parameters:', 'qr-code-widget' ); ?>
	                    <br>
                        [qr_code_display qr_code_format='png|jpg' qr_code_size='1-10' qr_code_ecc='0-3' qr_code_trans_bg='0|1' qr_code_bg='ffffff' qr_code_fg='000000' qr_text='string' no_cache='0|1']<br/>
	                    <?php _e( 'Empty or missing qr_text will be replaced by site url.', 'qr-code-widget' ); ?>
                    </span>
				</td>
			</tr>
			<tr>
				<th><label for='qr_code_format'><?php _e( 'QR code format', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<input type='radio' id='qr_code_format' <?php echo esc_js( ( $option[ 'qr_code_format' ] == 'png' ? $checked : '' ) )?> name='qr_code_format' value='png'> <?php _e( 'PNG', 'qr-code-widget' ); ?>
					<input type='radio' id='qr_code_format' <?php echo esc_js( ( $option[ 'qr_code_format' ] == 'jpg' ? $checked : '' ) )?> name='qr_code_format' value='jpg'> <?php _e( 'Jpeg', 'qr-code-widget' ); ?>
					<span class='description'>* <?php _e( 'Image format PNG or Jpeg.', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for='qr_code_size'><?php _e( 'QR code size', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<select id='qr_code_size' name='qr_code_size'>
						<option value='2' <?php echo esc_js( ( $option[ 'qr_code_size' ] == 2 ? $selected : '' ) ); ?>><?php _e( 'Small', 'qr-code-widget' ); ?></option>
						<option value='4' <?php echo esc_js( ( $option[ 'qr_code_size' ] == 4 ? $selected : '' ) ); ?>><?php _e( 'Medium', 'qr-code-widget' ); ?></option>
						<option value='6' <?php echo esc_js( ( $option[ 'qr_code_size' ] == 6 ? $selected : '' ) ); ?>><?php _e( 'Large', 'qr-code-widget' ); ?></option>
						<option value='8' <?php echo esc_js( ( $option[ 'qr_code_size' ] == 8 ? $selected : '' ) ); ?>><?php _e( 'XXL', 'qr-code-widget' ); ?></option>
					</select>
					<span class='description'>* <?php _e( 'Size of QR code image', 'qr-code-widget' );?></span>
				</td>
			</tr>
			<tr>
				<th><label for='qr_code_ecc'><?php _e( 'Level of error correction', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<select id='qr_code_ecc' name='qr_code_ecc'>
						<option value='0' <?php echo esc_js( ( $option[ 'qr_code_ecc' ] == 0 ? $selected : '' ) ); ?>> L</option>
						<option	value='1' <?php echo esc_js( ( $option[ 'qr_code_ecc' ] == 1 ? $selected : '' ) ); ?>> M</option>
						<option value='2' <?php echo esc_js( ( $option[ 'qr_code_ecc' ] == 2 ? $selected : '' ) ); ?>> Q</option>
						<option value='3' <?php echo esc_js( ( $option[ 'qr_code_ecc' ] == 3 ? $selected : '' ) ); ?>> H</option>
					</select>
                    <span class='description'>
                        <?php _e( '<br />There are 4 ECC Levels in QR code as follows:<br />Level L - 7% of codewords can be restored <br />Level M - 15% of codewords can be restored <br />Level Q - 25% of codewords can be restored <br />Level H - 30% of codewords can be restored <br />', 'qr-code-widget' ); ?>
                    </span>
				</td>
			</tr>
			<tr>
				<th><label for='no_cache'><?php _e( 'Disable Cache', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<input type='radio' id='no_cache' <?php echo esc_js( ( $option[ 'no_cache' ] == '0' ? $checked : '' ) );?> name='no_cache' value='0'> <?php _e( 'No', 'qr-code-widget' ); ?>
					<input type='radio' id='no_cache' <?php echo esc_js( ( $option[ 'no_cache' ] == '1' ? $checked : '' ) );?> name='no_cache' value='1'> <?php _e( 'Yes', 'qr-code-widget' ); ?>
					<span class='description'>* <?php _e( 'Don\'t cache image. Images are re-created every time and embedded in the HTML code', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan='2'><h3><?php _e( 'Color settings', 'qr-code-widget' ); ?></h3></td>
			</tr>
			<tr>
				<th><label for='qr_fg'><?php _e( 'Code Color', 'qr-code-widget' ); ?>:</label></th>
				<td>
					#<input id='qr_fg' name='qr_code_fg' maxlength='7' size='7' value='<?php echo esc_js( $option[ 'qr_code_fg' ] );?>'/>
					<span class='description'>* <?php _e( 'Set Color for QR code', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for='qr_bg'><?php _e( 'Background Color', 'qr-code-widget' ) ?>:</label></th>
				<td>
					#<input id='qr_bg' name='qr_code_bg' maxlength='7' size='7' value='<?php echo esc_js( $option[ 'qr_code_bg' ] );?>'/>
                    <span class='description'>* <?php _e( 'Set background Color for QR code', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for='qr_code_trans_bg'><?php _e( 'Transparent background', 'qr-code-widget' ); ?>:</label>
				</th>
				<td>
					<input type='radio' id='qr_code_trans_bg' <?php echo esc_js( ( $option[ 'qr_code_trans_bg' ] == '0' ? $checked : '' ) );?> name='qr_code_trans_bg' value='0'> <?php _e( 'No', 'qr-code-widget' ); ?>
					<input type='radio' id='qr_code_trans_bg' <?php echo esc_js( ( $option[ 'qr_code_trans_bg' ] == '1' ? $checked : '' ) );?> name='qr_code_trans_bg' value='1'> <?php _e( 'Yes', 'qr-code-widget' ); ?>
					<span class='description'>* <?php _e( 'Set transparency for QR code background. <b>PNG Only</b>', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan='2'><input type='submit' class='button-primary' value='<?php _e( 'Save Changes', 'qr-code-widget' ) ?>'/></td>
			</tr>
		</table>
	</form>
	<br/>
	<h3><?php _e( 'Test Images', 'qr-code-widget' ); ?></h3>
	<table class='widefat' style='margin-top: .5em'>
		<thead>
		<tr>
			<th><?php _e( 'Shortcode', 'qr-code-widget' ) ?></th>
			<th><?php _e( 'Image', 'qr-code-widget' ); ?></th>
			<th><?php _e( 'HTML Source', 'qr-code-widget' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>[qr_code]</td>
			<td>
				<?php 
				$img_tag = qr_shortcode( array() );
				echo $img_tag;
				?>
			</td>
			<td>
				<?php 
				echo esc_html( $img_tag );
				if ( isset( $img ) ){
					unset( $img ); 
				}
				?>
			</td>
		</tr>
		<tr>
			<td>[qr_code qr_text='http://www.poluschin.info']</td>
			<td>
				<?php
				$img_tag = qr_shortcode( array( 'qr_text' => 'http://www.poluschin.info' ) );
				echo $img_tag ?>
			</td>
			<td>
				<?php echo esc_html( $img_tag );
				unset($img_tag); ?>
			</td>
		</tr>
		<tr>
			<td>[qr_code qr_text='http://www.poluschin.info' no_cache=1]</td>
			<td><?php
				$img_tag = qr_shortcode( array( 'qr_text' => 'http://www.poluschin.info', 'no_cache' => 1 ) );
				echo $img_tag;
				?>
			</td>
			<td>
				<?php echo esc_html( $img_tag );
				unset( $img_tag ) ?>
			</td>
		</tr>
		</tbody>
	</table>
 <?php }

function qrcode_wg_manage()
{
	$selected     = 'selected=selected';
	$checked      = 'checked=checked';
	$allowed_tags = wp_kses_allowed_html( 'post' );
	$option       = get_option( 'qr_code_wg' );

	echo '<div class=\'wrap\'>';?>
	<script type='text/javascript' language='javascript'>
		jQuery.noConflict();
		jQuery(document).ready(function ($) {
			$('#qr_bg, #qr_fg').ColorPicker({
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

		function resetWgDf(){
			if( confirm('<?php _e( 'You are about to owerride all exist Widget settings by Defaults. This action can not be undone! OK to continue, Cancel to stop.', 'qr-code-widget' ); ?>') ){
				document.getElementById( 'reset_wg' ).value=1;
				document.getElementById( 'post' ).submit();
			}
			return false;		
		}
	</script>
	<?php
	_e( '<h2>QR code default settings for Widgets</h2>', 'qr-code-widget' );
	qcw_credits(); ?>
	</br>
	<form name='qr_widget_settings' id='post' method='post' action=''>
		<button class="button-secondary" id="reset_wg_btn" name="reset_wg_btn" value="0" onclick="return resetWgDf()" ><strong><?php _e( 'Apply Default Settings to all Widgets','qr-code-widget' ); ?></strong></button>
		<br />
		<span class='description'><?php _e( 'Default settings for <b>new</b> Widgets.<br /><b>Changes have no effect to existing Widgets.</b>', 'qr-code-widget' ); ?></span>
		
		<?php
		wp_nonce_field( 'QCW_settings' )
		?>
		<input type='hidden' name='reset_wg' id='reset_wg' value='0'/>
		<input type='hidden' name='qrcode_options_submit' value='qr_code_wg'/>
		<table class='form-table'>
			<tr>
				<td colspan='2'><h3><?php _e( 'QR code Widget settings', 'qr-code-widget' ); ?></h3></td>
			</tr>
			<tr>
				<th><label for='title'><?php _e( 'Widget Title', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<input id='title' name='title' value='<?php echo wp_kses( $option[ 'title' ], $allowed_tags ) ?>' tabindex='1'/>
					<span class='description'>* <?php _e( 'Widget Title', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for='pre_code'><?php _e( 'Code to display before Widget', 'qr-code-widget' ); ?></label>:
				</th>
				<td>
					<textarea rows='15' cols='65' name='pre_code' id='pre_code' tabindex='2'><?php echo wp_kses( $option[ 'pre_code' ], $allowed_tags ); ?></textarea>
					<span class='description'>* <?php _e( 'Can be HTML', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for='post_code'><?php _e( 'Code to display after Widget', 'qr-code-widget' ); ?></label>:
				</th>
				<td>
					<textarea rows='15' cols='65' id='post_code' name='post_code' tabindex='2'><?php echo wp_kses( $option[ 'post_code' ], $allowed_tags  ); ?></textarea>
					<span class='description'>* <?php _e( 'Can be HTML', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan='2'><h3><?php _e( 'QR code settings', 'qr-code-widget' ); ?></h3></td>
			</tr>
			<tr>
				<th><label for='qr_code_format'><?php _e( 'QR code format', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<input type='radio'    <?php echo esc_js( ( $option[ 'qr_code_format' ] == 'png' ? $checked : '' ) );?> id='qr_code_format' name='qr_code_format' value='png'> <?php _e( 'PNG', 'qr-code-widget' ); ?>
					<input type='radio'    <?php echo esc_js( ( $option[ 'qr_code_format' ] == 'jpg' ? $checked : '' ) );?> id='qr_code_format' name='qr_code_format' value='jpg'> <?php _e( 'Jpeg', 'qr-code-widget' ); ?>
					<span class='description'>* <?php _e( 'Image format PNG or Jpeg', 'qr-code-widget' ); ?>.</span>
				</td>
			</tr>
			<tr>
				<th><label for='qr_code_size'><?php _e( 'QR code size', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<select id='qr_code_size' name='qr_code_size'>
						<option value='4' <?php echo esc_js( ( $option[ 'qr_code_size' ] == 4 ? $selected : '' ) );?>><?php _e( 'Medium', 'qr-code-widget' ); ?></option>
						<option value='2' <?php echo esc_js( ( $option[ 'qr_code_size' ] == 2 ? $selected : '' ) );?>><?php _e( 'Small', 'qr-code-widget' ); ?></option>
						<option value='6' <?php echo esc_js( ( $option[ 'qr_code_size' ] == 6 ? $selected : '' ) );?>><?php _e( 'Large', 'qr-code-widget' ); ?></option>
						<option value='8' <?php echo esc_js( ( $option[ 'qr_code_size' ] == 8 ? $selected : '' ) );?>><?php _e( 'XXL', 'qr-code-widget' ); ?></option>
					</select>
					<span class='description'>* <?php _e( 'Size of QR code', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for='qr_code_ecc'><?php _e( 'Level of error correction', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<select id='qr_code_ecc' name='qr_code_ecc'>
						<option value='0' <?php echo esc_js( ( $option[ 'qr_code_ecc' ] == 0 ? $selected : '' ) );?>> L</option>
						<option	value='1' <?php echo esc_js( ( $option[ 'qr_code_ecc' ] == 1 ? $selected : '' ) );?>> M</option>
						<option value='2' <?php echo esc_js( ( $option[ 'qr_code_ecc' ] == 2 ? $selected : '' ) );?>> Q</option>
						<option	value='3' <?php echo esc_js( ( $option[ 'qr_code_ecc' ] == 3 ? $selected : '' ) );?>> H</option>
					</select>
                    <span
	                    class='description'><br/><?php _e( 'There are 4 ECC Levels in QR code as follows:<br />Level L - 7% of codewords can be restored <br />Level M - 15% of codewords can be restored <br />Level Q - 25% of codewords can be restored <br />Level H - 30% of codewords can be restored <br />', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for='no_cache'><?php _e( 'Disable Cache', 'qr-code-widget' ); ?>:</label></th>
				<td>
					<input type='radio'    <?php echo esc_js( ( $option[ 'no_cache' ] == '0' ? 'checked' : '' ) );?> id='no_cache'
					       name='no_cache'
					       value='0'> <?php _e( 'No', 'qr-code-widget' ); ?>
					<input type='radio'    <?php echo esc_js( ( $option[ 'no_cache' ] == '1' ? 'checked' : '' ) );?> id='no_cache'
					       name='no_cache'
					       value='1'> <?php _e( 'Yes', 'qr-code-widget' ); ?>
					<span
						class='description'>* <?php _e( 'Don\'t cache image. QR code will be created on the fly and embedded in to HTML', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan='2'><h3><?php _e( 'Color Settings', 'qr-code-widget' ); ?></h3></td>
			</tr>
			<tr>
				<th><label for='qr_fg'><?php _e( 'code Color', 'qr-code-widget' ); ?>:</label></th>
				<td>
					#<input id='qr_fg' name='qr_code_fg' maxlength='7' size='7'
					        value='<?php echo esc_js( $option[ 'qr_code_fg' ] );?>'/>
					<span class='description'>* <?php _e( 'Set Color for QR code', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for='qr_bg'><?php _e( 'Background Color', 'qr-code-widget' ); ?>:</label></th>
				<td>
					#<input id='qr_bg' name='qr_code_bg' maxlength='7' size='7'
					        value='<?php echo esc_js( $option[ 'qr_code_bg' ] );?>'/>
                    <span
	                    class='description'>* <?php _e( 'Set background Color for QR code', 'qr-code-widget' ); ?></span>
				</td>

			</tr>
			<tr>
				<th><label for='qr_code_trans_bg'><?php _e( 'Background transparency', 'qr-code-widget' ); ?>:</label>
				</th>
				<td>
					<input type='radio' <?php echo esc_js( ( $option[ 'qr_code_trans_bg' ] == '0' ? $checked : '' ) );?> id='qr_code_trans_bg' name='qr_code_trans_bg' value='0'>
					<?php _e( 'No', 'qr-code-widget' ); ?>
					<input type='radio' <?php echo esc_js( ( $option[ 'qr_code_trans_bg' ] == '1' ? $checked : '' ) );?> id='qr_code_trans_bg' name='qr_code_trans_bg' value='1'>
					<?php _e( 'Yes', 'qr-code-widget' ); ?>
					<span class='description'>* <?php _e( 'Set transparency for QR code background. <b>PNG Only</b>', 'qr-code-widget' ); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan='2'><input type='submit' class='button-primary'
				                       value='<?php _e( 'Save Changes', 'qr-code-widget' ) ?>'/></td>
			</tr>
		</table>
	</form>
<? }

function show_qr_cache()
{ ?>	
	<script  type='text/javascript'>
	jQuery(document).ready(function(){
		jQuery('.3762').on('click', function() {
			var file = jQuery(this).val();
			var parts = file.split('.');
			var fid = parts[0];
			var ht = '<?php echo esc_js( QCW_URLPATH . 'cache/' ) ?>' + file;

			jQuery.ajax({
				type: 'get',
				url: 'http://www.poluschin.info/decode/',
				data: { 
					url: ht
				},
				dataType: 'json',
				success: function(data){
					jQuery('#btn' + fid).hide('fast');
					jQuery('#'+fid).text('');
					if (data.type == 'error'){
						jQuery('#'+fid).append('<div class="qr_error">' + data.data + '</div>');
					}
					else{
						jQuery('#'+fid).append('Decoded String: <div class="qr_data">'+ data.data + '</div>');
						jQuery('#'+fid).append('<div class="qr_promo">'+ data.promo + '</div>');
					}
					jQuery('#'+fid).fadeOut().fadeIn();
				},
				
			});
			return  false;
		});
	}); 
	</script>
	<div class="info"><?php _e( 'Decode by <a href="http://www.poluschin.info">zbar on Poluschin.info</a>','qr-code-widget' );?></div>
	<table class="wp-admin widefat">
	<thead>
		<tr>
			<th><?php _e( 'Qr Image', 'qr-code-widget' );?></th>
			<th><?php _e( 'QrCode Details', 'qr-code-widget' );?></th>
			<th class="nowrap"><?php _e( 'Creation date', 'qr-code-widget' );?></th>
		</tr>
	</thead>
	<tbody>
		<form method="post" action="">
			<?php 
			$items = glob( QCW_IMAGE_CACHE . '*.*', GLOB_NOSORT );
			array_multisort( array_map( 'filemtime', $items ), SORT_NUMERIC, SORT_DESC, $items );
			foreach ( $items as $filename ) {
				$file = basename( $filename );
				$ext  = pathinfo( $file, PATHINFO_EXTENSION );
				$fid  = basename( $file, '.' . $ext );
			?>
			<tr>
				<td><img src='<?php echo esc_url( QCW_URLPATH  . 'cache/'. $file );	?>'></td>
				<td>
					<button value="<?php echo esc_html_e( $file )?>" id="btn<?php echo esc_html_e( $fid )?>"  class="button primary 3762" type="submit">Decode</button>
					<div id="<?php echo esc_html_e( $fid ); ?>"></div>
				</td>
				<td class="nowrap"><?php echo esc_html_e( date( 'd M Y H:i:s', filectime( $filename ) ) );?></td>
			</tr>
			<?php } ?>
		</form>
	</tbody>
	</table>
	<?php exit();
}

function qrcode_request_action()
{
	$output = __( 'Error', 'qr-code-widget' );
	if ( !empty( $_POST ) && check_admin_referer( 'qrcode' ) ) {
		if ( isset( $_POST[ 'clear_cache' ] ) ) {
			clear_cache();
			$output = __( 'Cache cleared', 'qr-code-widget' );
		}

		if ( isset( $_POST[ 'create_cache' ] ) ) {
			$output = create_cache();
		}
	}
	return $output;
}

function qrcode_options_submit()
{
	$allowed_tags = wp_kses_allowed_html( 'post' );
	if ( !empty ( $_POST ) && check_admin_referer( 'QCW_settings' )  ) {
		if ( $_POST[ 'qrcode_options_submit' ] == 'qr_code_sc' ) {
			switch ( $_POST['reset_sc'] ) {
				case 1:
					global $defaults_sc;
					delete_option( 'qr_code_sc' );
					add_option( 'qr_code_sc', $defaults_sc );
					break;
				
				default:
					$new_options = array(
						'qr_code_format' => $_POST[ 'qr_code_format' ],
						'qr_code_trans_bg' => $_POST[ 'qr_code_trans_bg' ],
						'qr_code_bg' => $_POST[ 'qr_code_bg' ],
						'qr_code_fg' => $_POST[ 'qr_code_fg' ],
						'qr_code_size' => $_POST[ 'qr_code_size' ],
						'qr_code_ecc' => $_POST[ 'qr_code_ecc' ],
						'no_cache' => $_POST[ 'no_cache' ],
					);
					update_option( 'qr_code_sc', $new_options );
					break;
			}
		}

		if ( $_POST[ 'qrcode_options_submit' ] == 'qr_code_wg' ) {
			switch ( $_POST['reset_wg'] ) {
				case 1:
					reset_qr_widget_defaults();
					break;
				
				default:
					$new_options = array(
						'title' => wp_kses( $_POST[ 'title' ],$allowed_tags ),
						'qr_code_format' => $_POST[ 'qr_code_format' ],
						'qr_code_trans_bg' => $_POST[ 'qr_code_trans_bg' ],
						'qr_code_bg' => $_POST[ 'qr_code_bg' ],
						'qr_code_fg' => $_POST[ 'qr_code_fg' ],
						'qr_code_size' => $_POST[ 'qr_code_size' ],
						'qr_code_ecc' => $_POST[ 'qr_code_ecc' ],
						'pre_code' => wp_kses( $_POST[ 'pre_code' ], $allowed_tags ),
						'post_code' => wp_kses( $_POST[ 'post_code' ], $allowed_tags ),
						'no_cache' => $_POST[ 'no_cache' ],
					);
					update_option( 'qr_code_wg', $new_options );
					break;
			}
		}
	}
}

function clear_cache()
{
	foreach ( glob( QCW_IMAGE_CACHE . '*.*' ) as $cachedfile ) {
		unlink( $cachedfile );
	}
}

function create_cache()
{
	if ( !is_dir( QCW_IMAGE_CACHE ) ) {
		if ( mkdir( QCW_IMAGE_CACHE ) ){
			return __( 'Cache created', 'qr-code-widget' );
		}
		return __( 'Can\'t create Cache', 'qr-code-widget' );
	}
	return __( 'Cache folder already exists', 'qr-code-widget' );
}

function reset_qr_widget_defaults() {
	$wg_defaults = get_option( 'qr_code_wg' );
	$wg 		 = new QrCodeWidget();
	$settings    = $wg->get_settings();
	foreach ( $settings as $key => $value ) {
		unset( $value );
		$settings[$key] = $wg_defaults;
	}
	$wg->save_settings( $settings );
}

function qr_code_activate()
{
	$defaults_wg = array(
		'title' => 'QR Code',
		'qr_code_bg' => 'ffffff',
		'qr_code_fg' => '000000',
		'qr_code_trans_bg' => '0',
		'qr_code_format' => 'png',
		'qr_code_ecc' => '1',
		'qr_code_size' => '2',
		'pre_code' => '<div>',
		'no_cache' => '0',
		'post_code' => '<p style="padding:0;margin:0;font-size:.8em;">QR code created by<a href="http://www.poluschin.info/">QR code Widget</a></p></div>',
		'version' => QCW_VERSION,
	);

	$defaults_sc = array(
		'qr_code_bg' => 'ffffff',
		'qr_code_fg' => '000000',
		'qr_code_trans_bg' => '0',
		'qr_code_format' => 'png',
		'qr_code_ecc' => '1',
		'qr_code_size' => '2',
		'no_cache' => '0',
		'version' => QCW_VERSION,
	);

	if ( ( $old_params = get_option( 'qr_code_widget' ) ) ) {
		$size   = $old_params[ 'size' ];
		$format = ( $old_params[ 'format' ] == 'J' ? 'jpg' : 'png' );

		$defaults_sc = array(
			'qr_code_bg' => 'ffffff', 
			'qr_code_fg' => '000000',
			'qr_code_trans_bg' => '0', 
			'qr_code_format' => $format,
			'qr_code_ecc' => '1', 
			'qr_code_size' => $size,
			'no_cache' => '0', 
			'version' => QCW_VERSION,
		);

		$defaults_wg = array(
			'title' => 'QR Code',
			'qr_code_bg' => 'ffffff',
			'qr_code_fg' => '000000',
			'qr_code_trans_bg' => '0',
			'qr_code_format' => $format,
			'qr_code_ecc' => '1',
			'qr_code_size' => $size,
			'pre_code' => '<div>',
			'no_cache' => '0',
			'post_code' => '</div><p style="padding:0;margin:0;font-size:.8em;">QR code created by<a href="http://www.poluschin.info/">QR code Widget</a></p>',
			'version' => QCW_VERSION,
		);
	}

	if ( current_user_can( 'activate_plugins' ) ) {
		delete_option( 'qr_code_widget' );
		add_option( 'qr_code_sc', $defaults_sc );
		add_option( 'qr_code_wg', $defaults_wg );
		if ( !is_dir( QCW_IMAGE_CACHE ) ) {
			mkdir( QCW_IMAGE_CACHE );
		}
		return;
	}

	deactivate_plugins( plugin_basename( 'qr-code-widget.php' ) );
	wp_die( __( 'You do not have appropriate access to activate this plugin! Contact your administrator!<br /><a href="'. get_option( 'siteurl' ).'/wp-admin/plugins.php">Back to plugins</a>.' ) );
	return;
}

function qcw_credits() 
{
	$output = '<table class="widefat" style="margin-top: .5em">
	<thead>
	<tr>
	<th>'
		. __( 'Your support makes a difference', 'qr-code-widget' ) .
	'</th>
	<th>'
		. __( 'Useful links', 'qr-code-widget' ) .
	'</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>
			<ul>
	    		<li>'
					. __( 'Your awesome gift will ensure the continued development of this Plugin! Thanks for your consideration!', 'qr-code-widget' ) .
				'</li>
				<li><a href="http://www.poluschin.info/donate/" target="_blank"><img src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" /></a></li>
			</ul>
		</td>
		<td style="border-left:1px #ddd solid;">
			<ul>
				<li>'. __( 'Like the plugin?', 'qr-code-widget' ) .' <a href="http://wordpress.org/support/view/plugin-reviews/qr-code-widget?rate=5#postform" target="_blank">'.__( 'Rate and review', 'qr-code-widget' ).'</a> QR Code Widget.</li>
				<li>'. __( 'Find my website at', 'qr-code-widget' ) . ' <a href="http://www.poluschin.info" target="_blank">poluschin.info</a>.</li>
				<li>'.__( 'Found a issue? Submit it at', 'qr-code-widget' ).' <a href="http://scm.poluschin.info/projects/qrcodewidget" target="_blank">scm.poluschin.info</a>.</li>
			</ul>
		</td>
	</tr>
	</tbody>
	</table>
	';
	echo $output;
}

/**
function decode_qr_code() {
	if ( !empty( $_POST ) && check_admin_referer( 'qr_code-next-nonce', 'nextNonce' ) ) {		
		$file  = QCW_IMAGE_CACHE . $_POST['file'];
		$url   = 'http://www.poluschin.info/decode/?url=' . QCW_URLPATH . 'cache/' . $_REQUEST['file'];
		$hfile = fopen( $file, 'r' );
		$ch    = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );

		if ( ! $result = curl_exec( $ch ) ) {
			trigger_error( curl_error( $ch ) );
		}
		fclose( $hfile );
		curl_close( $ch );
		echo json_encode( $result );
		exit();
	}
}
**/

function qr_code_deactivate()
{
	delete_option( 'qr_code_widget' );
	delete_option( 'qr_code_sc' );
	delete_option( 'qr_code_wg' );
	delete_option( 'widget_qrcode_widget' );
}

function qr_code_uninstall()
{
	delete_option( 'qr_code_widget' );
	delete_option( 'qr_code_sc' );
	delete_option( 'qr_code_wg' );
	delete_option( 'widget_qrcode_widget' );
}

add_action( 'wp_ajax_show_qr_cache', 'show_qr_cache' );
add_action( 'wp_ajax_decode_qr_code', 'decode_qr_code' );
?>