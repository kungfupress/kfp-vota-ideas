<?php
/**
 * Plugin Name: KFP Vota Ideas
 * Plugin Author: KungFuPress
 */

// Crea los CPT al activar el plugin
add_action('init', 'kfp_cpt_ideas', 10);
add_action('init', 'kfp_cpt_votos', 15);
 
/**
 * Crea el CPT Ideas
 *
 * @return void
 */
function kfp_cpt_ideas()
{
    $args = array(
        'public' => true,
        'label'  => 'Ideas'
      );
      register_post_type('idea', $args);
}

/**
 * Crea el CPT Votos
 * 
 * @return void
 */
function kfp_cpt_votos()
{
    $args = array(
        'public' => true,
        'label' => 'Votos',
    );
    register_post_type('voto', $args);
}
