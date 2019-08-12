<?php

defined( 'ABSPATH' ) or die();

// Crea el shortcode para mostrar el formulario de propuesta de ideas
add_shortcode('kfp_vti_form_idea', 'Kfp_Vti_Form_idea');
// Agrega los action hooks para grabar el formulario (el primero para usuarios 
// logeados y el otro para el resto)
// Lo que viene tras admin_post_nopriv_ tiene que coincidir con el value 
// del campo input con name "action" del formulario
add_action("admin_post_kfp-vti-grabar-idea", "Kfp_Vti_Grabar_idea");
add_action("admin_post_nopriv_kfp-vti-grabar-idea", "Kfp_Vti_Grabar_idea");

/**
 * Muestra el formulario para proponer ideas desde el frontend
 *
 * @return void
 */
function Kfp_Vti_Form_idea()
{
    global $wpdb;
    if (isset($_GET['kfp_vti_texto_aviso'])) {
        echo "<h4>" . $_GET['kfp_vti_texto_aviso'] . "</h4>";
    }
    ob_start();
    ?>
    <form name="idea"action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
        method="post" id="kfp-vti-form-grabar-idea">
        <?php wp_nonce_field('kfp-vti-form', 'kfp-vti-form-nonce'); ?>
        <input type="hidden" name="action" value="kfp-vti-grabar-idea">
        <input type="hidden" name="kfp-url-origen" 
            value="<?php echo home_url( add_query_arg(array())); ?>"
        <p>
            <label for="kfp-vti-title">Idea</label>
            <input type="text" name="kfp-vti-title" id="kfp-vti-title" 
                placeholder="Pon un título breve pero descriptivo a tu idea">
        </p>
        <p>
            <label for="kfp-vti-content">Descripción</label>
            <textarea name="kfp-vti-content" id="kfp-vti-content" 
                placeholder="Aquí puedes explicar mejor tu idea"></textarea>
        </p>
        <p>
            <input type="submit" name="kfp-vti-submit" value="Enviar idea">
        </p>
    </form>
    <?php
    return ob_get_clean();
}

/**
 * Procesa el formulario para proponer ideas desde el frontend
 *
 * @return void
 */
function Kfp_Vti_Grabar_idea()
{
    if(empty($_POST['kfp-vti-title']) || empty($_POST['kfp-vti-content'])
        || !wp_verify_nonce($_POST['kfp-vti-form-nonce'], 'kfp-vti-form')) {
        $url_origen = $_POST['kfp-url-origen'];
        $aviso = "error";
        $texto_aviso = "Por favor, rellena los contenidos requeridos del formulario";
        wp_redirect(
            esc_url_raw(
                add_query_arg(
                    array(
                        'kfp_vti_aviso' => $aviso,
                        'kfp_vti_texto_aviso' => $texto_aviso,
                    ),
                    $url_origen
                ) 
            ) 
        );
        exit();
    }

    $args = array(
        'post_title' => $_POST['kfp-vti-title'],
        'post_content' => $_POST['kfp-vti-content'],
        'post_type' => 'idea',
        'post_status' => 'publish',
        'comment_status' => 'closed',
        'ping_status' => 'closed'
    );

    $post_id = wp_insert_post($args);

    $url_origen = $_POST['kfp-url-origen'];
    $aviso = "success";
    $texto_aviso = "Has registrado tu idea correctamente. ¡Gracias!";
    wp_redirect(
        esc_url_raw(
            add_query_arg(
                array(
                    'kfp_vti_aviso' => $aviso,
                    'kfp_vti_texto_aviso' => $texto_aviso,
                ),
                $url_origen
            ) 
        ) 
    );
    exit();
}