<?php
/**
 * Display Ad Item Setting.
 *
 * @package UDAdsManager
 */

wp_nonce_field( 'ad_info_data', 'ad_info_nonce' ); ?>
<p>Specify all required data for render dfp</p>
<h3>General</h3>
<table class="form-table">
	<tbody>
		<?php echo $args['options']['enable']['html']; ?>
		<tr>
			<th scope="row">Shortcode</th>
			<td>
				<input class="regular-text" type="text" readonly value="<?php echo esc_attr( $args['ad_shortcode'] ); ?>">
			</td>
		</tr>
		<?php
		echo $args['options']['ad_type']['html'];
		echo $args['options']['excluded_cats']['html'];
		echo $args['options']['included_cats']['html'];
		?>
	</tbody>
</table>

<hr>
<h3>Display Ad Box</h3>
<table class="form-table">
	<tbody>
		<?php echo $args['options']['enable_ad_box']['html']; ?>
		<?php echo $args['options']['ad_box_width']['html']; ?>
		<?php echo $args['options']['ad_box_height']['html']; ?>
		<?php echo $args['options']['ad_box_mobile_width']['html']; ?>
		<?php echo $args['options']['ad_box_mobile_height']['html']; ?>
		<?php echo $args['options']['ad_box_padding']['html']; ?>
		<?php echo $args['options']['ad_box_background_color']['html']; ?>
	</tbody>
</table>
<hr>

<div id="dfp-ad-settings">
	<p>Specify all required data for render dfp ad</p>
	<h3>DFP Ad Setting</h3>
	<table class="form-table">
		<tbody>
			<?php
			foreach ( array( 'dfp_ad_collapse_empty_div', 'dfp_ad_unit_path', 'dfp_ad_size_global', 'dfp_ad_custom_css_global', 'dfp_ad_fallback_image_global' ) as $field_id ) :
				echo $args['options'][ $field_id ]['html'];
			endforeach;
			?>
		</tbody>
	</table>
	<hr>

	<h3>Responsive Mapping Ad Sizes</h3>
	<p>Specify at least one of these sizes will override Global Ad Size</p>
	<p>Example: [320,100], "fluid"</p>
	<p>Noted: double quote must me used to enclose fluid string (single quote is not supported) </p>
	<table class="form-table">
		<tbody>
<?php foreach ( array( 'dfp_ad_size_d', 'dfp_ad_size_tl', 'dfp_ad_size_tp', 'dfp_ad_size_m' ) as $field_id ) : ?>
			<?php echo $args['options'][ $field_id ]['html']; ?>
<?php endforeach ?>
		</tbody>
	</table>
	<hr>

	<h3>Responsive Custom CSS</h3>
	<p>responsive custom css</p>
	<table class="form-table">
		<tbody>
<?php foreach ( array( 'dfp_ad_use_responsive_custom_css', 'dfp_ad_custom_css_d', 'dfp_ad_custom_css_tl', 'dfp_ad_custom_css_tp', 'dfp_ad_custom_css_m' ) as $field_id ) : ?>
			<?php echo $args['options'][ $field_id ]['html']; ?>
<?php endforeach ?>
		</tbody>
	</table>
	<hr>

	<h3>Responsive Fallback Image</h3>
	<p>responsive fallback image</p>
	<table class="form-table">
		<tbody>
<?php foreach ( array( 'dfp_ad_use_responsive_fallback_image', 'dfp_ad_fallback_image_d', 'dfp_ad_fallback_image_tl', 'dfp_ad_fallback_image_tp', 'dfp_ad_fallback_image_m' ) as $field_id ) : ?>
			<?php echo $args['options'][ $field_id ]['html']; ?>
<?php endforeach ?>
		</tbody>
	</table>
</div>
<div id="custom-ad-settings">
	<p>Specify all required data for render custom ad</p>
	<h3>Custom Ad Setting</h3>
	<table class="form-table">
<?php foreach ( array( 'custom_ad_custom_html' ) as $field_id ) : ?>
		<tbody>
			<?php echo $args['options'][ $field_id ]['html']; ?>
		</tbody>
<?php endforeach ?>
	</table>
</div>

<script>
	(function(){
		var select = document.getElementById('ud_ad_info_ad_type');
		if(!select){
			return;
		}

		var divs = {
			'dfp' : document.getElementById('dfp-ad-settings'),
			'custom' : document.getElementById('custom-ad-settings')
		}

		Object.values(divs).forEach(function(elem){ elem.style.display = 'none' });
		divs[select.value].style.display = 'block';

		select.addEventListener('change', function handleChange(event) {
			Object.values(divs).forEach(function(elem){ elem.style.display = 'none' });
			divs[event.target.value].style.display = 'block';
		});
	})();
</script>
