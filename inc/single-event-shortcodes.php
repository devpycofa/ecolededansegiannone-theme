<?php

add_shortcode('event_title', 'display_event_title');
function display_event_title()
{
    ob_start();
?>
    <h1 class="single-event__title">
        <span><?php esc_html_e(get_field('titres_et_textes')['titre_dentete']['premiere_ligne']); ?></span>
        <span><?php esc_html_e(get_field('titres_et_textes')['titre_dentete']['deuxieme_ligne']); ?></span>
    </h1>
<?php
    wp_reset_postdata();
    return ob_get_clean();
}
?>