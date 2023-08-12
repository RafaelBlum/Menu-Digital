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

    <style>

    </style>

    <div class="dash_content_app_box">
        <section>
            <div class="app_blog_home">
                <?php if (!$products): ?>
                    <div class="message info icon-info">Ainda não existem produtos cadastrados no cardápio.</div>
                <?php else: ?>
                    <?php foreach ($products as $product):
                        $productCover = ($product->cover ? image($product->cover, 300) : CONF_IMAGE_DEFAULT);
                        ?>

                        <!--======= CONTAINER PRODUCTS LIST =======-->
                        <article class="container__prod">
                            <!--======= THUMBNAIL =======-->
                            <div class="thumbnail__prod">
                                <figure class="thumbnail__img">
                                    <img src="<?= $productCover ?>">
                                </figure>
                                <div class="thumbnail__title">
                                    <?= $product->title; ?>
                                </div>
                                <a class="thumbnail__title__access" target="_blank" href="<?= url("/produtos/{$product->uri}"); ?>">
                                    <span class="thumbnail__text"><?= $product->title; ?></span>
                                </a>
                                <div class="thumbnail__title__data">
                                    <div class="thumbnail__data__text">
                                        <div class="data__text"><?= $product->title; ?></div>

                                        <ul class="data__container__li">
                                            <li class="data__li">
                                                <a class="data__pointer data__btn data__btn__5 data__btn__y" href="<?= url("/admin/products/product/{$product->id}"); ?>">
                                                    <i class="ri-edit-2-line"></i>
                                                </a>
                                            </li>

                                            <li class="data__li">
                                                <a class="data__btn data__btn__y data__btn__5 like-8m3" title="Remover produto" href="#"
                                                   data-post="<?= url("/admin/products/product"); ?>"
                                                   data-action="delete"
                                                   data-confirm="Tem certeza que deseja deletar esse post?"
                                                   data-product_id="<?= $product->id; ?>">
                                                    <i class="ri-delete-bin-2-fill"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!--======= CONTENTS =======-->
                            <div class="content__prod">
                                <div class="content__data">
                                    <a class="data__category" href="<?= url("/admin/products/categories") ?>">
                                        <img class="data__thumb" width="24" height="24" src="<?= $productCover ?>">
                                        <span class="data__cat__title"><?= $product->category()->title; ?></span>
                                    </a> <a class="data__status" href="#">

                                        <?php if($product->status == "post"): ?>
                                            <span class="badge__sm badge__xl">Publicado</span>
                                        <?php elseif ($product->status == "draft"): ?>
                                            <span class="badge__rc badge__xl">Rascunho</span>
                                        <?php else: ?>
                                            <span class="badge__vm badge__xl">Lixo</span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="content__view">
                                    <div class="content__div">
                                        <i class="ri-eye-line"></i>
                                        <span class="col-b1g font-weight-wsy"><?= $product->views; ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>