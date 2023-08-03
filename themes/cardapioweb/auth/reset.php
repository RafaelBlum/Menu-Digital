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

            <div class="login__header">
                <header>Criar nova senha</header>
                <p>Informe e repita uma nova senha para recuperar seu acesso.</p>
            </div>

            <form class="auth_form" action="<?= url("/recuperar/reset"); ?>" method="post"
                  enctype="multipart/form-data">

                <div class="ajax_response"><?= flash(); ?></div>
                <input type="hidden" name="code" value="<?= $code; ?>"/>

                <?= csrf_input(); ?>

                <div class="input__box">
                    <input type="password" name="password" class="input__field" placeholder="Nova senha" autocomplete="off" required>
                </div>

                <div class="input__box">
                    <input type="password" name="password_re" class="input__field" placeholder="Repita a nova senha" autocomplete="off" required>
                </div>

                <div class="input-submit">
                    <button class="submit-btn" id="submit"></button>
                    <label for="submit">Alterar Senha</label>
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
