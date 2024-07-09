<?php
function ccp_enqueue_carousel_scripts() {
    wp_enqueue_style('owl-carousel', plugins_url('assets/css/owl.carousel.min.css', __FILE__));
    wp_enqueue_style('owl-theme-default', plugins_url('assets/css/owl.theme.default.min.css', __FILE__));
    wp_enqueue_script('owl-carousel', plugins_url('assets/js/owl.carousel.min.js', __FILE__), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'ccp_enqueue_carousel_scripts');
?>
