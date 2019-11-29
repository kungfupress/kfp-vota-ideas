<?php
/**
 * Plugin Name: KFP Vota Ideas
 * Plugin Author: KungFuPress
 * Description: Aplicación para que tus usuarios puedan proponer ideas y votarlas
 *
 * @package kfp-vti
 */

defined( 'ABSPATH' ) || die();
define( 'KFP_VTI_DIR', plugin_dir_path( __FILE__ ) );
define( 'KFP_VTI_VERSION', '1.2.0' );
// Crea los CPT al activar el plugin.
require_once KFP_VTI_DIR . 'include/create-cpts.php';

// Crea la tabla voto al activar el plugin
// include_once(KFP_VTI_DIR . "include/create-tables.php");
// register_activation_hook(__FILE__, 'Kfp_Vti_Create_tables');
// TODO: eliminar el fichero create-tables.php.

// Implementa los shortcodes para añadir ideas y mostrar la lista de ideas.
require_once KFP_VTI_DIR . 'include/idea-add.php';
require_once KFP_VTI_DIR . 'include/idea-list.php';

