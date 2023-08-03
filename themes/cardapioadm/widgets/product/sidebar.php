<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">dashboard/cardápio</h3>
    <p class="dash_content_sidebar_desc">Aqui você gerencia todo seu cardápio e suas categorias...</p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"icon-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("pencil-square-o", "products/home", "Cardápio");
        echo $nav("bookmark", "products/categories", "Categorias");
        echo $nav("plus-circle", "products/product", "Novo produto");
        ?>

        <?php if (!empty($product->cover)): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= image($product->cover, 680); ?>"/>
        <?php endif; ?>

        <?php if (!empty($category->cover)): ?>
            <img class="radius" style="width: 100%; margin-top: 30px" src="<?= image($category->cover, 680); ?>"/>
        <?php endif; ?>
    </nav>
</div>