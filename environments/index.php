<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */

return [

    'Development' => [
        'path' => 'dev',
        /// SKIP
        'skipFiles' => [
            'frontend/config',
            '/composer.lock'
        ],

        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',

            'frontend/runtime',
            'frontend/web/assets',
            'frontend/web/assets/reports',
            'frontend/web/photo',
            'frontend/web/photo/ident_pe',

            'vendor/mpdf/mpdf/tmp',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
            'composer.lock',
            'mobile/web/',

            'frontend/web/fonts',
            'frontend/web/css',
            'frontend/web/js',
        ],

        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
        ],
    ],


    'Production' => [
        'path' => 'prod',

        /// SKIP
        'skipFiles'  => [
            'frontend/config',
            '/composer.lock'
        ],

        ///777
        'setWritable' => [
            'vendor/mpdf/mpdf/tmp',

            'frontend/web/assets/reports',


            'frontend/web/photo/ident_pe',
            'frontend/web/photo/mts_change_old',
            'frontend/web/photo/mts_change_new',

            'frontend/web/assets',

        ],

        ///755
        'setExecutable' => [
            'yii',
            'mobile/web/',

            'backend/runtime',
            'backend/web/assets',

            'frontend/runtime',

            'frontend/web/fonts',
            'frontend/web/css',
            'frontend/web/js',


            'frontend/web/photo',



        ],

        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
        ],
    ],
];
