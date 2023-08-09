<article class="container product__card">
    <!--=============== CARD POST ===============-->
    <div class="product__thumb">
        <h3>
            <a title="<?= $prod->category()->title; ?>" href="<?= url("/produtos/em/{$prod->category()->uri}") ?>">
                <?= $prod->category()->title; ?>
            </a>
        </h3>
        <img src="<?= image($prod->cover, 500, 300) ?>" alt="<?= str_limit_chars($prod->title, 65); ?>"/>
    </div>

    <div class="product__content">
        <header class="content__header">
            <div class="row-wrapper">
                <h2 class="product__title">
                    <a href="<?= url("/produtos/{$prod->uri}") ?>" title="<?= str_limit_chars($prod->title, 30); ?>">
                        <?= str_limit_chars($prod->title, 65); ?>
                    </a>
                </h2>
            </div>
            <?php if($isPrice->view_price == 'active'): ?>
            <div class="product__details">
                R$  <h3><?= str_price($prod->price); ?></h3>
            </div>
            <?php endif; ?>
        </header>
        <p class="product__description">
            <?= str_limit_chars($prod->subtitle, 80); ?>
        </p>

        <footer class="product__button">
            <a href="<?= url("/produtos/{$prod->uri}") ?>">Ver produto</a>
        </footer>

        <img src="<?= theme("assets/images/illustrations/leaf-cyan-2.png") ?>" alt="leaf" class="product_leaf-1">
        <img src="<?= theme("assets/images/illustrations/leaf-yellow.png") ?>" alt="leaf" class="product_leaf-2">
    </div>
</article>
