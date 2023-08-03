<?php $this->layout("_admin", ["head" => $head, "app" => $app]); ?>
<?php $this->insert("widgets/product/sidebar", ["app"=>$app]); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-pencil-square-o">Cardápio</h2>
        <form action="<?= url("/admin/products/home"); ?>" method="post" class="app_search_form">
            <input type="text" name="s" value="<?= $search; ?>" placeholder="Pesquisar produtos:">
            <button class="icon-search icon-notext"></button>
        </form>
    </header>

    <div class="dash_content_app_box">
        <section>
            <div class="app_blog_home">
                <?php if (!$products): ?>
                    <div class="message info icon-info">Ainda não existem produtos cadastrados no cardápio.</div>
                <?php else: ?>
                    <?php foreach ($products as $product):
                        $productCover = ($product->cover ? image($product->cover, 300) : CONF_IMAGE_DEFAULT);
                        ?>
                        <article>
                            <div style="background-image: url(<?= $productCover; ?>);"
                                 class="cover embed radius"></div>
                            <h3 class="tittle">
                                <a target="_blank" href=" <?= url("/produtos/{$product->uri}"); ?>">
                                    <?php if ($product->post_at > date("Y-m-d H:i:s")): ?>
                                        <span class="icon-clock-o"><?= $product->title; ?></span>
                                    <?php else: ?>
                                        <span class="<?= ($product->status == 'post' ? 'icon-check post__green' : ($product->status == 'draft' ? 'icon-check' : 'icon-check post__red')); ?>"><?= $product->title; ?></span>
                                    <?php endif; ?>
                                </a>
                            </h3>

                            <div class="info">
                                <p class="icon-clock-o"><?= date_fmt($product->post_at, "d.m.y \à\s H\hi"); ?></p>
                                <p class="icon-bookmark"><?= $product->category()->title; ?></p>
                                <p class="icon-bar-chart"><?= $product->views; ?></p>
                                <p class="icon-pencil-square-o"><?= ($product->status == "post" ? "Publicado" : ($product->status == "draft" ? "Rascunho" : "Lixo")); ?></p>
                            </div>

                            <div class="actions">
                                <a class="icon-pencil btn btn-blue" title=""
                                   href="<?= url("/admin/products/product/{$product->id}"); ?>">Editar</a>

                                <a class="icon-trash-o btn btn-red" title="" href="#"
                                   data-post="<?= url("/admin/products/product"); ?>"
                                   data-action="delete"
                                   data-confirm="Tem certeza que deseja deletar esse post?"
                                   data-product_id="<?= $product->id; ?>">Deletar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>