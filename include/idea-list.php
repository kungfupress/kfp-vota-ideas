<?php
/** 
 * File: include/idea-list.php 
 */

defined( 'ABSPATH' ) or die();

// Crea el shortcode para mostrar el formulario de propuesta de ideas
add_shortcode('kfp_vti_idea_list', 'Kfp_Vti_Idea_list');

/**
 * Muestra en el frontend una lista de las ideas publicadas
 * Desde esta lista se pueden votar las ideas
 *
 * @return void
 */
function Kfp_Vti_Idea_list()
{
    $args = array('post_type' => 'idea', 'posts_per_page' => 30);
    $the_query = new WP_Query( $args ); 
    if ($the_query->have_posts()) { 
        $html = '<table>';
        while ($the_query->have_posts()) { 
            $the_query->the_post();
            $html .= '<tr><td><b>'. get_the_title() . '</b>'; // Importante lo del get_
            $html .= '<br>' . get_the_content() .'</td>';
            $html .= '<td nowrap>5 votos - <a href="">Votar</a></td></tr>';
            wp_reset_postdata(); // Esto es para no fastidiar el loop principal del sitio
        }
        $html .= '</table>';
    } else { 
        $html = '<p>' . _e( 'De momento a nadie se le he ocurrido ninguna idea. ¿Tienes alguna?' ) . '</p>';
    }
    
    return $html; // No hace echo o print desde un shortcode, usar return 
}