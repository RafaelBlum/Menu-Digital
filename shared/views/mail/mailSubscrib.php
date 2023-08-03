<?php $this->layout('theme', ["title" => "Confirmação de inscrição", "logo" => $logo]); ?>

<body>

    <h2>
        Bem-vindo, <?= $name; ?> a <?= CONF_SITE_NAME; ?>
    </h2>

    <p>
        <?= $description; ?>
    </p>

    <p>
        <a title='Confirme inscrição' href='<?= $confirm_link; ?>'>CLIQUE AQUI PARA CONFIRMAR SUA INSCRIÇÃO!</a>
    </p>

    <p>
        <b>IMPORTANTE:</b> Para confirmar sua inscrição, você precisa acessar o link acima!.
    </p>

    <p>
        <?= $unsubscribe; ?>
    </p>
</body>
