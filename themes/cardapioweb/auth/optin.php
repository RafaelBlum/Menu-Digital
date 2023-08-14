<?php $this->layout('theme', ['head'=> $head]); ?>

<!--========= SECTION FOR STYLES ========== -->
<?php $this->start('styles'); ?>
    <link rel="stylesheet" href="<?= theme("assets/css/auth.css"); ?>" >
<?php $this->stop(); ?>

<section class="optin__container" id="optin">
    <div class="optin__box container grid">
        <article>
            <div class="container">
                <div class="optin_content">
                    <?php if ($data->confirm == "confirm"): ?>
                        <!--========= NEWSLATTER CONFIRM ========== -->
                        <div class="login__header">
                            <img alt="<?= $data->title; ?>" title="<?= $data->title; ?>" src="<?= $data->image; ?>"/>
                            <header><?= $data->title; ?></header>
                            <p><?= $data->desc; ?></p>

                            <div class="forgot-link">
                                <section>
                                    <a href="<?= $data->link; ?>" title="<?= $data->linkTitle; ?>"><?= $data->linkTitle; ?></a>
                                </section>
                            </div>
                        </div>
                    <?php else:; ?>
                        <!--========= NEWSLATTER SUCCESS ========== -->
                        <div class="login__header">
                            <img alt="<?= $data->title; ?>" title="<?= $data->title; ?>" src="<?= $data->image; ?>"/>
                            <header><?= $data->title; ?></header>
                            <p><?= $data->desc; ?></p>
                        </div>
                        <div class="forgot-link">
                            <h3>
                                <a href="<?= $data->link; ?>" title="<?= $data->linkTitle; ?>"><?= $data->linkTitle; ?></a>
                            </h3>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    </div>
</section>

<!--========= SECTION ALTERNATIVE ADD PAGE ========== -->
<?php $this->start('footer-log'); ?>
    <section class="container__log" id="footer__log">
        <?php $this->insert("auth/partials/footer"); ?>
    </section>
<?php $this->stop(); ?>
