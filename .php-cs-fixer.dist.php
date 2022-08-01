<?php

$config = new Rem42\CS\Config\Config;
$config
    ->addMoreRules([
        'declare_strict_types' => true,
    ])
    ->getFinder()
    ->in(
        [
            __DIR__.'/src',
            __DIR__.'/tests',
        ]
    );

return $config;
