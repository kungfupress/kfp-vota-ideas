<?php
/**
* File: include/create-cpts.php 
*/

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