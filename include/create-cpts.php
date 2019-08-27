<?php
/**
* File: include/create-cpts.php 
*/

defined( 'ABSPATH' ) or die();
add_action('init', 'kfp_cpt_ideas', 10);
add_action('init', 'kfp_cpt_votes', 11);

/**
 * Crea el CPT Ideas
 *
 * @return void
 */
function kfp_cpt_ideas()
{
    $args = array(
        'public' => true,
        'label'  => 'VotaIdeas'
      );
      register_post_type('vti_idea', $args);
}

/**
 * Crea el CPT Votos
 *
 * @return void
 */
function kfp_cpt_votes()
{
    $args = array(
        'public' => true,
        'label'  => 'Votos'
      );
      register_post_type('vti_vote', $args);
}