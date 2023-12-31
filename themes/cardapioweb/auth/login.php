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
            <header>Login</header>
        </div>

        <form class="auth_form" action="<?= url("/entrar"); ?>" method="post" enctype="multipart/form-data">
            <div class="ajax_response">
                <?= flash(); ?>
            </div>
            <?= csrf_input(); ?>

            <div class="input__box">
                <input type="email" value="<?= ($cookie ?? null); ?>" name="email" class="input__field" placeholder="Email" autocomplete="off" required>
            </div>

            <div class="input__box input_valid">
                <input type="password" name="password" class="input__field" placeholder="Password" autocomplete="off" required>
                <!--========= SELECT VIEW PASSWORD CLICK ========== -->

            </div>

            <!--========= MESSAGE VIEW VALID ========== -->
            <div class="forgot"  style="display: flex; justify-content: center; align-items: center;">
                <ul class="requirement-list">
                    <li>
                        <i class="ri-checkbox-blank-circle-line"></i>
                        <span>A senha deve ter no minimo <?= CONF_PASSWORD_MIN_LEN; ?> caracteres</span>
                    </li>
                </ul>
            </div>

            <div class="forgot">
                <section>
                    <input type="checkbox" <?= (!empty($cookie) ? "checked" : ""); ?> name="save" id="check">
                    <label for="check">Lembrar dados?</label>
                </section>

                <section>
                    <a title="Recuperar senha" href="<?= url("/recuperar"); ?>">Esqueceu a senha?</a>
                </section>
            </div>

            <div class="input-submit">
                <button class="submit-btn" id="submit"></button>
                <label for="submit">Entrar</label>
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
