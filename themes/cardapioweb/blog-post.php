<?php $this->layout('theme', ['head'=> $head]); ?>

<article class="post__page">
    <header class="post__header">
        <div class="post__hero">
            <div class="post__title__container">
                <h1><?= $post->title; ?></h1>
                <span class="title__author">criado por <?= "{$post->author()->fullName()}"; ?> | </span>
                <span class="post__date"><?= date_fmt($post->post_at, "d/m/Y"); ?> | </span>
                <span class="post__category">categoria <?= $post->category()->title; ?> | </span>
                <span class="post__views">visualizações <?= $post->views; ?> </span>
            </div>

            <img class="post__cover" alt="<?= $post->title; ?>" title="<?= $post->title; ?>"
                 src="<?= image($post->cover, 1280); ?>"/>

        </div>
        <!--==================== BG POST COVER ==================== -->
        <img src="<?= theme("assets/images/illustrations/leaf-pink.png") ?>" alt="home imagem" class="post__leaf-1">
        <img src="<?= theme("assets/images/illustrations/leaf-yellow.png") ?>" alt="home imagem" class="post__leaf-2">
    </header>

    <div class="post_page_content">
        <div class="htmlchars">
            <h2><?= $post->subtitle; ?></h2>
            <?= html_entity_decode($post->content); ?>
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
                    <?php foreach ($related as $p): ?>
                        <?php $this->insert("blog-card", ["post" => $p]); ?>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    <?php endif; ?>
</article>
