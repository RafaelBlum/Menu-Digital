<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="<?= theme("assets/image/logo-cd.png") ?>" type="image/x-icon">

    <!--=============== BOXICONS ===============-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!--=============== CSS EM PRODUÇÃO ===============-->
    <link rel="stylesheet" href="<?= theme("assets/css/styles.css"); ?>" >
    <link rel="stylesheet" href="<?= theme("assets/css/responsive.css"); ?>" >

    <title><?= $error->code; ?> - Cardápio Digital</title>
</head>

<body>
    <div id="notfound">
         <div class="notfound">

             <!--==================== Type error ====================-->
            <div class="notfound__404" id="notfound__404">
                <h1 class="notfound__type__error"><?= $error->code; ?></h1>
            </div>

            <h2><?= $error->title; ?></h2>

            <h5><?= $error->message; ?></h5>


             <?php if ($error->link): ?>
                 <a href="<?= $error->link; ?>" class="button notfound__btn">
                     <?= $error->linkTitle; ?>

                     <?php if ($error->link == "mailto:". MAIL['support']): ?>
                         <i class="ri-mail-send-line"></i>
                     <?php else: ?>
                         <i class="ri-home-smile-line"></i>
                     <?php endif; ?>
                 </a>
             <?php endif; ?>

             <!--==================== Midia social ====================-->
             <div class="notfound__social">
                 <div class="footer__social">
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
                 </div>
             </div>

             <!--==================== Image error ==================== -->
             <img src="<?= theme("development/assets/image/wave-euclidean.png") ?>" alt="wave image" class="home__leaf-1">
             <img src="<?= theme("development/assets/image/wave-euclidean.png") ?>" alt="wave image" class="home__leaf-2">
        </div>
    </div>

    <!--=============== SCROLLREVEAL ===============-->
    <script src="<?= theme("assets/js/scrollreveal.min.js") ?>" rel="script" type="text/javascript"></script>
    <!--=============== MAIN JS ===============-->
    <script rel="script" type="text/javascript">
        /*=============== SCROLL REVEAL ANIMATION ===============*/
        const sr = ScrollReveal({
            distance: '60px',
            duration: 2500,
            delay: 200,
        });
        sr.reveal('.notfound__type__error' , {origin: 'bottom'});
    </script>
</body>
</html>
