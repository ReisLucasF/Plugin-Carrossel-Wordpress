# Carossel Customizado Plugin

Este plugin permite a criação de múltiplos carrosséis utilizando Bootstrap e Owl Carousel. Ele possui uma interface visual intuitiva que permite a adição de vários carrosséis, gerando um shortcode único para cada um. Você pode incluir o shortcode diretamente no corpo do site.

## Funcionalidades

- Criação de múltiplos carrosséis.
- Interface visual para fácil adição e configuração de carrosséis.
- Utiliza Bootstrap e Owl Carousel para uma experiência responsiva e atraente.
- Gera shortcodes únicos para cada carrossel.

## Requisitos

- WordPress 5.0 ou superior
- Bootstrap 4.0 ou superior
- Owl Carousel 2.0 ou superior

## Instalação

1. Baixe o plugin.
3. No painel administrativo do WordPress, vá para `Plugins`, adicione e ative o `Carossel Customizado`.

## Uso

### Adicionando um novo carrossel

1. No painel administrativo do WordPress, vá para `Carrosséis` escolha o nome do seu novo carrossel e clique em `Adicionar Carrossel`.
   <br> <br>
 ![Adição de carrossel](imagens/toggle1.png)

3. Selecione `editar` no carrossel criado, em seguida selecione `adicionar item`:
   ![Adição de carrossel](imagens/adc%20carrossel.png)
   ![Adição de carrossel](imagens/adccarrossel2.png)
    - Adicione as imagens, titulo, descrição e link de destino do conteúdo desejado.
4. Salve o carrossel.
![Adição de carrossel](imagens/itemadc.png)
5. Um shortcode único será gerado para o carrossel na página inicial. Copie este shortcode.

### Inserindo o carrossel no site

1. Vá para a página ou post onde deseja adicionar o carrossel.
2. Cole o shortcode gerado no local desejado.
3. Atualize a página ou post.



<br/><br/><br/><br/><br/><br/><br/>











# Tutorial de criação

# Consumindo um Custom Post Type (CPT) no WordPress

Este documento explica como consumir um CPT já criado no WordPress, recuperando os dados do banco e exibindo no frontend através de um shortcode.

## 1. Como WordPress Armazena os CPTs
Os Custom Post Types (CPTs) são armazenados na tabela `wp_posts`, com o campo `post_type` especificando o tipo.
Os campos personalizados (Custom Fields) ficam na tabela `wp_postmeta`.

## 2. Criando a Consulta ao CPT
Usamos a classe `WP_Query` para buscar os posts do tipo desejado. Aqui está um exemplo consumindo um CPT chamado `evento`:

```php
// Função para exibir eventos via shortcode
function exibir_eventos_shortcode() {
    ob_start(); // Inicia a captura de saída

    // Configuração da consulta
    $args = array(
        'post_type'      => 'evento', // Nome do CPT
        'posts_per_page' => 10, // Limita a 10 posts
        'order'          => 'DESC', // Ordena do mais recente para o mais antigo
        'orderby'        => 'date' // Ordenação por data
    );

    $eventos_query = new WP_Query($args); // Executa a consulta

    if ($eventos_query->have_posts()) :
        echo '<div class="eventos-container">';
        while ($eventos_query->have_posts()) : $eventos_query->the_post();
            
            $titulo = get_the_title(); // Obtém o título do post
            $conteudo = get_the_content(); // Obtém o conteúdo
            $imagem = get_the_post_thumbnail_url(); // Obtém a URL da imagem destacada
            $data_evento = get_field('data_evento'); // Obtém um campo personalizado (ACF)
            
            echo '<div class="evento">';
            if ($imagem) {
                echo '<img src="' . esc_url($imagem) . '" alt="' . esc_attr($titulo) . '" />';
            }
            echo '<h2>' . esc_html($titulo) . '</h2>';
            if ($data_evento) {
                echo '<p><strong>Data do Evento:</strong> ' . esc_html($data_evento) . '</p>';
            }
            echo '<p>' . esc_html(wp_trim_words($conteudo, 30, '...')) . '</p>';
            echo '</div>';
        endwhile;
        echo '</div>'; // Fecha container

        wp_reset_postdata(); // Restaura o loop global
    else:
        echo '<p>Nenhum evento encontrado.</p>';
    endif;

    return ob_get_clean(); // Retorna o conteúdo formatado
}
add_shortcode('exibir_eventos', 'exibir_eventos_shortcode');
```

## 3. Explicação do Código
1. **Consulta ao banco**: `WP_Query` busca os posts do CPT `evento`.
2. **Loop pelos resultados**: Verifica se há eventos e os exibe.
3. **Uso de funções do WordPress**:
   - `get_the_title()`: Obtém o título do post.
   - `get_the_content()`: Obtém o conteúdo do post.
   - `get_the_post_thumbnail_url()`: Obtém a URL da imagem destacada.
   - `get_field('data_evento')`: Obtém um campo personalizado (usando ACF).
   - `wp_trim_words()`: Reduz o texto para 30 palavras com reticências.
   - `wp_reset_postdata()`: Reseta a consulta após o loop.
4. **Retorno via `ob_get_clean()`**: Permite que o conteúdo seja inserido onde o shortcode for usado.

## 4. Exibindo no Frontend
Para exibir os eventos, basta adicionar o shortcode no editor do WordPress ou no código PHP:

**No editor WordPress:**
```markdown
[exibir_eventos]
```

**No código PHP:**
```php
echo do_shortcode('[exibir_eventos]');
```

## Conclusão
Este método permite consumir qualquer CPT existente no WordPress e exibi-lo de forma dinâmica. Basta alterar o `post_type` e os campos personalizados conforme necessário.

