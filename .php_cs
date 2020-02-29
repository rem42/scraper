<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    // ->in(__DIR__.'/tests')
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        'ordered_imports' => true,
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'declare',
                'do',
                'for',
                'foreach',
                'if',
                'switch',
                'try',
            ],
        ],
        'declare_equal_normalize' => true,
        'phpdoc_scalar' => false,
        'phpdoc_summary' => false,
        'concat_space' => ['spacing' => 'one'],
        'binary_operator_spaces' => ['align_equals' => true],
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'no_unreachable_default_argument_value' => false,
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'heredoc_to_nowdoc' => false,
        'increment_style' => ['style' => 'post'],
        'yoda_style' => true,
        'phpdoc_line_span' => [
            'property' => 'single',
            'const' => 'single',
        ],
        'no_superfluous_phpdoc_tags' => true,
        'no_empty_phpdoc' => true,
        'phpdoc_align' => true,
        'phpdoc_order' => true,
    ])
    ->setCacheFile(__DIR__.'/.php_cs.cache')
    ->setFinder($finder)
;
