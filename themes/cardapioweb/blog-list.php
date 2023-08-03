<?php $this->layout('theme', ['head'=> $head]); ?>

<!--========= SECTION LOAD BOX ========== -->
<?php $this->start('load'); ?>
    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>
<?php $this->stop(); ?>

<!--========= SECTION FOR STYLES ========== -->
<?php $this->start('styles'); ?>

<?php $this->stop(); ?>

<!--==================== BLOG ====================-->
<section class="blog section" id="blog">

    <!--==== FORM SEARCH ========== -->
    <header class="section__search__header">

        <span class="section__title"><?= ($title ?? "Blog"); ?></span>
        <h2 class="section__subtitle"><?= ($search ?? $desc ?? "Informativos e conteúdo") ?></h2>

        <form class="section__form" name="search" action="<?= url("/blog/buscar"); ?>" method="post" enctype="multipart/form-data">
            <label>
                <input type="text" name="s" placeholder="Encontre um artigo:" class="section__input" required/>
                <button>
                    <i class="ri-search-line"></i>
                </button>
            </label>
        </form>

    </header>

    <?php if(empty($posts) && !empty($search)): ?>
        <!--==== return empty result ========== -->
        <div class="section__content">
            <div class="empty__content">
                <p class="empty_content_title">Sua pesquisa não retornou resultados</p>
                <p class="empty_content_desc">Você pesquisou por <b><?= $search; ?></b>. Tente outros termos.</p>
                <a class="empty_content_btn"
                   href="<?= url("/blog"); ?>" title="Blog">Voltar ao blog</a>
            </div>
        </div>
    <?php elseif (empty($posts)): ?>
        <!--==== return empty posts ========== -->
        <div class="section__content">
            <div class="empty_content">
                <h3 class="empty_content_title">Ainda estamos trabalhando aqui!</h3>
                <p class="empty_content_desc">Nossos editores estão preparando um conteúdo de primeira para você.</p>
            </div>
        </div>
    <?php else: ?>
        <!--==== return search results ========== -->
        <div class="blog__container container grid">
            <?php foreach ($posts as $post): ?>
                  <?php $this->insert("blog-card", ["post" => $post]); ?>
            <?php endforeach; ?>
            <p><?= $paginator; ?></p>
        </div>
    <?php endif; ?>
</section>
<!--========= SECTION FOR SCRIPTS ========== -->
<?php $this->start('scripts'); ?>

<?php $this->stop(); ?>