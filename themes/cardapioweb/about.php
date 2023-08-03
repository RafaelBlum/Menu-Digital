<?php $this->layout('theme', ['head'=> $head]); ?>

<!--==================== BLOG ====================-->
<section class="about__detail section__about" id="blog">

    <section class="about__faq__section" id="recently">
        <div class="recently__container container grid">
            <div class="recently__data">
                <p class="htmlchars">
                    <?= html_entity_decode($setting->description); ?>
                </p>
            </div>
            <img src="<?= theme("assets/images/site/faq.png")?>" class="recently__img">
        </div>
    </section>

    <div class="about__container">
        <h2 class="about__faq__title">Sobre <?= $setting->project_name;?> </h2>

        <div class="post_page_content">
            <div class="about__faq htmlchars">
                <?= html_entity_decode($setting->about); ?>
            </div>
        </div>

        <h2 class="about__faq__title">Perguntas e respostas</h2>

        <?php if(!empty($faq)): ?>
            <div class="container">
                <div class="about__faq">
                    <?php foreach ($faq as $quest): ?>
                        <div class="about__item">
                            <div class="about__faq__subtitle">
                                <?= $quest->question; ?>
                            </div>
                            <div class="about__faq__response">
                                <p><?= $quest->response; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <img src="<?= theme("assets/images/illustrations/leaf-yellow.png") ?>" alt="leaf" class="recently__leaf-1">
                <img src="<?= theme("assets/images/illustrations/leaf-pink.png") ?>" alt="leaf" class="recently__leaf-2">
            </div>
        <?php endif; ?>

        <?php if($setting->video): ?>

        <div class="about__movie content">
            <section>
                <header class="about__movie__header">
                    <h4>Assista também:</h4>
                    <p>Vídeo demonstrativo detalhado com informações sobre desenvolvimento, arquitetura, linguagens etc...</p>
                </header>

                <div class="about__movie__iframe">
                    <iframe class="about__responsive__iframe"
                            src="https://www.youtube.com/embed/<?= $setting->video; ?>">
                    </iframe>
                </div>
            </section>
        </div>


        <?php endif; ?>
    </div>
</section>