<?php

// Crea el shortcode para mostrar el formulario de propuesta de ideas
add_shortcode('kfp_form_propuesta_idea', 'Kfp_Form_Propuesta_idea');


function Kfp_Form_Propuesta_idea()
{
    global $wpdb;

    ob_start();
    ?>
    <form name="idea"action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
        method="post" id="kfp-vota-ideas-form-grabar">
        <input type="hidden" name="action" value="kfp-vota-ideas-grabar">
    <p>
        <label for="titulo">Idea</label>
        <input type="text" name="titulo" id="titulo" 
            default="Pon un título breve pero descriptivo a tu idea">
    </p>
    <p>
        <label for="contenido">Descripción</label>
        <textarea name="contenido" id="contenido" 
            default="Aquí puedes explicar mejor tu idea">
        </textarea>
    </p>
    <p>
        <input type="submit" name="submit" value="Enviar idea">
    </p>
    </form>
    <?php
    return ob_get_clean();
}
