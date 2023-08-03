<article class="container post__card">
    <!--=============== CARD POST ===============-->
    <div class="card">

        <!--=============== IMAGE ===============-->
        <div class="card__header">
             <img src="<?= image($post->cover, 500, 300) ?>" alt="Image post">
        </div>

        <!--=========== BODY - DATA =============-->
        <div class="card__body">
            <span class="card__category">
                <a title="<?= $post->category()->title; ?>" href="<?= url("/blog/em/{$post->category()->uri}") ?>">
                    <?= $post->category()->title; ?>
                </a>
            </span>

            <h4>
                <a href="<?= url("/blog/{$post->uri}") ?>" title="<?= str_limit_chars($post->title, 30); ?>">
                    <?= str_limit_chars($post->title, 65); ?>
                </a>
            </h4>

            <p><?= str_limit_chars($post->subtitle, 55); ?></p>
        </div>

        <!--=========== FOOTER =============-->
        <div class="card__footer">

            <!--=========== USER DATA =============-->
            <div class="card__user">
                <?php $userPhoto = ($post->user()->photo() ? image($post->user()->photo(), 300, 300) : theme("/assets/images/avatar.jpg", CONF_VIEW_ADMIN)); ?>
                <img src="<?= $userPhoto; ?>" alt="user__image" class="user__image">
                <div class="user__info">
                    <h5><?= $post->user()->fullName();?></h5>
                    <small><?= date_fmt($post->created_at, "d/m/Y"); ?></small>
                </div>
            </div>

        </div>
    </div>
</article>