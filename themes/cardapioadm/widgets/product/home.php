<?php $this->layout("_admin", ["head" => $head, "app" => $app]); ?>
<?php $this->insert("widgets/product/sidebar", ["app"=>$app]); ?>

<section class="dash_content_app">
    <header class="dash_content_app_header">
        <h2 class="icon-pencil-square-o">Cardápio</h2>
        <form action="<?= url("/admin/products/home"); ?>" method="post" class="app_search_form">
            <input type="text" name="s" value="<?= $search; ?>" placeholder="Pesquisar produtos:">
            <button class="icon-search icon-notext"></button>
        </form>
    </header>

    <style>
        article {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        li {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        .container__post {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }

        .container__post::selection {
            background: #ea4c89;
            color: rgba(255,255,255,0.85);
        }

        .thumbnail-bvz  {
            position: relative;
            height: 0;
            padding-bottom: 75%;
            overflow: hidden;
            border-radius: 8px;
        }

        .shot-display-template .thumbnail-bvz::after, .container-lof .thumbnail-bvz::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            pointer-events: none;
            background: -webkit-gradient(linear, left top, left bottom, from(transparent), color-stop(8.1%, transparent), color-stop(15.5%, rgba(0,0,0,0.001)), color-stop(22.5%, rgba(0,0,0,0.003)), color-stop(29%, rgba(0,0,0,0.005)), color-stop(35.3%, rgba(0,0,0,0.008)), color-stop(41.2%, rgba(0,0,0,0.011)), color-stop(47.1%, rgba(0,0,0,0.014)), color-stop(52.9%, rgba(0,0,0,0.016)), color-stop(58.8%, rgba(0,0,0,0.019)), color-stop(64.7%, rgba(0,0,0,0.022)), color-stop(71%, rgba(0,0,0,0.025)), color-stop(77.5%, rgba(0,0,0,0.027)), color-stop(84.5%, rgba(0,0,0,0.029)), color-stop(91.9%, rgba(0,0,0,0.03)), to(rgba(0,0,0,0.03)));
            background: linear-gradient(to bottom, transparent 0%, transparent 8.1%, rgba(0,0,0,0.001) 15.5%, rgba(0,0,0,0.003) 22.5%, rgba(0,0,0,0.005) 29%, rgba(0,0,0,0.008) 35.3%, rgba(0,0,0,0.011) 41.2%, rgba(0,0,0,0.014) 47.1%, rgba(0,0,0,0.016) 52.9%, rgba(0,0,0,0.019) 58.8%, rgba(0,0,0,0.022) 64.7%, rgba(0,0,0,0.025) 71%, rgba(0,0,0,0.027) 77.5%, rgba(0,0,0,0.029) 84.5%, rgba(0,0,0,0.03) 91.9%, rgba(0,0,0,0.03) 100%);
        }

        .container-y4c  {
            display: -ms-flexbox;
            display: flex;
            position: relative;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: justify;
            justify-content: space-between;
            margin-top: 8px;
        }

        .thumbnail-li1 {
            margin: 0;
            overflow: hidden;
        }

        .thumbnail-li1:before {
            content: '';
            display: block;
            padding-top: 75%;
        }

        .thumbnail-2ri  {
            display: -ms-flexbox;
            display: flex;
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: end;
            justify-content: flex-end;
            height: 40px;
            padding: 0 12px;
            color: #ffffff;
            font-weight: 600;
            font-size: 15px;
            text-shadow: 1px 2px #3d3d4e;
        }

        a {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        a:link {
            -webkit-transition: color 200ms ease;
            transition: color 200ms ease;
            color: #0d0c22;
            text-decoration: none;
        }

        a:hover {
            color: #3d3d4e;
            text-decoration: none;
        }

        .thumbnail-v4d  {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .thumbnail-y74  {
            display: -ms-flexbox;
            display: flex;
            z-index: 2;
            -ms-flex-align: end;
            align-items: flex-end;
            padding: 20px;
            -webkit-transition: opacity 300ms ease;
            transition: opacity 300ms ease;
            border-radius: 8px;
            opacity: 0;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(62%, transparent), color-stop(63.94%, rgba(0,0,0,0.00345888)), color-stop(65.89%, rgba(0,0,0,0.014204)), color-stop(67.83%, rgba(0,0,0,0.0326639)), color-stop(69.78%, rgba(0,0,0,0.0589645)), color-stop(71.72%, rgba(0,0,0,0.0927099)), color-stop(73.67%, rgba(0,0,0,0.132754)), color-stop(75.61%, rgba(0,0,0,0.177076)), color-stop(77.56%, rgba(0,0,0,0.222924)), color-stop(79.5%, rgba(0,0,0,0.267246)), color-stop(81.44%, rgba(0,0,0,0.30729)), color-stop(83.39%, rgba(0,0,0,0.341035)), color-stop(85.33%, rgba(0,0,0,0.367336)), color-stop(87.28%, rgba(0,0,0,0.385796)), color-stop(89.22%, rgba(0,0,0,0.396541)), color-stop(91.17%, rgba(0,0,0,0.4)));
            background: linear-gradient(180deg, transparent 62%, rgba(0,0,0,0.00345888) 63.94%, rgba(0,0,0,0.014204) 65.89%, rgba(0,0,0,0.0326639) 67.83%, rgba(0,0,0,0.0589645) 69.78%, rgba(0,0,0,0.0927099) 71.72%, rgba(0,0,0,0.132754) 73.67%, rgba(0,0,0,0.177076) 75.61%, rgba(0,0,0,0.222924) 77.56%, rgba(0,0,0,0.267246) 79.5%, rgba(0,0,0,0.30729) 81.44%, rgba(0,0,0,0.341035) 83.39%, rgba(0,0,0,0.367336) 85.33%, rgba(0,0,0,0.385796) 87.28%, rgba(0,0,0,0.396541) 89.22%, rgba(0,0,0,0.4) 91.17%);
            pointer-events: none;
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .thumbnail-bvz:hover .thumbnail-y74  {
            opacity: 1;
        }

        .container-y4c .form-x4a  {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            min-width: 0;
        }

        .container-y1q {
            display: -ms-flexbox;
            display: flex;
            -ms-flex: 1;
            flex: 1;
            -ms-flex-pack: end;
            justify-content: flex-end;
            color: #9e9ea7;
            font-family: var(--font-face, "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif);
            font-size: 12px;
            font-weight: 400;
            line-height: 16px;
        }

        .thumbnail-li1 > *  {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        img {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        .thumbnail-li1 img  {
            width: 100%;
            height: 100%;
            -o-object-fit: cover;
            object-fit: cover;
        }

        span {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        .text-1zb {
            position: absolute;
            width: 0;
            overflow: hidden;
            opacity: 0;
        }

        .thumbnail-y74 .thumbnail-n3e  {
            display: -ms-flexbox;
            display: flex;
            -ms-flex: 1;
            flex: 1;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: justify;
            justify-content: space-between;
            min-width: 0;
        }

        .container-y4c .form-x4a a.dhief  {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            min-width: 0;
        }

        .container-y4c .form-x4a .badge-bxy  {
            display: -ms-flexbox;
            display: flex;
            margin-left: 8px;
        }

        .container-y1q .shot-3tb  {
            display: -ms-inline-flexbox;
            display: inline-flex;
            -ms-flex-align: center;
            align-items: center;
            margin-left: 8px;
        }

        .thumbnail-y74 .title-hyo  {
            color: #fff;
            font-family: var(--font-face, "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif);
            font-size: 16px;
            font-weight: 500;
            line-height: 22px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        ul {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        ul {
            list-style: none;
        }

        .container-3el  {
            display: -ms-flexbox;
            display: flex;
            -ms-flex: 1;
            flex: 1;
            -ms-flex-pack: end;
            justify-content: flex-end;
        }

        a img  {
            border: none;
        }

        .container-y4c .form-x4a .pho-1pb  {
            width: 24px;
            height: 24px;
            overflow: hidden;
            border-radius: 50%;
        }

        .container-y4c .form-x4a .display-zbl  {
            font-family: var(--font-face, "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif);
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            -ms-flex: 1;
            flex: 1;
            margin-left: 8px;
            color: #0d0c22;
        }

        span.badge-xd4 {
            padding: 1px 3px;
            border-radius: 3px;
            background: #ccc;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            line-height: 1;
            text-transform: uppercase;
        }

        .container-y4c .form-x4a .badge-xd4  {
            padding: 2px 3px;
        }

        a:hover span.badge-rs8,span.badge-rs8:hover {
            background-color: #ea4c89;
        }

        .font-weight-wsy {
            font-weight: 500;
        }

        .col-b1g {
            color: #3d3d4e;
        }

        .fill-current {
            fill: currentColor;
        }

        .container-y1q .icon  {
            width: 16px;
            height: 16px;
            margin-right: 4px;
            -webkit-transition: color 200ms ease;
            transition: color 200ms ease;
            color: #9e9ea7;
        }

        .container-3el .shot-4fc  {
            margin-left: 12px;
        }

        .btn-5ye,a.btn-5ye {
            --btn-bg-color: #fff;
            --btn-bg-color-hover: #fff;
            --btn-text-color: #0d0c22;
            --btn-text-color-hover: #6e6d7a;
            --btn-border-color: var(--btn-bg-color);
            --btn-border-color-hover: var(--btn-bg-color-hover);
        }

        .container-y1q a  {
            display: -ms-flexbox;
            display: flex;
        }

        .btn-thq {
            display: -ms-inline-flexbox;
            display: inline-flex;
            position: relative;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .btn-thq,a.btn-thq {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            width: var(--btn-width, -webkit-min-content);
            width: var(--btn-width, -moz-min-content);
            width: var(--btn-width, min-content);
            height: var(--btn-height);
            padding: 0 var(--btn-padding);
            overflow: hidden;
            -webkit-transition: 0.15s cubic-bezier(0.32, 0, 0.59, 0.03);
            transition: 0.15s cubic-bezier(0.32, 0, 0.59, 0.03);
            -webkit-transition-property: color, background-color, border, border-radius, visibility;
            transition-property: color, background-color, border, border-radius, visibility;
            border: var(--btn-border-width, 2px) solid var(--btn-border-color, var(--btn-bg-color));
            border-radius: var(--btn-border-radius, 9999999px);
            background-color: var(--btn-bg-color);
            color: var(--btn-text-color);
            font-family: inherit;
            font-size: var(--btn-font-size);
            font-weight: var(--btn-font-weight, 600);
            line-height: 1;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-5d4,a.btn-5d4 {
            --btn-width: var(--btn-height);
            --btn-padding: 0;
            --btn-border-radius: 50%;
        }

        .btn-5d4 ,a.btn-5d4  {
            --btn-border-radius: 8px;
        }

        .container-3el .bucket-jfv  {
            pointer-events: auto;
        }

        .btn-thq:before,a.btn-thq:before {
            content: '';
            visibility: var(--btn-loading-visibility, hidden);
            position: absolute;
            z-index: 2;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: var(--btn-loading-opacity, 0);
            background-color: var(--btn-bg-color);
            pointer-events: none;
        }

        .btn-thq:after,a.btn-thq:after {
            content: '';
            display: -ms-flexbox;
            display: flex;
            visibility: var(--btn-loading-visibility, hidden);
            position: absolute;
            z-index: 2;
            top: calc(50% - (var(--btn-icon-size) / 2));
            left: calc(50% - (var(--btn-icon-size) / 2));
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            width: var(--btn-icon-size);
            height: var(--btn-icon-size);
            -webkit-animation: loading-spin-animation 0.35s infinite linear;
            animation: loading-spin-animation 0.35s infinite linear;
            border: 1px solid var(--btn-text-color);
            border-radius: 50%;
            border-top-color: transparent;
            border-right-color: transparent;
            opacity: var(--btn-loading-opacity, 0);
            pointer-events: none;
        }

        @media (hover: hover){
            .btn-thq:hover:not([disabled]),a.btn-thq:hover:not([disabled]) {
                border-color: var(--btn-border-color-hover, var(--btn-bg-color-hover));
                background-color: var(--btn-bg-color-hover, var(--btn-bg-color));
                color: var(--btn-text-color-hover, var(--btn-text-color));
            }
        }

        .container-3el .like-8m3  {
            pointer-events: auto;
        }

        .btn-thq svg ,a.btn-thq svg  {
            width: var(--btn-icon-width, var(--btn-icon-size));
            height: var(--btn-icon-height, var(--btn-icon-size));
            -webkit-transition: inherit;
            transition: inherit;
            color: var(--btn-icon-color, var(--btn-text-color));
        }

    </style>

    <div class="dash_content_app_box">
        <section>
            <div class="app_blog_home">
                <?php if (!$products): ?>
                    <div class="message info icon-info">Ainda não existem produtos cadastrados no cardápio.</div>
                <?php else: ?>
                    <?php foreach ($products as $product):
                        $productCover = ($product->cover ? image($product->cover, 300) : CONF_IMAGE_DEFAULT);
                        ?>


                        <article class="container__post">
                            <div class="thumbnail-bvz">
                                <figure class="thumbnail-li1">
                                    <img src="<?= $productCover ?>">
                                </figure>
                                <div class="thumbnail-2ri">
                                    <?= $product->title; ?>
                                </div>
                                <a class="thumbnail-v4d" href="<?= url("/produtos/{$product->uri}"); ?>">
                                    <span class="text-1zb"><?= $product->title; ?></span>
                                </a>
                                <div class="thumbnail-y74">
                                    <div class="thumbnail-n3e">
                                        <div class="title-hyo"><?= $product->title; ?></div>

                                        <ul class="container-3el">
                                            <li class="shot-4fc">
                                                <a class="bucket-jfv btn-thq btn-5d4 btn-5ye" href="<?= url("/admin/products/product/{$product->id}"); ?>">
                                                    <i class="ri-edit-2-line" style="font-size: 20px; padding: 5px;"></i>
                                                </a>
                                            </li>

                                            <div class="shot-4fc">
                                                <a class="btn-thq btn-5ye btn-5d4 like-8m3" title="Remover produto" href="#"
                                                   data-post="<?= url("/admin/products/product"); ?>"
                                                   data-action="delete"
                                                   data-confirm="Tem certeza que deseja deletar esse post?"
                                                   data-product_id="<?= $product->id; ?>">
                                                    <i class="ri-delete-bin-2-fill" style="font-size: 20px; padding: 5px;"></i>
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="container-y4c">
                                <div class="form-x4a">
                                    <a class="dhief" href="/awsmd">
                                        <img class="pho-1pb" width="24" height="24" src="<?= $productCover ?>">
                                        <span class="display-zbl"><?= $product->category()->title; ?></span>
                                    </a> <a class="badge-bxy" href="/pro">
                                        <span class="badge-xd4 badge-rs8"><?= ($product->status == "post" ? "Publicado" : ($product->status == "draft" ? "Rascunho" : "Lixo")); ?></span>
                                    </a>
                                </div>
                                <div class="container-y1q">
<!--                                    <div class="shot-3tb">-->
<!--                                        <div class="shot-4fc">-->
<!--                                            <a class="btn-5ye like-8m3" href="/signup/new">-->
<!--                                                <i class="ri-heart-line"></i>-->
<!--                                                <span class="text-1zb">Like</span>-->
<!--                                            </a>-->
<!--                                        </div>-->
<!--                                        <span class="col-b1g font-weight-wsy">168</span>-->
<!--                                    </div>-->
                                    <div class="shot-3tb">
                                        <i class="ri-eye-line"></i>
                                        <span class="col-b1g font-weight-wsy"><?= $product->views; ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?= $paginator; ?>
        </section>
    </div>
</section>