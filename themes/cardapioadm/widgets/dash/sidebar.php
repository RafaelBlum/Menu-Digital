<div class="dash_content_sidebar">
    <h3 class="icon-asterisk">dashboard</h3>
    <p class="dash_content_sidebar_desc"><?= $confContent; ?></p>

    <nav>
        <?php
        $nav = function ($icon, $href, $title) use ($app) {
            $active = ($app == $href ? "active" : null);
            $url = url("/admin/{$href}");
            return "<a class=\"ri-{$icon} radius {$active}\" href=\"{$url}\">{$title}</a>";
        };

        echo $nav("cogs", "dash", "Dash");
        echo $nav("cog", "dash/configuracoes", "Configurações");
        echo $nav("cog", "dash/subscriptions", "Inscrições");
        ?>
    </nav>
</div>