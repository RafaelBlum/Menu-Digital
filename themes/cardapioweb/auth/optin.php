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
                    <?php if (!empty($data->news->email)): ?>
                        <!--========= NEWSLATTER ========== -->
                        <div class="login__header">
                            <img alt="<?= $data->news->title; ?>" title="<?= $data->news->title; ?>" src="<?= $data->image; ?>"/>
                            <header><?= $data->news->title; ?></header>
                            <p><?= $data->news->desc; ?></p>
                            <p><b><?=$data->news->email;?></b></p>
                        </div>

                    <?php else:; ?>
                        <!--========= REGISTER ========== -->
                        <div class="login__header">
                            <img alt="<?= $data->title; ?>" title="<?= $data->title; ?>" src="<?= $data->image; ?>"/>
                            <header><?= $data->title; ?></header>
                            <p><?= $data->desc; ?></p>
                        </div>

                        <?php if (!empty($data->link)): ?>
                            <div class="forgot-link">
                                <h3>
                                    <a href="<?= $data->link; ?>" title="<?= $data->linkTitle; ?>"><?= $data->linkTitle; ?></a>
                                </h3>
                            </div>
                        <?php endif; ?>
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
