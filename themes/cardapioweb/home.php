<?php $this->layout('theme', ['head'=> $head]); ?>

<!--========= SECTION FOR STYLES ========== -->
<?php $this->start('styles'); ?>

<?php $this->stop(); ?>

<!--========= SECTION LOAD BOX ========== -->
<?php $this->start('load'); ?>
    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>
<?php $this->stop(); ?>

<!--==================== HOME ====================-->
<section class="home section" id="home">
    <div class="home__container container grid">
        <img src="<?= theme("assets/images/illustrations/hero-header.png") ?>" alt="logo home" class="home__img">

        <div class="home__data">
            <h1 class="home__title">
                <div>
                    <?= $setting->project_name; ?>
                </div>
            </h1>
            <p class="home__description">
                Você terá o controle total sobre as vendas. <br>
                O Meu cardápio digital é Intuitivo e adaptável <br>
                ao seu modelo de negócio.
            </p>
            <a target="_blank" type="button"
               class="icon-whatsapp transition button"
               href="https://api.whatsapp.com/send?phone=<?= $setting->phone; ?>&text=Olá, gostária de solicitar um orçamento...">
                <i class="ri-whatsapp-line"></i> Faça seu orçamento agora!
            </a>
        </div>
    </div>

    <!--==================== Image background home ==================== -->
    <img src="<?= theme("assets/images/illustrations/leaf-pink.png") ?>" alt="home imagem" class="home__leaf-1">
    <img src="<?= theme("assets/images/illustrations/leaf-yellow.png") ?>" alt="home imagem" class="home__leaf-2">
</section>


<!--==================== ABOUT ====================-->
<section class="about section" id="about">
    <div class="about__container container grid">

        <div class="about__data">
            <span class="section__title">Sobre nós</span>

            <h2 class="section__subtitle about__title">
                <div>
                    Perguntas frequentes
                    <img src="<?= theme("assets/images/logo/logo-cd-2.png") ?>" alt="about">
                </div>
            </h2>

            <p class="about__description">
                <?= $setting->description; ?>
            </p>
            <a href="<?= url("/sobre"); ?>" title="sobre" class="button__more">Saiba mais... <i class="ri-arrow-right-line"></i></a>
        </div>

        <img src="<?= theme("assets/images/site/faq.png") ?>" alt="about image" class="about__img">
    </div>

    <img src="<?= theme("assets/images/illustrations/design.png") ?>" alt="about leaf 1" class="about__leaf-1">
    <img src="<?= theme("assets/images/illustrations/leaf-cyan-2.png") ?>" alt="about leaf 2" class="about__leaf-2">
</section>


<!--==================== PRODUCTS ====================-->
<section class="products section" id="products">
    <span class="section__title">Produtos</span>
    <h2 class="section__subtitle">Os queridinhos e gostosos</h2>

    <div class="products__container container grid">
        <?php foreach ($products as $prod): ?>
            <?php $this->insert("product-card", ["prod" => $prod, "isPrice" => $isPrice]); ?>
        <?php endforeach; ?>
        <a href="<?= url("/produtos"); ?>" title="sobre" class="button__more">Veja mais... <i class="ri-arrow-right-line"></i></a>
    </div>
</section>

<!--==================== RECENTLY ====================-->
<?php if(!empty($recently)): ?>
    <section class="recently section" id="recently">
        <div class="recently__container container grid">
            <div class="recently__data">
                <span class="section_title">Última novidade</span>
                <h2 class="section__subtitle">
                    <?=$recently->title;?>
                </h2>

                <p class="recently__description">
                    <?= html_entity_decode($recently->subtitle); ?>
                </p>
                <a href="<?= url("/produtos/{$recently->uri}"); ?>" title="sobre" class="button__more">Mais detalhes... <i class="ri-arrow-right-line"></i></a>
            </div>
            <img src="<?= image($recently->cover, 800) ?>" alt="<?=$recently->name;?>" class="recently__img">
        </div>

        <img src="<?= theme("assets/images/illustrations/leaf-yellow.png") ?>" alt="leaf" class="recently__leaf-1">
        <img src="<?= theme("assets/images/illustrations/leaf-pink.png") ?>" alt="leaf" class="recently__leaf-2">
    </section>
<?php endif; ?>

<!--==================== BLOG ====================-->
<section class="blog section" id="blog">
    <span class="section__title">Blog</span>
    <h2 class="section__subtitle">Informativos e conteúdo</h2>

    <div class="blog__container container grid">
        <?php foreach ($posts as $post): ?>
            <?php $this->insert("blog-card", ["post"=> $post]); ?>
        <?php endforeach; ?>
        <a href="<?= url("/blog"); ?>" title="sobre" class="button__more">Veja mais... <i class="ri-arrow-right-line"></i></a>
    </div>
</section>

<!--========= SECTION FOR SCRIPTS ========== -->
<?php $this->start('scripts'); ?>

<?php $this->stop(); ?>
