<?php $this->layout('theme', ['head'=> $head]); ?>

<div class="container grid">
    <article class="products__card" style="margin-top: 300px;">

            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 col-xl-9">
                <div class="tm-blog-post">
                    <h3 class="products__name">
                            <?= $prod->title; ?>
                    </h3>
                    <a href="<?= url("/product/name-product") ?>" title="product">
                        <img src="<?= image($prod->cover, 500, 300) ?>" alt="<?= $prod->title; ?>" class="product__item">
                    </a>

                    <p style="margin-top: 140px;"><?= $prod->content; ?></p>

                </div>
            </div>
        <div class="container grid">
            <p><?= date_fmt($prod->created_at, "d-m-Y") ?></p>
            <h3><?= $prod->category()->title; ?></h3>
        </div>
    </article>

    <?php if(!empty($related)):  ?>
        <div class="products__container container grid">
            <?php foreach ($related as $prod): ?>
                <?php $this->insert("product-card", ["prod" => $prod]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- ===================================== SCRIPT ================================================================== -->
<!-- ===========    REFAZER BOTÃO DE COMPARTILHAMENTO FACEBOOK DEVELOPER FACEBOOK   ============================= -->
<!--//VIDEO 8.9-->
<?php $this->start("scripts"); ?>
    <div id="fb-root"></div>

    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>


    <script>
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v3.1&appId=267654637306249&autoLogAppEvents=1';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
<?php $this->end(); ?>