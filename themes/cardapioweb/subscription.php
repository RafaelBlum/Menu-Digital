<?php $this->layout('theme', ['head'=> $head]); ?>

<!--========= SECTION FOR STYLES ========== -->
<?php $this->start('styles'); ?>
    <link rel="stylesheet" href="<?= theme("assets/css/auth.css"); ?>" >
    <link rel="stylesheet" href="<?= theme("assets/css/message.css"); ?>" >
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

<section class="auth__container" id="auth__login">
    <div class="login__box container">

        <span class="section__title">Receber notificações</span>

        <form class="auth_form" action="<?= url("/subscrib"); ?>" method="post" enctype="multipart/form-data">
            <h2 class="section__subtitle">Cadastre seu e-mail e receba informações sobre as delícias.</h2>
            <div class="ajax_response">
                <?= flash(); ?>
            </div>
            <?= csrf_input(); ?>

            <div class="input__box">
                <input type="text" name="name" class="input__field" placeholder="Nome:" autocomplete="off" required/>
            </div>

            <div class="input__box">
                <input type="email" name="email" class="input__field" placeholder="Email" autocomplete="off" required>
            </div>

            <div class="input-submit">
                <button class="submit-btn" id="submit"></button>
                <label for="submit">Enviar</label>
            </div>

        </form>
    </div>
</section>

<!--========= SECTION ALTERNATIVE ADD PAGE ========== -->
<?php $this->start('footer-log'); ?>
    <section class="container__log" id="footer__log">
        <?php $this->insert("auth/partials/footer"); ?>
    </section>
<?php $this->stop(); ?>

<!--========= SECTION FOR SCRIPTS ========== -->
<?php $this->start('scripts'); ?>
    <script rel="script" type="text/javascript" src="<?= theme("assets/js/form-script.js") ?>"></script>
<?php $this->stop(); ?>
