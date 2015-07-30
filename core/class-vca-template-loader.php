<?php
/**
 * Template Loader
 *
 * @class 		WC_Template
 * @version		2.2.0
 * @package		WooCommerce/Classes
 * @category	Class
 * @author 		WooThemes
 */
class WC_Template_Loader {

	/**
	 * Hook in methods
	 */
	public static function init() {
		add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
		add_filter( 'comments_template', array( __CLASS__, 'comments_template_loader' ) );
	}

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. Village Client Area looks for theme
	 * overrides in /theme/client-area/ by default
	 **
	 * @param mixed $template
	 * @return string
	 */
	public static function template_loader( $template ) {
		$find = array();
		$file = '';

		if ( is_single() && get_post_type() == 'client_gallery' ) {

			$file 	= 'single-client_gallery.php';
			$find[] = $file;
			$find[] = VCA()->template_path() . $file;

		} elseif ( is_post_type_archive( 'client_gallery' ) ) {

			$file 	= 'archive-client_gallery.php';
			$find[] = $file;
			$find[] = VCA()->template_path() . $file;

		}

		if ( $file ) {
			$template       = locate_template( array_unique( $find ) );
			if ( ! $template ) {
				$template = VCA()->plugin_path() . '/templates/' . $file;
			}
		}

		return $template;
	}

	/**
	 * comments_template_loader function.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public static function comments_template_loader( $template ) {

		if ( get_post_type() !== '' ) {
			return $template;
		}

		$check_dirs = array(
			trailingslashit( get_stylesheet_directory() ) . VCA()->template_path(),
			trailingslashit( get_template_directory() ) . VCA()->template_path(),
			trailingslashit( get_stylesheet_directory() ),
			trailingslashit( get_template_directory() ),
			trailingslashit( VCA()->plugin_path() ) . 'templates/'
		);

		foreach ( $check_dirs as $dir ) {
			if ( file_exists( trailingslashit( $dir ) . 'client-comments.php' ) ) {
				return trailingslashit( $dir ) . 'client-comments.php';
			}
		}
	}
}

WC_Template_Loader::init();