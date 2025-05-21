<?php

use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap', 'storage', 'vendor', 'node_modules', '.local'])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config()->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect());

return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => [
        'default' => 'single_space',
        'operators' => [
            '=' => null,
        ],
    ],
    'blank_line_before_statement' => [
        'statements' => [
            'break',
            'continue',
            'declare',
            'exit',
            'for',
            'foreach',
            'if',
            'return',
            'switch',
            'throw',
            'try',
            'while',
        ],
    ],
    'concat_space' => [
        'spacing' => 'one',
    ],
    'type_declaration_spaces' => true,
    'native_function_casing' => true,
    'no_extra_blank_lines' => true,
    'no_leading_namespace_whitespace' => true,
    'no_spaces_around_offset' => true,
    'no_unused_imports' => true,
    'object_operator_without_whitespace' => true,
    'single_quote' => true,
    'trailing_comma_in_multiline' => true,
    'unary_operator_spaces' => true,
    'whitespace_after_comma_in_array' => true,
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'single_trait_insert_per_statement' => true,
    'class_attributes_separation' => true,
])
    ->setUsingCache(false)
    ->setLineEnding(PHP_EOL)
    ->setFinder($finder);
