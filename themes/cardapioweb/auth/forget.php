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
                <header>Recuperar senha</header>
                <p>Informe seu e-mail para receber um link de recuperação.</p>
            </div>

            <!--== data-reset para limpar campo do formulário == -->
            <form class="auth_form" data-reset="true" action="<?= url("/recuperar") ?>" method="post" enctype="multipart/form-data">
                <div class="ajax_response"><?= flash(); ?></div>
                <?= csrf_input(); ?>

                <div class="input__box">
                    <input type="email" name="email" class="input__field" placeholder="Email" autocomplete="off" required>
                </div>

                <div class="forgot-log">
                    <section>
                        <a title="Voltar e entrar!" href="<?= url("/entrar"); ?>">Voltar e entrar!</a>
                    </section>
                </div>

                <div class="input-submit">
                    <button class="submit-btn" id="submit"></button>
                    <label for="submit">Recuperar</label>
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
