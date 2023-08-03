<?php

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

/**
 * Ajustar minificação de ativos CSS/JS:
 *
*/
if(strpos(url(), "localhost")){

    /**
     * Minificando CSS
    */
    $minCSS = new CSS();
    $dirCSS = scandir(__DIR__ . "/../../../themes/");

    foreach ($dirCSS as $css)
    {
        $cssFile = __DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/{$css}";
        if(is_file($cssFile) && pathinfo($cssFile)['extension'] == 'css'){
            $minCSS->add($cssFile);
        }
    }
    $minifiedPathcss = __DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/style-minificado.css";
    $minCSS->minify($minifiedPathcss);

    /**
     * Minificando JS
     * add dir shared
     */
    $minJS = new JS();
    $minJS->add(__DIR__ . "/../../../shared/js/scrollreveal.min.js");
    $minJS->add(__DIR__ . "/../../../shared/js/jquery.min.js");
    $minJS->add(__DIR__ . "/../../../shared/js/jquery.form.js");
    $minJS->add(__DIR__ . "/../../../shared/js/jquery-ui.js");

    $dirJS = scandir(__DIR__ . "/../../../themes/");
    foreach ($dirJS as $js) {
        $jsFile = __DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/{$js}";
        if(is_file($jsFile) && pathinfo($jsFile)['extension'] == 'js'){
            $minJS->add($jsFile);
        }
    }

    $minifiedPathjs = __DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/script-minificado.js";
    $minJS->minify($minifiedPathjs);
}