<?php
return [
    'cache_types' => [
        'compiled_config' => 1
    ],
    'MAGE_MODE' => 'production',
    'system' => [
        'default' => [
            'dev' => [
                'js' => [
                    'enable_js_bundling' => 0,
                    'merge_files' => 0,
                    'minify_files' => 0,
                ],
                'css' => [
                    'merge_css_files' => 1,
                    'minify_files' => 1,
                ],
                'template' => [
                    'minify_html' => 1,
                ],
            ],
        ]
    ]
];
