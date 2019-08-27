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

// Crea la tabla voto al activar el plugin
//include_once($ruta_plugin . "include/create-tables.php");
//register_activation_hook(__FILE__, 'Kfp_Vti_Create_tables');
//TODO: eliminar el fichero create-tables.php

// Implementa los shortcodes para añadir ideas y mostrar la lista de ideas
include_once($ruta_plugin . "include/idea-add.php");
include_once($ruta_plugin . "include/idea-list.php");
 
