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
		.advads-field-switch input[type=checkbox] {
			display: none;
		}
		.advads-field-switch input[type=checkbox]:checked ~ .toggle {
			background: #009688;
			left: 52px;
			transition: 0.5s;
		}
		.advads-field-switch input[type=checkbox]:checked ~ .switch {
			background: #6DBEB7;
			transition: 0.5s;
		}

		.advads-field-switch .switch {
			display: block;
			width: 100px;
			height: 40px;
			background: #939393;
			border-radius: 20px;
			position: absolute;
			top: 0;
			transition: 0.5s;
		}

		.advads-field-switch .toggle {
			height: 56px;
			width: 56px;
			border-radius: 50%;
			background: white;
			position: absolute;
			top: -8px;
			left: -8px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
			transition: 0.5s;
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
