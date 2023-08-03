<?php $this->layout("_admin", ["head" => $head, "app" => $app]); ?>
<?php $this->insert("widgets/dash/sidebar", ["app" => $app, "confContent" => $confContent]); ?>

<!--========== SECTION UPLOAD POST ==========-->
<div class="mce_upload" style="z-index: 997">
    <div class="mce_upload_box">

        <!--========== SELECT IMAGE POST ==========-->
        <form class="app_form" action="<?= url("/admin/dash/settings"); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="upload" value="true"/>
            <label>
                <label class="legend">Selecione uma imagem JPG ou PNG:</label>
                <input accept="image/*" type="file" name="image" required/>
            </label>
            <button class="btn btn-blue icon-upload">Enviar Imagem</button>
        </form>
    </div>
</div>

<section class="dash_content_app">

        <header class="dash_content_app_header">
            <h2><i class="ri-settings-4-line"></i> Configurações</h2>
        </header>

        <div class="dash_content_app_box">
            <form class="app_form" action="<?= url("/admin/dash/settings/{$settings->id}"); ?>" method="post">
                <!--ACTION SPOOFING-->
                <input type="hidden" name="action" value="update"/>

                <div class="label_g2">
                    <!--=======  ======== -->
                    <label class="label">
                        <span class="legend">*Nome projeto:</span>
                        <input type="text" name="project_name" value="<?= $settings->project_name; ?>"
                               placeholder="Nome do projeto" required/>
                    </label>

                    <!--=======  ======== -->
                    <label class="label">
                        <span class="legend">*Mostrar valores de produtos:</span>
                        <select name="view_price" required>
                            <?php
                            $view = $settings->view_price;
                            $select = function ($value) use ($view) {
                                return ($view == $value ? "selected" : "");
                            };
                            ?>
                            <option <?= $select("active"); ?> value="active">Ativado</option>
                            <option <?= $select("inactive"); ?> value="inactive">Desativado</option>
                        </select>
                    </label>
                </div>

                <!--=======  ======== -->
                <div class="label_g2">
                    <label class="label">
                        <span class="legend">Vídeo:</span>
                        <input type="text" name="video" value="<?= $settings->video; ?>" placeholder="O ID de um vídeo do YouTube"/>
                    </label>

                    <!--=======  ======== -->
                    <div class="label">
                        <label class="label">
                            <span class="legend">Fone:</span>
                            <input class="mask-phone" type="text" value="<?= $settings->phone; ?>" name="phone"
                                   placeholder="Fone de contato"/>
                        </label>
                    </div>
                </div>

                <!--=======  ======== -->
                <label class="label">
                    <span class="legend">logo: (600x600px)</span>
                    <input type="file" name="logo" placeholder="Logo"/>
                </label>

                <label class="label">
                    <span class="legend">*Descrição do projeto:</span>
                    <textarea class="mce" required placeholder="Descrição do projeto" name="description"><?= $settings->description; ?></textarea>
                </label>

                <!--=======  ======== -->
                <label class="label">
                    <span class="legend">*Conteúdo sobre:</span>
                    <textarea class="mce" name="about"><?= $settings->about; ?></textarea>
                </label>



                <div class="app_form_footer">
                    <button class="btn btn-blue icon-check-square-o">Atualizar</button>
                </div>

            </form>
        </div>
</section>

<!--========== SECTION SCRIPTS ==========-->
<?php $this->start("scripts"); ?>


<?php $this->end(); ?>
