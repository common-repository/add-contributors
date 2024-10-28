<?php
/**
 * ICT Contributors plugin file
 *
 * @since             1.0.0
 * @package           ict101s-contributor\admin
 */

namespace ict101s;

/**
 * Main class for the admin portion of the contributor plugin
 *
 * This code adds a metabox for the contributors on the edit post page,
 * creates html for checkbox for each contributor and a method to update
 * the contributor's meta value in the postmeta table
 *
 * @since             1.0.0
 */
abstract class Contributors_Admin {

	/**
	 * Array of user objects
	 *
	 * @var    array    $user    Holds the array of site member objects
	 */
	private static $users;
	/**
	 * List of the allowed roles
	 *
	 * @var    array    @allowed_roles    Stores the roles allowed to click the
	 *  check box to select contributors
	 */
	private static $allowed_roles = array( 'administrator', 'editor', 'author' );

	/**
	 * Function to setup the meta box for contributors
	 */
	public static function ict_add_contributor_setup() {
		/* Add meta box on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', [ self::class, 'add_contributor_box' ] );
	}

	/**
	 * Function to add a meta box for contributors in the edit post page
	 */
	public static function add_contributor_box() {
		add_meta_box(
			'contributors_box__id',
			__( 'Contributors', 'ict101s_contributors' ),
			[ self::class, 'create_contributor_html' ],
			'post'
		);
	}

	/**
	 * Generates the HTML that produces the check boxes
	 * Object of post.
	 *
	 * @param    object $post    Object of post
	 * ID of the current post.
	 *
	 * @param    int    $post_id    id of current post.
	 */
	public static function create_contributor_html( $post, $post_id ) {

		self::$users  = get_users(
			array(
				'blog_id' => '1',
				'orderby' => 'nicename',
				'fields'  => array(
					'user_login',
					'display_name',
				),
			)
		);
		$contributors = get_post_meta( $post->ID, 'ict101s_contributor', false );
			wp_nonce_field( basename( __FILE__ ), 'ict_contributors_nonce' );
		foreach ( self::$users as $author ) {

			?> 
			<input type="checkbox" 
				<?php
					$users_data  = get_userdata( get_current_user_id() );
					$users_roles = $users_data->roles;
				foreach ( $users_roles as $key => $value ) {
					if ( ! in_array( $value, self::$allowed_roles, true ) ) {
						echo 'disabled';
						break;
					}
				}
				?>
				name="contributors[]" 
				value="<?php echo esc_html( $author->user_login ); ?>" 
				<?php
				if ( ! empty( $contributors ) ) {
					foreach ( $contributors as $key => $value ) {
						if ( $author->user_login === $value ) {
							echo ' checked';
						}
					}
				}

				?>
			> 
					<?php echo esc_html( $author->display_name ); ?>
			</input>
			<br>

			<?php
		}

	}

	/**
	 * Updates contributors meta values in the post meta table
	 *
	 * ID of the current post
	 *
	 * @param    int    $post_id    id of current post.
	 * Object of the current post.
	 *
	 * @param    object $post    object of the current post.
	 */
	public static function update_contributors_meta_value( $post_id, $post ) {
		/* Verify the nonce before proceeding. */
		if ( ! isset( $_POST['ict_contributors_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ict_contributors_nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}

		/* Get the post type object.  */
				$post_type = get_post_type_object( $post->post_type );

		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}

			$new_meta_value     = ( isset( $_POST['contributors'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['contributors'] ) ) : '' );
			$current_meta_value = get_post_meta( $post_id, 'ict101s_contributor', false );
			$meta_key           = 'ict101s_contributor';

		if ( '' !== $new_meta_value && ! empty( $current_meta_value ) ) {
			$diff_new_to_old = array_diff( $new_meta_value, $current_meta_value );
			$diff_old_to_new = array_diff( $current_meta_value, $new_meta_value );
		}

		if ( '' === $new_meta_value && empty( $current_meta_value ) ) {
			return;
		} elseif ( '' !== $new_meta_value && empty( $current_meta_value ) ) {
			self::save_meta_data( $new_meta_value, $meta_key, $post_id );
		} elseif ( '' === $new_meta_value && ! empty( $current_meta_value ) ) {
			self::delete_meta_data( $current_meta_value, $meta_key, $post_id );
		} elseif ( empty( $diff_new_to_old ) && empty( $diff_old_to_new ) ) {
			return;
		} elseif ( ! empty( $diff_new_to_old ) && empty( $diff_old_to_new ) ) {
			self::save_meta_data( $diff_new_to_old, $meta_key, $post_id );
		} elseif ( empty( $diff_new_to_old ) && ! empty( $diff_old_to_new ) ) {
			self::delete_meta_data( $diff_old_to_new, $meta_key, $post_id );
		} elseif ( ! empty( $diff_new_to_old ) && ! empty( $diff_old_to_new ) ) {
			self::save_meta_data( $diff_new_to_old, $meta_key, $post_id );
			self::delete_meta_data( $diff_old_to_new, $meta_key, $post_id );
		}
	}

	/**
	 * Saves contributos' meta values in the post meta table
	 *
	 * Array of the value to save.
	 *
	 * @param    array  $array    array of the contributors values to save
	 *  The name of the meta key to save.
	 *
	 * @param    string $meta_key    name of the meta key
	 * ID of the current post.
	 *
	 * @param    int    $post_id    id of current post.
	 */
	private static function save_meta_data( $array, $meta_key, $post_id ) {

		foreach ( $array as $key => $value ) {
					add_post_meta( $post_id, $meta_key, $value );
		}

	}

	/**
	 * Delets contributos' meta values in the post meta table
	 *
	 * Array of the value to delete.
	 *
	 * @param    array  $array    array of the contributors values to delete
	 *  The name of the meta key to delete.
	 *
	 * @param    string $meta_key    name of the meta key
	 * ID of the current post.
	 *
	 * @param    int    $post_id    id of current post.
	 */
	private static function delete_meta_data( $array, $meta_key, $post_id ) {

		foreach ( $array as $key => $value ) {
			delete_post_meta( $post_id, $meta_key, $value );
		}
	}
}
