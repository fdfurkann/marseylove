<?php
/**
 * The header area 
 *
 * This is the template that displays all of the <head> section and everything up until <div class="site-content">
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Goya
 */
 ?>

 <!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php 
		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head(); 

	?>
	<style>
		/* Header Before (Top Bar) Styles */
		.header-before {
			background: #000;
			color: #fff;
			padding: 10px 0;
			font-size: 12px;
			border-bottom: 1px solid rgba(255,255,255,0.1);
			transition: transform 0.3s ease, opacity 0.3s ease;
			position: relative;
			z-index: 1000;
		}
		.header-before .container {
			display: flex;
			justify-content: space-between;
			align-items: center;
			max-width: 100%;
			margin: 0 auto;
			padding: 0 30px;
		}
		.header-before-left {
			display: flex;
			gap: 20px;
			align-items: center;
		}
		.header-before-left a {
			color: #fff;
			text-decoration: none;
			font-weight: 400;
			letter-spacing: 0.5px;
			transition: all 0.2s ease;
		}
		.header-before-left a:hover {
			color: rgba(255,255,255,0.7);
		}
		.language-switcher {
			display: flex;
			align-items: center;
		}
		.language-switcher select {
			padding: 5px 30px 5px 10px;
			border: 1px solid rgba(255,255,255,0.2);
			border-radius: 2px;
			background: transparent;
			color: #fff;
			font-size: 12px;
			font-weight: 400;
			letter-spacing: 0.3px;
			cursor: pointer;
			appearance: none;
			-webkit-appearance: none;
			-moz-appearance: none;
			background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
			background-repeat: no-repeat;
			background-position: right 8px center;
			background-size: 12px;
			transition: all 0.2s ease;
		}
		.language-switcher select:hover {
			border-color: rgba(255,255,255,0.4);
			background-color: rgba(255,255,255,0.05);
		}
		.language-switcher select:focus {
			outline: none;
			border-color: rgba(255,255,255,0.5);
			background-color: rgba(255,255,255,0.08);
		}
		.language-switcher select option {
			background: #000;
			color: #fff;
			padding: 10px;
		}
		/* Hide header-before on scroll with smooth animation */
		body.header_on_scroll .header-before {
			transform: translateY(-100%);
			opacity: 0;
			pointer-events: none;
		}
		/* Adjust header spacer when top bar is hidden */
		body.header_on_scroll .site-header {
			top: 0 !important;
		}
		@media (max-width: 768px) {
			.header-before {
				padding: 8px 0;
				font-size: 11px;
			}
			.header-before .container {
				padding: 0 20px;
			}
			.header-before-left {
				gap: 12px;
			}
			.header-before-left a {
				font-size: 11px;
			}
			.language-switcher select {
				padding: 4px 25px 4px 8px;
				font-size: 11px;
			}
		}
		@media (max-width: 480px) {
			.header-before-left a:first-child {
				display: none;
			}
		}
	</style>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<?php do_action( 'goya_before_site' ) ?>

<div id="wrapper" class="open">
	
	<div class="click-capture"></div>
	
	<?php do_action( 'goya_before_header' ) ?>

	<div class="page-wrapper-inner">

		<?php 
		// Elementor header or default
		if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
			do_action( 'goya_header' );
		} ?>

		<div role="main" class="site-content">

			<div class="header-spacer"></div>

			<?php do_action( 'goya_after_header_spacer' ); ?>