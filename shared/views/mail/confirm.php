<?php $this->layout('theme', ["title" => "Confirmação de cadastro", "logo" => $logo]); ?>

<body>

<h2>Bem-vindo, <?= $first_name; ?> a <?= CONF_SITE_NAME; ?></h2>

<p><?= $description; ?></p>

<p>
    <a title='Recuperar Senha' href='<?= $confirm_link; ?>'>CLIQUE AQUI PARA CONFIRMAR CADASTRO!</a>
</p>

<p>
    <b>IMPORTANTE:</b> Para você estar com acesso ao sistema, precisa acessar o link acima!.
</p>

</body>
