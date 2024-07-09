<?php
function ccp_add_admin_menu() {
    add_menu_page(
        'Carrosséis', // Título da página
        'Carrosséis', // Título do menu
        'manage_options', // Capacidade
        'ccp_carousels', // Slug do menu
        'ccp_carousels_page', // Função que exibe a página
        'dashicons-images-alt2', // Ícone do menu
        20 // Posição do menu
    );
}
add_action('admin_menu', 'ccp_add_admin_menu');

function ccp_enqueue_admin_scripts() {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', ['jquery'], null, true);
    wp_enqueue_media();
    wp_enqueue_script('ccp-admin-js', plugins_url('../assets/js/admin.js', __FILE__), ['jquery'], null, true);
}
add_action('admin_enqueue_scripts', 'ccp_enqueue_admin_scripts');

function ccp_carousels_page() {
    if (isset($_POST['ccp_submit'])) {
        $carousels = get_option('ccp_carousels', []);
        $new_carousel = [
            'title' => sanitize_text_field($_POST['carousel_title']),
            'items' => []
        ];
        $carousels[] = $new_carousel;
        update_option('ccp_carousels', $carousels);
    }

    $carousels = get_option('ccp_carousels', []);
    ?>
    <div class="wrap">
        <h1>Gerenciar Carrosséis</h1>
        <form method="post" action="" class="mb-4">
            <h2>Adicionar Novo Carrossel</h2>
            <div class="form-group">
                <label for="carousel_title">Título do Carrossel</label>
                <input name="carousel_title" type="text" id="carousel_title" value="" class="form-control">
            </div>
            <?php submit_button('Adicionar Carrossel', 'primary', 'ccp_submit'); ?>
        </form>
        
        <h2>Lista de Carrosséis</h2>
        <ul class="list-group">
            <?php foreach ($carousels as $index => $carousel) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo esc_html($carousel['title']); ?></strong>
                        <p><code>[carousel id="<?php echo esc_html($index); ?>"]</code></p>
                    </div>
                    <div>
                        <a href="<?php echo admin_url('admin.php?page=ccp_edit_carousel&carousel=' . $index); ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="<?php echo admin_url('admin.php?page=ccp_delete_carousel&carousel=' . $index); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este carrossel?');">Excluir</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

// edição de carrossel
function ccp_edit_carousel_page() {
    if (!isset($_GET['carousel'])) {
        return;
    }
    $carousel_index = intval($_GET['carousel']);
    $carousels = get_option('ccp_carousels', []);
    if (!isset($carousels[$carousel_index])) {
        return;
    }
    $carousel = $carousels[$carousel_index];

    if (isset($_POST['ccp_edit_submit'])) {
        $carousel['title'] = sanitize_text_field($_POST['carousel_title']);
        $carousels[$carousel_index] = $carousel;
        update_option('ccp_carousels', $carousels);
    }

    if (isset($_POST['ccp_add_item'])) {
        $new_item = [
            'name' => sanitize_text_field($_POST['item_name']),
            'image' => esc_url_raw($_POST['item_image']),
            'link' => esc_url_raw($_POST['item_link']),
            'summary' => sanitize_textarea_field($_POST['item_summary'])
        ];
        $carousel['items'][] = $new_item;
        $carousels[$carousel_index] = $carousel;
        update_option('ccp_carousels', $carousels);
    }

    if (isset($_POST['ccp_edit_item'])) {
        $item_index = intval($_POST['item_index']);
        if (isset($carousel['items'][$item_index])) {
            $carousel['items'][$item_index] = [
                'name' => sanitize_text_field($_POST['item_name']),
                'image' => esc_url_raw($_POST['item_image']),
                'link' => esc_url_raw($_POST['item_link']),
                'summary' => sanitize_textarea_field($_POST['item_summary'])
            ];
            $carousels[$carousel_index] = $carousel;
            update_option('ccp_carousels', $carousels);
        }
    }

    if (isset($_GET['remove_item'])) {
        $item_index = intval($_GET['remove_item']);
        if (isset($carousel['items'][$item_index])) {
            unset($carousel['items'][$item_index]);
            $carousels[$carousel_index] = $carousel;
            update_option('ccp_carousels', $carousels);
        }
    }

    ?>
    <div class="wrap">
        <h1>Editar Carrossel</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="carousel_title">Título do Carrossel</label>
                <input name="carousel_title" type="text" id="carousel_title" value="<?php echo esc_attr($carousel['title']); ?>" class="form-control">
            </div>
            <?php submit_button('Salvar Alterações', 'primary', 'ccp_edit_submit'); ?>
        </form>

        <h2>Itens do Carrossel</h2>
        <button type="button" class="btn btn-secondary mb-4" data-toggle="modal" data-target="#addItemModal">Adicionar Item</button>

        <!-- Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Adicionar Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="item_name">Nome do Item</label>
                                <input name="item_name" type="text" id="item_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="item_image">Imagem do Item</label>
                                <input name="item_image" type="hidden" id="item_image" class="form-control">
                                <button type="button" class="btn btn-secondary" id="upload_image_button">Selecionar Imagem</button>
                                <img id="item_image_preview" src="" style="max-width: 150px; margin-top: 10px; display: none;">
                            </div>
                            <div class="form-group">
                                <label for="item_link">Link</label>
                                <input name="item_link" type="text" id="item_link" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="item_summary">Resumo</label>
                                <textarea name="item_summary" id="item_summary" class="form-control"></textarea>
                            </div>
                            <button type="submit" name="ccp_add_item" class="btn btn-primary">Adicionar Item</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal  -->
        <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Editar Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <input type="hidden" name="item_index" id="edit_item_index">
                            <div class="form-group">
                                <label for="edit_item_name">Nome do Item</label>
                                <input name="item_name" type="text" id="edit_item_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="edit_item_image">Imagem do Item</label>
                                <input name="item_image" type="hidden" id="edit_item_image" class="form-control">
                                <button type="button" class="btn btn-secondary" id="edit_upload_image_button">Selecionar Imagem</button>
                                <img id="edit_item_image_preview" src="" style="max-width: 150px; margin-top: 10px; display: none;">
                            </div>
                            <div class="form-group">
                                <label for="edit_item_link">Link</label>
                                <input name="item_link" type="text" id="edit_item_link" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="edit_item_summary">Resumo</label>
                                <textarea name="item_summary" id="edit_item_summary" class="form-control"></textarea>
                            </div>
                            <button type="submit" name="ccp_edit_item" class="btn btn-primary">Salvar Alterações</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <ul class="list-group">
            <?php foreach ($carousel['items'] as $item_index => $item) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo esc_html($item['name']); ?></strong>
                        <p><?php echo esc_html($item['summary']); ?></p>
                        <img src="<?php echo esc_url($item['image']); ?>" style="max-width: 100px;">
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-sm edit-item-button" data-item-index="<?php echo $item_index; ?>" data-item-name="<?php echo esc_html($item['name']); ?>" data-item-image="<?php echo esc_url($item['image']); ?>" data-item-link="<?php echo esc_html($item['link']); ?>" data-item-summary="<?php echo esc_html($item['summary']); ?>">Editar</button>
                        <a href="<?php echo admin_url('admin.php?page=ccp_edit_carousel&carousel=' . $carousel_index . '&remove_item=' . $item_index); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este item?');">Remover</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}
add_action('admin_menu', function() {
    add_submenu_page(null, 'Editar Carrossel', 'Editar Carrossel', 'manage_options', 'ccp_edit_carousel', 'ccp_edit_carousel_page');
});

function ccp_delete_carousel_page() {
    if (!isset($_GET['carousel'])) {
        return;
    }
    $carousel_index = intval($_GET['carousel']);
    $carousels = get_option('ccp_carousels', []);
    if (isset($carousels[$carousel_index])) {
        unset($carousels[$carousel_index]);
        update_option('ccp_carousels', $carousels);
    }
    wp_redirect(admin_url('admin.php?page=ccp_carousels'));
    exit;
}
add_action('admin_menu', function() {
    add_submenu_page(null, 'Excluir Carrossel', 'Excluir Carrossel', 'manage_options', 'ccp_delete_carousel', 'ccp_delete_carousel_page');
});
?>
