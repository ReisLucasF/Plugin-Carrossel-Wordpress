<?php
function ccp_carousel_shortcode($atts) {
    $atts = shortcode_atts(['id' => ''], $atts, 'carousel');
    $carousel_id = intval($atts['id']);
    $carousels = get_option('ccp_carousels', []);
    
    if (!isset($carousels[$carousel_id])) {
        return 'Carrossel não encontrado.';
    }
    
    $carousel = $carousels[$carousel_id];
    ob_start();

    if (!empty($carousel['items'])) :
        echo '<div class="owl-carousel carousel-container">';

        foreach ($carousel['items'] as $item) :
            ?>
            <div class="item carousel-column">
                <a href="<?php echo esc_url($item['link']); ?>" class="carousel-link" style="text-decoration: none; color: inherit;">
                    <div class="carousel-widget-wrap">
                        <?php if ($item['image']) : ?>
                            <div class="carousel-image">
                                <div class="carousel-widget-container">
                                    <img loading="lazy" decoding="async" src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name']); ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($item['summary']) : ?>
                            <p class="summary"><?php echo esc_html($item['summary']); ?></p>
                        <?php endif; ?>
                        <div class="buy-now-button">
                            <a href="<?php echo esc_url($item['link']); ?>" class="button">Compre Agora</a>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        endforeach;

        echo '</div>';

        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>';
        echo '<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>';
        echo '<script>
            jQuery(document).ready(function($) {
                $(".owl-carousel").owlCarousel({
                    items: 3,
                    loop: true,
                    margin: 20,
                    nav: true,
                    dots: true,
                    autoplay: true,
                    center: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 3
                        }
                    }
                });
            });
        </script>';
    endif;

    return ob_get_clean();
}
add_shortcode('carousel', 'ccp_carousel_shortcode');
?>
