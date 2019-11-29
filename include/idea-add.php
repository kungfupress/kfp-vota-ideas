<?php
/**
 * File: include/idea-add.php
 *
 * @package kfp-vti
 */

defined( 'ABSPATH' ) || die();

// Crea el shortcode para mostrar el formulario de propuesta de ideas.
add_shortcode( 'kfp-vti-idea-add', 'kfp_vti_idea_add' );
// Agrega los action hooks para grabar el formulario (el primero para usuarios
// logeados y el otro para el resto)
// Lo que viene tras admin_post_nopriv_ tiene que coincidir con el value
// del campo input con name "action" del formulario.
add_action( 'admin_post_kfp-vti-idea-save', 'kfp_vti_idea_save' );
add_action( 'admin_post_nopriv_kfp-vti-idea-save', 'kfp_vti_idea_save' );

/**
 * Muestra el formulario para proponer ideas desde el frontend
 *
 * @return string
 */
function kfp_vti_idea_add() {
	global $wpdb;
	if ( isset( $_GET['kfp_vti_alert_text'] ) ) {
		echo '<h4>' . $_GET['kfp_vti_alert_text'] . '</h4>';
	}
	ob_start();
	?>
	<form name="idea"action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" 
		method="post" id="kfp-vti-form-idea-save">
		<?php wp_nonce_field( 'kfp-vti-form', 'kfp-vti-form-nonce' ); ?>
		<input type="hidden" name="action" value="kfp-vti-idea-save">
		<input type="hidden" name="kfp-vti-backlink" 
			value="<?php echo home_url( add_query_arg( array() ) ); ?>"
		<p>
			<label for="kfp-vti-title">Idea</label>
			<input type="text" name="kfp-vti-title" id="kfp-vti-title" 
				placeholder="Pon un título breve pero descriptivo a tu idea">
		</p>
		<p>
			<label for="kfp-vti-content">Descripción</label>
			<textarea name="kfp-vti-content" id="kfp-vti-content" 
				placeholder="Aquí puedes explicar mejor tu idea"></textarea>
		</p>
		<p>
			<input type="submit" name="kfp-vti-submit" value="Enviar idea">
		</p>
	</form>
	<?php
	return ob_get_clean();
}

/**
 * Procesa el formulario para proponer ideas desde el frontend
 *
 * @return void
 */
function kfp_vti_idea_save() {
	if ( filter_has_var( INPUT_POST, 'kfp-vti-backlink' ) ) {
		$backlink = filter_input( INPUT_POST, 'kfp-vti-backlink', FILTER_SANITIZE_URL );
	}

	if ( empty( $_POST['kfp-vti-title'] ) || empty( $_POST['kfp-vti-content'] )
		|| ! wp_verify_nonce( $_POST['kfp-vti-form-nonce'], 'kfp-vti-form' ) ) {
		$alert      = 'error';
		$alert_text = 'Por favor, rellena los contenidos requeridos del formulario';
		wp_safe_redirect(
			esc_url_raw(
				add_query_arg(
					array(
						'kfp_vti_alert'      => $alert,
						'kfp_vti_alert_text' => $alert_text,
					),
					$backlink
				)
			)
		);
		exit();
	}

	$args = array(
		'post_title'     => filter_input( INPUT_POST, 'kfp-vti-title', FILTER_SANITIZE_STRING ),
		'post_content'   => filter_input( INPUT_POST, 'kfp-vti-content', FILTER_SANITIZE_STRING ),
		'post_type'      => 'vti_idea',
		'post_status'    => 'publish',
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
	);

	// Esta variable $post_id contiene el ID del nuevo registro
	// Nos vendría de perlas para grabar los metadatas
	$post_id = wp_insert_post( $args );

	$alert      = 'success';
	$alert_text = 'Has registrado tu idea correctamente. ¡Gracias!';
	wp_redirect(
		esc_url_raw(
			add_query_arg(
				array(
					'kfp_vti_alert'      => $alert,
					'kfp_vti_alert_text' => $alert_text,
				),
				$backlink
			)
		)
	);
	exit();
}
