<?php
/**
 * Temporary CSS and JS will be move to main plugin
 *
 * @package AdvancedAds\Framework
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.0.0
 */

namespace AdvancedAds\Framework;

// Early bail!!
if ( ! function_exists( 'add_action' ) ) {
	return;
}

/**
 * Register CSS
 *
 * @return void
 */
function advanced_ads_framework_css() {
	?>
	<style>
		.advads-placements-table .advads-option-placement-page-peel-position div.clear {
			content: ' ';
			display: block;
			float: none;
			clear: both;
		}
		.advads-field-position table tbody tr td {
			width: 3em !important;
			height: 2em;
			text-align: center;
			vertical-align: middle;
			padding: 0;
		}
	</style>
	<?php
}
add_action( 'admin_head', __NAMESPACE__ . '\\advanced_ads_framework_css', 100, 0 );

/**
 * Register JS
 *
 * @return void
 */
function advanced_ads_framework_js() {
	?>
	<script>
		jQuery(document).ready(function($) {
			if (undefined !== jQuery.fn.wpColorPicker) {
				$('.advads-field-color .advads-field-input input').wpColorPicker({defaultColor: '#5d5d5d'});
			}
		});
	</script>
	<?php
}
add_action( 'admin_footer', __NAMESPACE__ . '\\advanced_ads_framework_js', 100, 0 );
