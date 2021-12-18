<?php

$config = new Rem42\CS\Config\Config;
$config->getFinder()
    ->in(
        [
            __DIR__.'/src',
            __DIR__.'/tests',
        ]
    );

return $config;
