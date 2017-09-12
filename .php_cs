<?php
$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'psr0' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'align_multiline_comment' => true,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'no_trailing_comma_in_singleline_array' => true
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
    )
;

return $config;
