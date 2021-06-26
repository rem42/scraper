<?php

$config = new Rem42\CS\Config\Php74;
$config->getFinder()
    ->in([
       __DIR__.'/src',
       __DIR__.'/tests',
    ]);

return $config;
