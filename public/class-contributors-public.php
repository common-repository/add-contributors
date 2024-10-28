<?php
/**
 * ICT Contributors plugin file
 *
 * @since             1.0.0
 * @package           ict101s-contributor\public
 */

namespace ict101s;

/**
 * Main class for the public portion of the contributor plugin
 *
 * This code appends the list of contributors to the content,
 * and also enqueques the css to format the contributors' box
 *
 * @since             1.0.0
 */
abstract class Contributors_Public {
	/**
	 * Displays the list of the contributors at the bottom of the post content
	 *
	 * @param string $content Holds the content of each post.
	 * */
	public static function display_contributors( $content ) {
		$contributors = get_post_meta( get_the_ID(), 'ict101s_contributor', false );
		if ( ! $contributors ) {
			return $content;
		}
		ob_start();
		foreach ( $contributors as $key => $value ) {
			$user         = get_user_by( 'login', $value );
			$authors_page = get_author_posts_url( $user->ID );
					echo '<li>' . get_avatar( $user->ID ) . '&nbsp &nbsp<a href =' . esc_url( $authors_page ) . ' >' . esc_html( $user->display_name ) . '</a></li>';
		}
		$contributors_list = ob_get_clean();
		$heading           = __( 'Contributors', 'ict101s-contributors' );
		$content          .= '	
			<div id="contributor-box">
				<h3>' . $heading . '</h3>
				<ul>' .
					$contributors_list
				. '</ul> 
			</div>';

		return $content;
	}

	/**
	 * Registers and enqueues css styles for the public view
	 */
	public static function enqueue_plugin_styles() {
		wp_enqueue_style( 'style', plugin_dir_url( __FILE__ ). 'css/public-style.css', array(), '1.0' );
	}
}

