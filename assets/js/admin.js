jQuery(document).ready(function($){
    $('#upload_image_button').click(function(e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Selecionar Imagem',
            multiple: false
        }).open()
        .on('select', function() {
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            $('#item_image').val(image_url);
            $('#item_image_preview').attr('src', image_url).show();
        });
    });

    $('#edit_upload_image_button').click(function(e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Selecionar Imagem',
            multiple: false
        }).open()
        .on('select', function() {
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            $('#edit_item_image').val(image_url);
            $('#edit_item_image_preview').attr('src', image_url).show();
        });
    });

    $(document).on('click', '.edit-item-button', function() {
        var itemIndex = $(this).data('item-index');
        var itemName = $(this).data('item-name');
        var itemImage = $(this).data('item-image');
        var itemLink = $(this).data('item-link');
        var itemSummary = $(this).data('item-summary');

        $('#edit_item_index').val(itemIndex);
        $('#edit_item_name').val(itemName);
        $('#edit_item_image').val(itemImage);
        $('#edit_item_image_preview').attr('src', itemImage).show();
        $('#edit_item_link').val(itemLink);
        $('#edit_item_summary').val(itemSummary);
        $('#editItemModal').modal('show');
    });
});
