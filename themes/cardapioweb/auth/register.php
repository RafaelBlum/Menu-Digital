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
                <header>Cadastre-se</header>
                <p>Já tem uma conta? <a title="Fazer login!" href="<?= url("/entrar"); ?>">Fazer login!</a></p>
            </div>

            <form class="auth_form" action="<?= url("/cadastrar"); ?>" method="post" enctype="multipart/form-data">
                <div class="ajax_response"><?= flash(); ?></div>
                
                <?= csrf_input(); ?>

                <div class="input__box">
                    <input type="text" name="first_name" class="input__field" placeholder="Nome" autocomplete="off" required/>
                </div>

                <div class="input__box">
                    <input type="text" name="last_name" class="input__field" placeholder="Sobrenome" autocomplete="off" required/>
                </div>

                <div class="input__box">
                    <input type="email" value="<?= ($cookie ?? null); ?>" name="email" class="input__field" placeholder="Email" autocomplete="off" required>
                </div>

                <div class="input__box">
                    <input type="password" name="password" class="input__field" placeholder="Senha" autocomplete="off" required>
                </div>

                <div class="input-submit">
                    <button class="submit-btn" id="submit"></button>
                    <label for="submit">Criar conta</label>
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
