<?php
/** 
 * File: include/idea-list.php 
 * Reference: https://toolset.com/forums/topic/increment-count-when-link-is-clicked/
 */

defined( 'ABSPATH' ) or die();

// Crea el shortcode para mostrar el listado de ideas
add_shortcode('kfp_vti_idea_list', 'Kfp_Vti_Idea_list');
function Kfp_Vti_Idea_list()
{
    global $wpdb;
    wp_enqueue_script('kfp-vti-vote-link');
    $args = array(
        'post_type' => 'vti_idea', 
        'posts_per_page' => 30
    );
    // This query get the idea's list
    $the_query = new WP_Query( $args ); 
    if ($the_query->have_posts()) { 
        $html = '<table id="tbl"><tbody>';
        while ($the_query->have_posts()) { 
            $the_query->the_post();
            $post_id = get_the_ID();
            $where = $wpdb->prepare('WHERE post_parent = %d', $post_id);
            $votes = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} {$where}");
            
            $html .= '<tr><td>' . get_the_ID() . ' - <b>'. get_the_title() . '</b>'; // Importante lo del get_
            $html .= '<br>' . get_the_content() .'</td>';
            $html .= '<td>' . $votes . ' votos - <a href="#" class="vote"';
            $html .= 'data-idea-id="' . get_the_ID() . '">Votar</a></td></tr>';
        }
        $html .= '</tbody></table>';
    } else { 
        $html = '<p>' . _e( 'De momento a nadie se le he ocurrido ninguna idea. ¿Tienes alguna?' ) . '</p>';
    }
    
    return $html; // No hace echo o print desde un shortcode, usar return 
}

add_action( 'wp_enqueue_scripts', 'kfp_Vti_Idea_List_scripts' );
function Kfp_Vti_Idea_List_scripts() 
{
    wp_register_script('kfp-vti-vote-link', plugins_url('../js/vote-link.js', __FILE__), array('jquery'));
    wp_localize_script('kfp-vti-vote-link', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('link_click_vote_' . admin_url('admin-ajax.php')),
    )); 
}

// Prepara los hooks para grabar con ajax
add_action('wp_ajax_vti_idea_vote', 'Kfp_Vti_Idea_vote');
add_action('wp_ajax_nopriv_vti_idea_vote', 'Kfp_Vti_Idea_vote');
function Kfp_Vti_Idea_vote() {
    if ( defined('DOING_AJAX') && DOING_AJAX 
        && wp_verify_nonce($_POST['nonce'], 'link_click_vote_' . admin_url( 'admin-ajax.php'))) {
        // Crea un CPT de tipo Voto con la IP del visitante actual
        $idea_id = filter_input(INPUT_POST, 'idea_id', FILTER_SANITIZE_NUMBER_INT);
        $args = array(
            'post_title' => 'kfp-vti-title',
            'post_content' => $_SERVER['REMOTE_ADDR'],
            'post_type' => 'vti_vote',
            'post_status' => 'publish',
            'post_parent' => $idea_id,
            'comment_status' => 'closed',
            'ping_status' => 'closed'
        );
        // Esta variable $vote_id contiene el ID del nuevo registro 
        // Nos vendría de perlas para grabar los metadatas
        $vote_id = wp_insert_post($args);
        // Devuelve un token para saber que se ha grabado el voto
        $query = new WP_Query(array(
            'post_type' => 'vti_vote',
            'post_parent' => $idea_id
        ));
        $count_votes = $query->found_posts;
        $html = "$count_votes votos - Voté";
        echo $html;
        die();
    } else {
        die('Error de seguridad');
    }
}