<?php
/** 
 * File: includde/create-tables.php 
 */

defined( 'ABSPATH' ) or die();

/**
 * Crea las tablas necesarias durante la activación del plugin
 *
 * @return void
 */
function Kfp_Vti_Create_tables()
{
    global $wpdb;
    $sql = array();
    $vote_table = $wpdb->prefix . 'vti_vote';
    $charset_collate = $wpdb->get_charset_collate();
    
    // Consulta para crear las tablas
    // Mas adelante utiliza dbDelta, si la tabla ya existe no la crea sino que la
    // modifica con los posibles cambios y sin pérdida de datos
    $sql[] = "CREATE TABLE $vote_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip varchar(100) NOT NULL,
        id_user mediumint(9),
        id_post mediumint(9),
        PRIMARY KEY (id)
        ) $charset_collate";
    
    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}