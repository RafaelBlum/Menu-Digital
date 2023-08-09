<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== FAVICON ===============-->
    <link rel="icon" href="<?= theme("development/assets/image/logo.png") ?>" type="image/x-icon">

    <?php
        $set = (new \Source\Models\Setting())->find()->fetch();
    ?>

    <!--=============== BOXICONS ===============-->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!--=============== SEO - Otimização do site para motores de buscas e redes sociais ===============-->
    <?= $head; ?>

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="<?= theme("development/assets/css/styles.css") ?>">
    <link rel="stylesheet" href="<?= theme("development/assets/css/responsive.css") ?>">

    <title><?= $set->project_name; ?></title>
</head>
<body>
    <!--==================== MAIN ====================-->
    <main class="main">
        <!--==================== HOME ====================-->
        <section class="home">

            <div class="container home__container">

                <div class="home__data">

                    <span class="home__subtitle">Olá, seja bem-vindo ao meu portfolio,</span>
                    <h1 class="home__title"><?= $set->project_name; ?></h1>

                    <p class="home__description">
                        Este é um sistema todo desenvolvido em <b>Php 8.2</b> seguindo os padrões das <b>PSR's</b> e
                        utilizando diversas bibliotecas para maior agilidade de desenvolvimento fluidez do sistema.<br>
                        O sistema conta com site web profissional e um sistema de <b>CMS</b> para gestão de site.
                    </p>

                    <a href="<?= url("/home") ?>" type="button" class="home__button">
                        Acesse aqui <i class="ri-arrow-right-s-fill"></i>
                    </a>


                    <!--==================== Image background home ==================== -->
                    <img src="<?= theme("development/assets/image/wave-euclidean.png") ?>" alt="wave image" class="home__leaf-1">
                    <img src="<?= theme("development/assets/image/wave-euclidean.png") ?>" alt="wave image" class="home__leaf-2">
                </div>

                <div class="home__img">
                    <img src="<?= theme("development/assets/image/web_tablet.png") ?>" alt="<?= $set->project_name; ?>">
                    <div class="home__shadow"></div>
                </div>



            </div>

            <!--==================== FOOTER ====================-->
            <div class="container__footer">
                <footer>
                    <div class="container__footer">
                        <h3><?= $set->project_name; ?></h3>
                        <p>Sistema gerenciável para axiliar pequenas e médias empresas
                            a demonstrar seus produtos e gerenciar suas vendas internamente.</p>
                    </div>

                    <div class="footer-bottom">
                        <p>copyright &copy; <?= date("Y"); ?> <?= $set->project_name; ?>.
                            designed by <span><?= CONF_DEV_NAME; ?></span></p>
                    </div>

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
                        <a href="<?= CONF_URL_LINKEDIN; ?>" target="_blank" class="footer__social_link">
                            <i class="ri-linkedin-box-fill"></i>
                        </a>

                    </ul>
                </footer>
            </div>
        </section>
    </main>

<!--=============== SCROLLREVEAL ===============-->
<script src="<?= theme("development/assets/js/scrollreveal.min.js") ?>"></script>

<!--=============== MAIN JS ===============-->
<script src="<?= theme("development/assets/js/script.js") ?>"></script>
</body>
</html>