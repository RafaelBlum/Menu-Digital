<?php $this->layout("_admin", ["head" => $head, "app" => $app]); ?>
<?php $this->insert("widgets/dash/sidebar", ["app" => $app, "confContent" => $confContent]); ?>

<section class="dash_content_app">

    <!--========== HEADER SUBSCRITIONS ==========-->
    <header class="dash_content_app_header">

        <!--========== TITLE ==========-->
        <h2 class="icon-pencil-square-o">Inscrições</h2>

        <!--========== SEARCH SUBSCRITIONS ==========-->
        <form action="<?= url("/admin/dash/subscriptions"); ?>" method="post" class="app_search_form">
            <input type="text" name="s" value="<?= $search; ?>" placeholder="Pesquisar inscrição:">
            <button class="icon-search icon-notext"></button>
        </form>
    </header>

    <div class="dash_content_app_box">
        <!--========== SECTION DASH SUBSCRITIONS ==========-->
        <section class="app_dash_home_subscrib">
            <h3><i class="ri-file-mark-line"></i>Inscrições ativas:
                <span class="app_dash_home_subscrib_count"><?= $subscribCount; ?></span>
            </h3>

            <div class="app_dash_subscriptions">
                <?php if (!$subscriptions): ?>
                    <div class="message info icon-info">
                        Não existem inscrições no momento.
                    </div>
                <?php else: ?>
                    <?php foreach ($subscriptions as $subscrib): ?>
                        <article>
                            <h4>[ <?= $subscrib->name; ?> ]</h4>
                            <p><?= $subscrib->email; ?></p>

                            <p class="<?= $subscrib->status; ?>"
                                    data-deactivate="<?= url("/admin/dash/subscriptions/deactivate"); ?>"
                                    data-subscrib_id="<?= $subscrib->id; ?>">
                                <?= $subscrib->status; ?>
                            </p>

                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!--=============  paginator  ================-->
            <?= $paginator; ?>
        </section>
    </div>
</section>

<!--========== SECTION SCRIPTS ==========-->
<?php $this->start("scripts"); ?>


<?php $this->end(); ?>
