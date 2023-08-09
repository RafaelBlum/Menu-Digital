<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--=============== SETTING THEME ===============-->
    <?php
        $set = (new \Source\Models\Setting())->find()->fetch();
    ?>

    <!--=============== FAVICON ===============-->
    <link rel="icon" href="<?= theme("assets/images/logo/logo-favico.ico") ?>" type="image/x-icon">

    <!--=============== BOXICONS ===============-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!--=============== SEO - Otimização do site para motores de buscas e redes sociais ===============-->
    <?= $head; ?>

    <!--=============== CSS MINIFICADO FINAL DO PROJETO ===============-->

    <!--=============== CSS EM DESENVOLVIMENTO ===============-->
    <link rel="stylesheet" href="<?= theme("assets/css/styles.css"); ?>" >
    <link rel="stylesheet" href="<?= theme("assets/css/responsive.css"); ?>" >
    <link rel="stylesheet" href="<?= theme("assets/css/form-list.css"); ?>" >

    <!--========== SECTION STYLES PAGES ==========-->
    <?= $this->section('styles'); ?>

</head>
<body>
<!--==================== HEADER ====================-->
<header class="header" id="header">
    <nav class="nav container">
        <a href="<?= url("/home"); ?>" class="nav__logo">
            <img src="<?= theme("assets/images/logo/logo-md.png") ?>" alt="logo image">
            <?= $set->project_name; ?>
        </a>

        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="<?= (url("/home") == $_SERVER['REQUEST_URI'] ? url("/home#home") : url("/home")) ?>" class="nav__link active-link">Home</a>
                </li>

                <li class="nav__item">
                    <a href="#about" class="nav__link">Sobre</a>
                </li>

                <li class="nav__item">
                    <a href="#products" class="nav__link">Produtos</a>
                </li>

                <li class="nav__item">
                    <a href="#recently" class="nav__link">Recentemente</a>
                </li>

                <li class="nav__item">
                    <a href="#blog" class="nav__link">Blog</a>
                </li>

                <?php if(\Source\Models\Auth::user()): ?>
                    <li class="nav__item log__auth">
                        <a href="<?= url("/admin/dash/home") ?>" class="nav__link">
                            <?= \Source\Models\Auth::user()->fullName(); ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav__item">
                        <a href="<?= url("/entrar") ?>" class="nav__link">Login</a>
                    </li>
                <?php endif;?>
            </ul>

            <!--============ Close button ============-->
            <div class="nav__close" id="nav-close">
                <i class="ri-close-line"></i>
            </div>

            <!--==================== Image background header ==================== -->
            <img src="<?= theme("assets/images/illustrations/leaf-yellow.png") ?>" alt="nav image" class="nav__img__1">
            <img src="<?= theme("assets/images/illustrations/leaf-cyan-2.png") ?>" alt="nav image" class="nav__img__2">
        </div>

        <div class="nav__buttons">
            <!-- Button themer dark and light-->
            <i class="ri-moon-line charge-theme" id="theme-button"></i>

            <!--============ toggle button ============-->
            <div class="nav__toggle" id="nav-toggle">
                <i class="ri-apps-2-line"></i>

            </div>

        </div>
    </nav>
</header>

<!--==================== MAIN ====================-->
<main class="main">
    <!--==== SECTION ALTERNATIVE ======-->
    <?php if($this->section("load")): ?>
        <?= $this->section("load"); ?>
    <?php endif; ?>

    <!--==== BODY PAGES ==========-->
    <?= $this->section("content"); ?>
</main>

<!--==================== FOOTER ====================-->
<?php if($this->section('footer-log')): ?>
    <?= $this->section('footer-log'); ?>
<?php else: ?>
    <footer class="footer">
        <div class="footer__container container grid">
            <div>
                <a href="#" class="footer__logo">
                    <img src="<?= theme("assets/images/logo/logo-lg.png") ?>" alt="logo image">
                    <?= $set->project_name; ?>
                </a>

                <p class="footer__description">
    <!--                --><?//= CONF_SITE_DESC; ?>
                    Food for the body is not <br>
                    enough. there must be food <br>
                    for the soul.
                </p>
            </div>

            <div class="footer__content">
                <div>
                    <h3 class="footer__title">Menu</h3>

                    <ul class="footer__links">
                        <li>
                            <a href="<?= url("sobre") ?>" class="footer__link">Sobre</a>
                        </li>

                        <li>
                            <a href="<?= url("produtos") ?>" class="footer__link">Produtos</a>
                        </li>

                        <li>
                            <a href="<?= url("blog") ?>" class="footer__link">Blog</a>
                        </li>

                        <li>
                            <a href="#" class="footer__link">Promoções</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer__title">Informações</h3>

                    <ul class="footer__links">
                        <li>
                            <a href="#" class="footer__link">Contato</a>
                        </li>

                        <li>
                            <a href="#" class="footer__link">Pedidos</a>
                        </li>

                        <li>
                            <a href="#" class="footer__link">Videos</a>
                        </li>

                        <li>
                            <a href="<?= url("/inscricao") ?>" class="footer__link">Receba as promoções</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer__title">Endereço</h3>

                    <ul class="footer__links">
                        <li class="footer__information">
                            <?= CONF_ADDR_STREET . ", " . CONF_ADDR_NUMBER . "<br>" ?>
                            <?= CONF_ADDR_CITY . "/" . CONF_ADDR_STATE . ". CEP " . CONF_ADDR_ZIPCODE . "<br>" ?>
                        </li>

                        <li class="footer__information">
                            Fone <div class="mask-phone"><?= $set->phone; ?></div>
                        </li>
                        <li>
                            <a target="_blank"
                               class="icon-whatsapp transition"
                               href="https://api.whatsapp.com/send?phone=<?= $set->phone; ?>&text=Olá, preciso de ajuda com o login."
                            >WhatsApp: (51) 9812 4404</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer__title">Midias sociais</h3>

                    <ul class="footer__social">
                        <a href="https://www.facebook.com/<?= CONF_SOCIAL_FACEBOOK_PAGE; ?>" target="_blank" class="footer__social_link">
                            <i class="ri-facebook-circle-fill"></i>
                        </a>

                        <a href="<?= CONF_URL_INSTAGRAM; ?>" target="_blank" class="footer__social_link">
                            <i class="ri-instagram-fill"></i>
                        </a>

                        <a href="<?= CONF_URL_TWITTER; ?>" target="_blank" class="footer__social_link">
                            <i class="ri-twitter-fill"></i>
                        </a>

                        <a href="<?= CONF_URL_YOUTUBE; ?>" target="_blank" class="footer__social_link">
                            <i class="ri-youtube-fill"></i>
                        </a>

                        <a href="<?= CONF_URL_GITHUB; ?>" target="_blank" class="footer__social_link">
                            <i class="ri-github-fill"></i>
                        </a>
                    </ul>
                    <div class="footer__qr__code">
                        <?= qr_code(110); ?>
                    </div>
                </div>
            </div>

            <img src="<?= theme("assets/images/illustrations/leaf-cyan-2.png") ?>" alt="" class="footer__onion">
            <img src="<?= theme("assets/images/illustrations/leaf-pink.png") ?>" alt="" class="footer__spinach">
            <img src="<?= theme("assets/images/illustrations/leaf-bg.png") ?>" alt="" class="footer__leaf">
        </div>

        <div class="footer__info container">
            <div class="footer__card">
                <img src="<?= theme("assets/images/site/card.png") ?>" alt="footer card">
                <img src="<?= theme("assets/images/site/card.png") ?>" alt="footer card">
                <img src="<?= theme("assets/images/site/card.png") ?>" alt="footer card">
                <img src="<?= theme("assets/images/site/card.png") ?>" alt="footer card">
            </div>

            <span class="footer__copy">
                    &#169; Copyright <?= $set->project_name; ?>. All Rights reserved
            </span>
        </div>
    </footer>
    <!--========== SCROLL UP ==========-->
    <a href="#" class="scrollup" id="scroll-up">
        <i class="ri-arrow-up-line"></i>
    </a>
<?php endif; ?>

<!--=============== SCROLLREVEAL ===============-->
<script rel="script" type="text/javascript" src="<?= theme("../../shared/scripts/scrollreveal.min.js"); ?>"></script>
<script rel="script" type="text/javascript" src="<?= theme("../../shared/scripts/jquery.min.js"); ?>"></script>
<script rel="script" type="text/javascript" src="<?= theme("../../shared/scripts/jquery.form.js"); ?>"></script>
<script rel="script" type="text/javascript" src="<?= theme("../../shared/scripts/jquery-ui.js"); ?>"></script>
<script src="<?= url("/shared/scripts/jquery.mask.js"); ?>"></script>

<!--=============== JS MINIFICADO FINAL PROJETO ===============-->

<!--=============== JS EM DESENVOLVIMENTO ===============-->
<script rel="script" type="text/javascript" src="<?= theme("assets/js/script.js") ?>"></script>


<!--=============== SECTION SCRIPT PAGES ===============-->
<?= $this->section('scripts'); ?>

</body>
</html>