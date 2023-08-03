<?php $this->layout("_admin", ["head" => $head, "app" => $app]); ?>
<?php $this->insert("widgets/dash/sidebar", ["app" => $app, "confContent" => $confContent]); ?>

<section class="dash_content_app">

    <!--========== HEADER BAR ==========-->
    <header class="dash_content_app_header">
        <h2 class="icon-home">Dashboard</h2>
    </header>

    <div class="dash_content_app_box">

        <!--========== SECTION CONTROLS STATUS ==========-->
        <section class="app_dash_home_stats">

            <!--========== CONTROL CARD ==========-->
            <article class="control radius">
                <h4><i class="ri-bookmark-3-fill"></i> Assinantes</h4>
                <p><b>Assinantes:</b> <?= $subscriptions->subscribs; ?></p>
            </article>

            <!--========== PRODUCTS CARD ==========-->
            <article class="Produtos radius">
                <h4><i class="ri-bookmark-3-fill"></i> Produtos</h4>
                <p><b>Total: </b> <?= $product->tot; ?></p>
                <p><b>A venda:</b> <?= $product->posts; ?></p>
                <p><b>Em criação:</b> <?= $product->drafts; ?></p>
                <p><b>Categorias:</b> <?= $product->categories; ?></p>
            </article>

            <!--========== BLOG CARD ==========-->
            <article class="blog radius">
                <h4><i class="ri-bookmark-3-fill"></i> Blog</h4>
                <p><b>Artigos:</b> <?= $blog->posts; ?></p>
                <p><b>Rascunhos:</b> <?= $blog->drafts; ?></p>
                <p><b>Categorias:</b> <?= $blog->categories; ?></p>
            </article>

            <!--========== USERS CARD ==========-->
            <article class="users radius">
                <h4><i class="ri-bookmark-3-fill"></i> Usuários</h4>
                <p><b>Usuários:</b> <?= $users->users; ?></p>
                <p><b>Admins:</b> <?= $users->admins; ?></p>
            </article>
        </section>

        <!--========== SECTION DASH TRAFIC HOME ==========-->
        <section class="app_dash_home_trafic">
            <h3 class="icon-bar-chart">Online agora:
                <span class="app_dash_home_trafic_count"><?= $onlineCount; ?></span>
            </h3>

            <div class="app_dash_home_trafic_list">
                <?php if (!$online): ?>
                    <div class="message info icon-info">
                        Não existem usuários online navegando no site neste momento. Quando tiver, você
                        poderá monitoriar todos por aqui.
                    </div>
                <?php else: ?>
                    <?php foreach ($online as $onlineNow): ?>
                        <article>
                            <h4>[<?= date_fmt($onlineNow->created_at, "H\hm"); ?> - <?= date_fmt($onlineNow->updated_at,
                                    "H\hm"); ?>]
                                <?= ($onlineNow->user ? $onlineNow->user()->fullName() : "Guest User"); ?></h4>
                            <p><?= $onlineNow->pages; ?> páginas vistas</p>
                            <p class="radius icon-link"><a target="_blank"
                                                           href="<?= url("/{$onlineNow->url}"); ?>"><b><?= strtolower(CONF_SITE_NAME); ?></b><?= $onlineNow->url; ?>
                                </a></p>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</section>

<!--========== SECTION SCRIPTS ==========-->
<?php $this->start("scripts"); ?>

<!--========== SCRIPT TRAFIC HOME ==========-->
<script>
    $(function () {
        setInterval(function () {
            $.post('<?= url("/admin/dash/home");?>', {refresh: true}, function (response) {

                <!--========== COUNT ==========-->
                if (response.count) {
                    $(".app_dash_home_trafic_count").text(response.count);
                } else {
                    $(".app_dash_home_trafic_count").text(0);
                }

                <!--========== LIST ==========-->
                var list = "";
                if (response.list) {
                    $.each(response.list, function (item, data) {
                        var url = '<?= url();?>' + data.url;
                        var title = '<?= strtolower(CONF_SITE_NAME);?>';

                        list += "<article>";
                        list += "<h4>[" + data.dates + "] " + data.user + "</h4>";
                        list += "<p>" + data.pages + " páginas vistas</p>";
                        list += "<p class='radius icon-link'>";
                        list += "<a target='_blank' href='" + url + "'><b>" + title + "</b>" + data.url + "</a>";
                        list += "</p>";
                        list += "</article>";
                    });
                } else {
                    list = "<div class=\"message info icon-info\">\n" +
                        "Não existem usuários online navegando no site neste momento. Quando tiver, você\n" +
                        "poderá monitoriar todos por aqui.\n" +
                        "</div>";
                }

                $(".app_dash_home_trafic_list").html(list);
            }, "json");
        }, 1000 * 3);
    });
</script>
<?php $this->end(); ?>
