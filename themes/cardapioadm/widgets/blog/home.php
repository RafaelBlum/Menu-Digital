<?php $this->layout("_admin", ["head" => $head, "app" => $app]); ?>
<?php $this->insert("widgets/blog/sidebar", ["app"=>$app]); ?>

<section class="dash_content_app">

    <!--========== HEADER BLOG ==========-->
    <header class="dash_content_app_header">

        <!--========== TITLE ==========-->
        <h2 class="icon-pencil-square-o">Blog</h2>

        <!--========== SEARCH BLOG ==========-->
        <form action="<?= url("/admin/blog/home"); ?>" method="post" class="app_search_form">
            <input type="text" name="s" value="<?= $search; ?>" placeholder="Pesquisar Artigo:">
            <button class="icon-search icon-notext"></button>
        </form>
    </header>

    <!--========== SECTION CONTENT BLOG ==========-->
    <div class="dash_content_app_box">
        <section>
            <div class="app_blog_home">

                <?php if (!$posts): ?>
                    <!--========== CASE NO EXIST POSTS IN DATE BASE ==========-->
                    <div class="message info icon-info">Ainda não existem artigos cadastrados no blog.</div>
                <?php else: ?>

                    <!--========== CARDS POSTS LIST [ 12 POST FOR PAGE IN PAGINATOR ] ==========-->
                    <?php foreach ($posts as $post):
                        $postCover = ($post->cover ? image($post->cover, 300) : CONF_IMAGE_DEFAULT);
                        ?>
                        <article>
                            <!--========== IMG AND TITLE ==========-->
                            <div style="background-image: url(<?= $postCover; ?>);"
                                 class="cover embed radius"></div>
                            <h3 class="tittle">
                                <a target="_blank" href=" <?= url("/blog/{$post->uri}"); ?>">
                                    <?php if ($post->post_at > date("Y-m-d H:i:s")): ?>
                                        <span class="icon-clock-o"><?= $post->title; ?></span>
                                    <?php else: ?>
                                        <span class="icon-check"><?= $post->title; ?></span>
                                    <?php endif; ?>
                                </a>
                            </h3>

                            <!--========== INFORMATION'S ==========-->
                            <div class="info">
                                <p class="icon-clock-o"><?= date_fmt($post->post_at, "d.m.y \à\s H\hi"); ?></p>
                                <p class="icon-bookmark"><?= $post->category()->title; ?></p>
                                <p class="icon-user"><?= ($post->author() != null ? $post->author()->fullName() : "Sem editor") ?></p>
                                <p class="icon-bar-chart"><?= $post->views; ?></p>
                                <p class="icon-pencil-square-o"><?= ($post->status == "post" ? "Artigo" : ($post->status == "draft" ? "Rascunho" : "Lixo")); ?></p>
                            </div>

                            <!--========== BUTTON'S EDIT/DELETE ==========-->
                            <div class="actions">
                                <a class="icon-pencil btn btn-blue" title=""
                                   href="<?= url("/admin/blog/post/{$post->id}"); ?>">Editar</a>

                                <a class="icon-trash-o btn btn-red" title="" href="#"
                                   data-post="<?= url("/admin/blog/post"); ?>"
                                   data-action="delete"
                                   data-confirm="Tem certeza que deseja deletar esse post?"
                                   data-post_id="<?= $post->id; ?>">Deletar</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>