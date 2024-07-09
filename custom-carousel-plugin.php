<?php
/**
 * Plugin Name: Carossel Customizado
 * Description: Plugin para criar múltiplos carrosséis com imagens, links, nomes e resumos, gerenciados através de uma interface administrativa.
 * Version: 1.0
 * Author: Lucas Reis
 * Text Domain: carrossel-cistomizado-Devos
 */

if ( !defined('ABSPATH') ) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/admin-interface.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/carousel-scripts.php';

function ccp_activate() {
}
register_activation_hook(__FILE__, 'ccp_activate');

function ccp_deactivate() {
}
register_deactivation_hook(__FILE__, 'ccp_deactivate');
?>
