<?php
/** 
 * File: include/idea-list.php 
 * Reference: https://toolset.com/forums/topic/increment-count-when-link-is-clicked/
 */

defined( 'ABSPATH' ) or die();

// Crea el shortcode para mostrar el listado de ideas
add_shortcode('kfp_vti_idea_list', 'Kfp_Vti_Idea_list');

// Prepara los hooks para grabar con ajax
add_action('wp_ajax_vti_idea_vote', 'Kfp_Vti_Idea_vote');
add_action('wp_ajax_nopriv_vti_idea_vote', 'Kfp_Vti_Idea_vote');
function Kfp_Vti_Idea_vote() {
    if ( defined('DOING_AJAX') && DOING_AJAX 
        && wp_verify_nonce($_POST['nonce'], 'link_click_vote_' . admin_url( 'admin-ajax.php'))) {
        // Crea un CPT de tipo Voto con la IP del visitante actual
        $args = array(
            'post_title' => 'kfp-vti-title',
            'post_content' => $_SERVER['REMOTE_ADDR'],
            'post_type' => 'vti_vote',
            'post_status' => 'publish',
            'post_parent' => filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT),
            'comment_status' => 'closed',
            'ping_status' => 'closed'
        );
    
        // Esta variable $post_id contiene el ID del nuevo registro 
        // Nos vendría de perlas para grabar los metadatas
        $post_id = wp_insert_post($args);
        // Devuelve un token para saber que se ha grabado el voto
        echo "pass";
        die();
    } else {
        die('Error de seguridad');
    }
}

// carga y 'localiza' el script ¿para introducir el nonce?
add_action( 'wp_enqueue_scripts', 'ajax_vote_link_enqueue_scripts' );
function ajax_vote_link_enqueue_scripts() 
{
    wp_enqueue_script('vote-link', plugins_url('../js/vote-link.js', __FILE__));
    wp_localize_script('vote-link', 'votelink', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('link_click_vote_' . admin_url('admin-ajax.php')),
    ));
}

/**
 * Muestra en el frontend una lista de las ideas publicadas
 * Desde esta lista es desde donde se pueden votar las ideas
 *
 * @return void
 */
function Kfp_Vti_Idea_list()
{
    $args = array('post_type' => 'vti_idea', 'posts_per_page' => 30);
    $the_query = new WP_Query( $args ); 
    if ($the_query->have_posts()) { 
        $html = '<table>';
        while ($the_query->have_posts()) { 
            $the_query->the_post();
            $html .= '<tr><td><b>'. get_the_title() . '</b>'; // Importante lo del get_
            $html .= '<br>' . get_the_content() .'</td>';
            $html .= '<td nowrap>5 votos - <a href="#" class="click-vote-link"';
            $html .= 'data-idea-id="' . get_the_ID() . '">Votar</a></td></tr>';
        }
        $html .= '</table>';
    } else { 
        $html = '<p>' . _e( 'De momento a nadie se le he ocurrido ninguna idea. ¿Tienes alguna?' ) . '</p>';
    }
    
    return $html; // No hace echo o print desde un shortcode, usar return 
}