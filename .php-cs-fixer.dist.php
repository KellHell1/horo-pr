<?php

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixer\{Config, Finder};

// https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/index.rst
$rules = [
    '@PER-CS2.0' => true,
    'array_push' => true,
    'array_syntax' => true,
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'declare_parentheses' => true,
    'declare_strict_types' => true,
    'dir_constant' => true,
    'explicit_indirect_variable' => true,
    'fully_qualified_strict_types' => true,
    'function_to_constant' => true,
    'group_import' => true,
    'include' => true,
    'long_to_shorthand_operator' => true,
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'method_chaining_indentation' => true,
    'modernize_strpos' => true,
    'modernize_types_casting' => true,
    'native_function_casing' => true,
    'native_type_declaration_casing' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_empty_comment' => true,
    'no_extra_blank_lines' => [
        'tokens' => ['curly_brace_block', 'extra'],
    ],
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_spaces_around_offset' => true,
    'no_superfluous_phpdoc_tags' => true,
    'no_unneeded_import_alias' => true,
    'no_unused_imports' => true,
    'no_useless_concat_operator' => true,
    'no_useless_sprintf' => true,
    'no_whitespace_before_comma_in_array' => true,
    'normalize_index_brace' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'object_operator_without_whitespace' => true,
    'operator_linebreak' => ['only_booleans' => true],
    'ordered_imports' => [
        'sort_algorithm' => 'alpha',
        'imports_order' => [
            'const', 'class', 'function',
        ],
    ],
    'ordered_interfaces' => true,
    'ordered_traits' => true,
    'phpdoc_separation' => true,
    'phpdoc_summary' => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'pow_to_exponentiation' => true,
    'return_assignment' => true,
    'self_accessor' => true,
    'simple_to_complex_string_variable' => true,
    'simplified_if_return' => true,
    'single_import_per_statement' => false,
    'single_line_comment_spacing' => true,
    'single_quote' => true,
    'single_space_around_construct' => true,
    'standardize_increment' => true,
    'standardize_not_equals' => true,
    'static_lambda' => true,
    'switch_continue_to_break' => true,
    'ternary_to_null_coalescing' => true,
    'trim_array_spaces' => true,
    'type_declaration_spaces' => true,
    'types_spaces' => true,
    'whitespace_after_comma_in_array' => true,
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/config',
    ])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);