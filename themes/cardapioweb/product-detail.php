<?php $this->layout('theme', ['head'=> $head]); ?>

<article class="post__page">
    <header class="post__header">
        <div class="post__hero">
            <div class="post__title__container">
                <h1><?= $prod->title; ?></h1>
                <span class="post__date"><?= date_fmt($prod->post_at, "d/m/Y"); ?> | </span>
                <span class="post__category">categoria <?= $prod->category()->title; ?> | </span>
                <span class="post__views">visualizações <?= $prod->views; ?> </span>
            </div>

            <img class="post__cover" alt="<?= $prod->title; ?>" title="<?= $prod->title; ?>"
                 src="<?= image($prod->cover, 1280); ?>"/>

        </div>
        <!--==================== BG POST COVER ==================== -->
        <img src="<?= theme("assets/images/illustrations/leaf-pink.png") ?>" alt="home imagem" class="post__leaf-1">
        <img src="<?= theme("assets/images/illustrations/leaf-yellow.png") ?>" alt="home imagem" class="post__leaf-2">
    </header>

    <div class="post_page_content">
        <div class="product__price">
            R$ <?= str_price($prod->price); ?> cento.
        </div>

        <div class="htmlchars">
            <h2><?= $prod->subtitle; ?></h2>
            <?= html_entity_decode($prod->content); ?>
        </div>

        <aside class="social_share">
            <hr/>
        </aside>
    </div>

    <?php if (!empty($related)): ?>
        <div class="post__related content">
            <section>
                <header class="post__related__header">
                    <h4>Veja também:</h4>
                    <p>Confira mais artigos relacionados e tenha ainda mais dicas.</p>
                </header>

                <div class="blog__container container grid">
                    <?php foreach ($related as $prd): ?>
                        <?php $this->insert("product-card", ["prod" => $prd]); ?>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    <?php endif; ?>
</article>
