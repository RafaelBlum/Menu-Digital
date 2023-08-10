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
        .container__prod article {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        .container__prod li {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
            border: red solid 1px;
        }

        .container__prod {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }

        .container__prod::selection {
            background: #ea4c89;
            color: rgba(255,255,255,0.85);
        }

        .thumbnail__prod  {
            position: relative;
            height: 0;
            padding-bottom: 75%;
            overflow: hidden;
            border-radius: 8px;
        }

        .shot-display-template .thumbnail__prod::after, .container-lof .thumbnail__prod::after {
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

        .content__prod  {
            display: -ms-flexbox;
            display: flex;
            position: relative;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: justify;
            justify-content: space-between;
            margin-top: 8px;
        }

        .thumbnail__img {
            margin: 0;
            overflow: hidden;
        }

        .thumbnail__img:before {
            content: '';
            display: block;
            padding-top: 75%;
        }

        .thumbnail__title  {
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

        .container__prod a {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        .container__prod a:link {
            -webkit-transition: color 200ms ease;
            transition: color 200ms ease;
            color: #0d0c22;
            text-decoration: none;
        }

        .container__prod a:hover {
            color: #3d3d4e;
            text-decoration: none;
        }

        .thumbnail__title__access  {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .thumbnail__title__data  {
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

        .thumbnail__prod:hover .thumbnail__title__data  {
            opacity: 1;
        }

        .content__prod .content__data  {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            min-width: 0;
        }

        .content__view {
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

        .thumbnail__img > *  {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .container__prod img {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        .thumbnail__img img  {
            width: 100%;
            height: 100%;
            -o-object-fit: cover;
            object-fit: cover;
        }

        .container__prod span {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        .thumbnail__text {
            position: absolute;
            width: 0;
            overflow: hidden;
            opacity: 0;
        }

        .thumbnail__title__data .thumbnail__data__text  {
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

        .content__prod .content__data a.data__category  {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            min-width: 0;
        }

        .content__prod .content__data .data__status  {
            display: -ms-flexbox;
            display: flex;
            margin-left: 8px;
        }

        .content__view .content__div  {
            display: -ms-inline-flexbox;
            display: inline-flex;
            -ms-flex-align: center;
            align-items: center;
            margin-left: 8px;
        }

        .thumbnail__title__data .data__text  {
            color: #fff;
            font-family: var(--font-face, "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif);
            font-size: 16px;
            font-weight: 500;
            line-height: 22px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .container__prod ul {
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            font-size: 100%;
            vertical-align: baseline;
        }

        .container__prod ul {
            list-style: none;
        }

        .data__container__li  {
            display: -ms-flexbox;
            display: flex;
            -ms-flex: 1;
            flex: 1;
            -ms-flex-pack: end;
            justify-content: flex-end;
        }

        .container__prod a img  {
            border: none;
        }

        .content__prod .content__data .data__thumb  {
            width: 24px;
            height: 24px;
            overflow: hidden;
            border-radius: 50%;
        }

        .content__prod .content__data .data__cat__title  {
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

        .container__prod span.badge__sm {
            padding: 2px 3px;
            border-radius: 3px;
            background: #77cc56;
            color: #3824c5;
            font-size: 10px;
            font-weight: bold;
            line-height: 1;
            text-transform: uppercase;
        }

        .container__prod span.badge__rc {
            padding: 2px 3px;
            border-radius: 3px;
            background: #cc7b44;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            line-height: 1;
            text-transform: uppercase;
        }

        .container__prod span.badge__vm {
            padding: 2px 3px;
            border-radius: 3px;
            background: #cc164c;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            line-height: 1;
            text-transform: uppercase;
        }

        .content__prod .content__data .badge__sm  {
            padding: 2px 3px;
        }

        .container__prod a:hover span.badge__xl,span.badge__xl:hover {
            background-color: rgba(197, 191, 197, 0.79);
        }

        .font-weight-wsy {
            font-weight: 500;
        }

        .col-b1g {
            color: #3d3d4e;
        }

        .content__view .icon  {
            width: 16px;
            height: 16px;
            margin-right: 4px;
            -webkit-transition: color 200ms ease;
            transition: color 200ms ease;
            color: #9e9ea7;
        }

        .data__container__li .data__li  {
            margin-left: 12px;
        }

        .data__container__li .data__li i{
            font-size: 20px;
            padding: 5px;
        }

        .data__btn__y,a.data__btn__y {
            --btn-bg-color: #fff;
            --btn-bg-color-hover: #fff;
            --btn-text-color: #0d0c22;
            --btn-text-color-hover: #6e6d7a;
            --btn-border-color: var(--btn-bg-color);
            --btn-border-color-hover: var(--btn-bg-color-hover);
        }

        .content__view a  {
            display: -ms-flexbox;
            display: flex;
        }

        .data__btn {
            display: -ms-inline-flexbox;
            display: inline-flex;
            position: relative;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .data__btn,a.data__btn {
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

        .data__btn__5,a.data__btn__5 {
            --btn-width: var(--btn-height);
            --btn-padding: 0;
            --btn-border-radius: 50%;
        }

        .data__btn__5 ,a.data__btn__5  {
            --btn-border-radius: 8px;
        }

        .data__container__li .data__pointer  {
            pointer-events: auto;
        }

        .data__btn:before,a.data__btn:before {
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

        .data__btn:after,a.data__btn:after {
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
            .data__btn:hover:not([disabled]),a.data__btn:hover:not([disabled]) {
                border-color: var(--btn-border-color-hover, var(--btn-bg-color-hover));
                background-color: var(--btn-bg-color-hover, var(--btn-bg-color));
                color: var(--btn-text-color-hover, var(--btn-text-color));
            }
        }

        .data__container__li .like-8m3  {
            pointer-events: auto;
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

                        <!--======= CONTAINER PRODUCTS LIST =======-->
                        <article class="container__prod">
                            <!--======= THUMBNAIL =======-->
                            <div class="thumbnail__prod">
                                <figure class="thumbnail__img">
                                    <img src="<?= $productCover ?>">
                                </figure>
                                <div class="thumbnail__title">
                                    <?= $product->title; ?>
                                </div>
                                <a class="thumbnail__title__access" href="<?= url("/produtos/{$product->uri}"); ?>">
                                    <span class="thumbnail__text"><?= $product->title; ?></span>
                                </a>
                                <div class="thumbnail__title__data">
                                    <div class="thumbnail__data__text">
                                        <div class="data__text"><?= $product->title; ?></div>

                                        <ul class="data__container__li">
                                            <li class="data__li">
                                                <a class="data__pointer data__btn data__btn__5 data__btn__y" href="<?= url("/admin/products/product/{$product->id}"); ?>">
                                                    <i class="ri-edit-2-line"></i>
                                                </a>
                                            </li>

                                            <li class="data__li">
                                                <a class="data__btn data__btn__y data__btn__5 like-8m3" title="Remover produto" href="#"
                                                   data-post="<?= url("/admin/products/product"); ?>"
                                                   data-action="delete"
                                                   data-confirm="Tem certeza que deseja deletar esse post?"
                                                   data-product_id="<?= $product->id; ?>">
                                                    <i class="ri-delete-bin-2-fill"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!--======= CONTENTS =======-->
                            <div class="content__prod">
                                <div class="content__data">
                                    <a class="data__category" href="<?= url("/admin/products/categories") ?>">
                                        <img class="data__thumb" width="24" height="24" src="<?= $productCover ?>">
                                        <span class="data__cat__title"><?= $product->category()->title; ?></span>
                                    </a> <a class="data__status" href="#">

                                        <?php if($product->status == "post"): ?>
                                            <span class="badge__sm badge__xl">Publicado</span>
                                        <?php elseif ($product->status == "draft"): ?>
                                            <span class="badge__rc badge__xl">Rascunho</span>
                                        <?php else: ?>
                                            <span class="badge__vm badge__xl">Lixo</span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="content__view">
                                    <div class="content__div">
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