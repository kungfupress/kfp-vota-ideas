<?php
/**
 * File: include/idea-list.php
 * Reference: https://toolset.com/forums/topic/increment-count-when-link-is-clicked/
 *
 * @package kfp-vti
 */

defined( 'ABSPATH' ) || die();

// Crea el shortcode para mostrar el listado de ideas.
add_shortcode( 'kfp-vti-idea-list', 'kfp_vti_idea_list' );
/**
 * Devuelve el html con la lista de ideas
 *
 * @return string
 */
function kfp_vti_idea_list() {
	global $wpdb;
	wp_enqueue_script( 'kfp-vti-vote-link' );
	$args = array(
		'post_type'      => 'vti_idea',
		'posts_per_page' => 30,
	);
	// This query get the idea's list.
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		$html = '<table id="tbl"><tbody>';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$idea_id = get_the_ID();
			// Devuelve el número actual de votos.
			$query = new WP_Query(
				array(
					'post_type'   => 'vti_vote',
					'post_parent' => $idea_id,
				)
			);
			$votes = $query->found_posts;
			// Importante lo del get_ !
			$html .= '<tr><td>' . get_the_ID() . ' - <b>' . get_the_title() . '</b>';
			$html .= '<br>' . get_the_content() . '</td>';
			$html .= '<td>' . (int) $votes . ' votos - <a href="#" class="vote"';
			$html .= 'data-idea-id="' . get_the_ID() . '">Votar</a></td></tr>';
		}
		$html .= '</tbody></table>';
	} else {
		$html = '<p>' . esc_html_e( 'De momento a nadie se le he ocurrido ninguna idea. ¿Tienes alguna?' ) . '</p>';
	}
	// No hace echo o print desde un shortcode, usar return.
	return $html;
}

add_action( 'wp_enqueue_scripts', 'kfp_vti_idea_list_scripts' );
/**
 * Encola los scripts para permitir el voto
 *
 * @return void
 */
function kfp_vti_idea_list_scripts() {
	wp_register_script(
		'kfp-vti-vote-link',
		plugins_url( '../js/vote-link.js', __FILE__ ),
		array( 'jquery' ),
		KFP_VTI_VERSION,
		false
	);
	wp_localize_script(
		'kfp-vti-vote-link',
		'ajax_object',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'link_click_vote_' . admin_url( 'admin-ajax.php' ) ),
		)
	);
}

// Prepara los hooks para grabar con ajax.
add_action( 'wp_ajax_vti_idea_vote', 'kfp_vti_idea_vote' );
add_action( 'wp_ajax_nopriv_vti_idea_vote', 'kfp_vti_idea_vote' );
/**
 * Crear un nuevo voto para la idea y devuelve el número de votos actual
 *
 * @return void
 */
function kfp_vti_idea_vote() {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_POST['nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
			'link_click_vote_' . admin_url( 'admin-ajax.php' )
		)
		) {
		// Crea un CPT de tipo Voto con la IP del visitante actual.
		$idea_id         = filter_input( INPUT_POST, 'idea_id', FILTER_SANITIZE_NUMBER_INT );
		$user_ip_address = isset( $_SERVER['REMOTE_ADDR'] )
			? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
			: '0.0.0.0';
		$args            = array(
			'post_title'     => 'kfp-vti-title',
			'post_content'   => $user_ip_address,
			'post_type'      => 'vti_vote',
			'post_status'    => 'publish',
			'post_parent'    => $idea_id,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		);
		// Esta variable $vote_id contiene el ID del nuevo registro
		// Nos vendría de perlas para grabar los metadatas.
		$vote_id = wp_insert_post( $args );
		// Devuelve el número actual de votos.
		$query       = new WP_Query(
			array(
				'post_type'   => 'vti_vote',
				'post_parent' => $idea_id,
			)
		);
		$count_votes = $query->found_posts;
		$html        = (int) $count_votes . ' votos - Voté';
		echo $html;
		die();
	} else {
		echo '-1';
		die( 'Error de seguridad' );
	}
}
