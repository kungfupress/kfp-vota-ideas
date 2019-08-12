<?php
/**
 * Plugin Name: KFP Vota Ideas
 * Plugin Author: KungFuPress
 */

defined( 'ABSPATH' ) or die();


// Crea los CPT al activar el plugin
add_action('init', 'kfp_cpt_ideas', 10);
add_action('init', 'kfp_cpt_votos', 15);

// Inlcuye los ficheros necesarios
$ruta_plugin = plugin_dir_path(__FILE__);
include_once($ruta_plugin . "include/form-propuesta-idea.php");
 
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
