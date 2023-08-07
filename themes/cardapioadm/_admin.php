<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!--=============== SETTING THEME ===============-->
    <?php
        $set = (new \Source\Models\Setting())->find()->fetch();
    ?>

    <!--=============== SEO - Otimização ===============-->
    <?= $head; ?>

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!--=============== CSS MINIFICADO FINAL DO PROJETO ===============-->
    <!-- add css final end project here -->

    <!--=============== CSS EM DESENVOLVIMENTO ===============-->
    <link rel="stylesheet" href="<?= url("/shared/styles/boot.css"); ?>"/>
    <link rel="stylesheet" href="<?= url("/shared/styles/styles.css"); ?>"/>
    <link rel="stylesheet" href="<?= theme("/assets/css/style.css", CONF_VIEW_ADMIN); ?>"/>

    <!--=============== FAVICON ===============-->
    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png", CONF_VIEW_ADMIN); ?>"/>

    <!--========== SECTION STYLES PAGES ==========-->
    <?= $this->section('styles'); ?>
</head>
<body>

<!--========== LOADER ==========-->
<div class="ajax_load" style="z-index: 999;">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>

<!--========== FLASH MSG ==========-->
<div class="ajax_response"><?= flash(); ?></div>

<div class="dash">

    <!--========== SECTION SIDEBAR ==========-->
    <aside class="dash_sidebar">

        <!--========== PROFILE USER ==========-->
        <article class="dash_sidebar_user">
            <?php
            $photo = user()->photo();
            $userPhoto = ($photo ? image($photo, 300, 300) :
                theme("/assets/images/avatar.jpg", CONF_VIEW_ADMIN));
            ?>

            <div><img class="dash_sidebar_user_thumb" src="<?= $userPhoto; ?>" alt="" title=""/></div>
            <h3 class="dash_sidebar_user_name">
                <a href="<?= url("/admin/users/user/" . user()->id); ?>"><?= user()->fullName(); ?></a>
            </h3>
        </article>

        <!--========== LIST NAV SIDEBAR ==========-->
        <ul class="dash_sidebar_nav">
            <?php
            $nav = function ($icon, $href, $title) use ($app) {
                $active = (explode("/", $app)[0] == explode("/", $href)[0] ? "active" : null);
                $url = url("/admin/{$href}");
                return "<li class=\"dash_sidebar_nav_li {$active}\"><a class=\"icon-{$icon}\" href=\"{$url}\">{$title}</a></li>";
            };

            echo $nav("home", "dash", "Dashboard");
            echo $nav("pencil-square-o", "products/home", "Cardápio");
            echo $nav("pencil-square-o", "blog/home", "Blog");
            echo $nav("comments-o", "faq/home", "FAQs");
            echo $nav("user", "users/home", "Usuários");
            echo "<li class=\"dash_sidebar_nav_li\"><a class=\"icon-link\" href=\"" . url() . " \" target=\"_blank\">Ver site</a></li>";
            echo $nav("sign-out on_mobile", "logoff", "Sair");
            ?>
        </ul>
    </aside>

    <!--========== SECTION CONTENT ==========-->
    <section class="dash_content">

        <!--========== USERBAR ==========-->
        <div class="dash_userbar">
            <div class="dash_userbar_box">
                <!--========== CONTENT BAR ==========-->
                <div class="dash_content_box">
                    <h1><img src="<?= image("images/favicon.png", 25) ?>" alt="about"> <a href="<?= url("/admin/dash"); ?>">Cardapio<b>Admin</b></a></h1>
                    <div class="dash_userbar_box_bar">
                        <span class="no_mobile icon-clock-o"><?= date("d/m H\hi"); ?></span>
                        <a class="no_mobile icon-sign-out" title="Sair" href="<?= url("/admin/logoff"); ?>">Sair</a>
                        <span class="icon-menu icon-notext mobile_menu transition"></span>
                    </div>
                </div>
            </div>
        </div>

        <!--========== SECTION CONTENT DASH ==========-->
        <div class="dash_content_box">
            <?= $this->section("content"); ?>
        </div>
    </section>
</div>

<!--========== SECTION SCRIPTS JS ==========-->
<script src="<?= url("/shared/scripts/jquery.min.js"); ?>"></script>
<script src="<?= url("/shared/scripts/jquery.form.js"); ?>"></script>
<script src="<?= url("/shared/scripts/jquery-ui.js"); ?>"></script>
<script src="<?= url("/shared/scripts/jquery.mask.js"); ?>"></script>
<script src="<?= url("/shared/scripts/tinymce/tinymce.min.js"); ?>"></script>
<script src="<?= theme("/assets/js/scripts.js", CONF_VIEW_ADMIN); ?>"></script>
<?= $this->section("scripts"); ?>

</body>
</html>