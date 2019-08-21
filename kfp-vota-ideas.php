<?php
/**
 * Plugin Name: KFP Vota Ideas
 * Plugin Author: KungFuPress
 * Description: Aplicación para que tus usuarios puedan proponer ideas y votarlas
 */

defined( 'ABSPATH' ) or die();
$ruta_plugin = plugin_dir_path(__FILE__);

// Crea los CPT al activar el plugin
include_once($ruta_plugin . "include/create-cpts.php");
add_action('init', 'kfp_cpt_ideas', 10);

// Crea la tabla voto al activar el plugin
include_once($ruta_plugin . "include/create-tables.php");
register_activation_hook(__FILE__, 'Kfp_Vti_Create_tables');

// Incluye los ficheros necesarios
include_once($ruta_plugin . "include/form-new-idea.php");
 
