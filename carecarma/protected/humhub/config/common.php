<?php

Yii::setAlias('@webroot', realpath(__DIR__ . '/../../../'));

Yii::setAlias('@app', '@webroot/protected');
Yii::setAlias('@humhub', '@app/humhub');

$config = [
    'name' => 'HumHub',
    'version' => '1.0.0',
    'basePath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
    'bootstrap' => ['log', 'humhub\components\bootstrap\ModuleAutoLoader'],
    'sourceLanguage' => 'en',
    'components' => [
        'moduleManager' => [
            'class' => '\humhub\components\ModuleManager'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_SERVER'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
//                    'logVars' => ['_GET', '_SERVER'],
                    'logVars' =>[],//被收集记录的额外数据如 'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION','_SERVER'],
                    //指定日志策略
                    'categories' => ['MyLog'],
                    //指定日志目录
                    'logFile' => '@app/runtime/logs/Mylog/info.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 100,
                ],
            ],
        ],
        'search' => array(
            'class' => 'humhub\modules\search\engine\ZendLuceneSearch',
        ),
        'i18n' => [
            'class' => 'humhub\components\i18n\I18N',
            'translations' => [
                'base' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@humhub/messages'
                ],
                'security' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@humhub/messages'
                ],
                'error' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@humhub/messages'
                ],
                'widgets_views_markdownEditor' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@humhub/messages'
                ],
            ],
        ],
        'formatter' => [
            'class' => 'humhub\components\i18n\Formatter',
        ],
        /**
         * Deprecated
         */
        'formatterApp' => [
            'class' => 'yii\i18n\Formatter',
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@humhub/views/mail',
            'view' => [
                'class' => '\humhub\components\View',
                'theme' => [
                    'class' => '\humhub\components\Theme',
                    'name' => 'CareCarma-4cacc6'
                ],
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'view' => [
            'class' => '\humhub\components\View',
            'theme' => [
                'class' => '\humhub\components\Theme',
                'name' => 'CareCarma-4cacc6',
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=humhub',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
        ],
    ],
    'params' => [
        'installed' => false,
        'databaseInstalled' => false,
        'dynamicConfigFile' => '@app/config/dynamic.php',
        'moduleAutoloadPaths' => ['@app/modules', '@humhub/modules'],
        'moduleMarketplacePath' => '@app/modules',
        'availableLanguages' => [
            'en' => 'English (US)',
            'en_gb' => 'English (UK)',
            'de' => 'Deutsch',
            'fr' => 'Français',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'pt' => 'Português',
            'pt_br' => 'Português do Brasil',
            'es' => 'Español',
            'ca' => 'Català',
            'it' => 'Italiano',
            'th' => 'ไทย',
            'tr' => 'Türkçe',
            'ru' => 'Русский',
            'uk' => 'українська',
            'el' => 'Ελληνικά',
            'ja' => '日本語',
            'hu' => 'Magyar',
            'nb_no' => 'Nnorsk bokmål',
            'zh_cn' => '中文(简体)',
            'zh_tw' => '中文(台灣)',
            'an' => 'Aragonés',
            'vi' => 'Tiếng Việt',
            'sv' => 'Svenska',
            'cs' => 'čeština',
            'da' => 'dansk',
            'uz' => 'Ўзбек',
            'fa_ir' => 'فارسی',
            'bg' => 'български',
            'sk' => 'slovenčina',
            'ro' => 'română',
            'ar' => 'العربية/عربي‎‎',
            'ko' => '한국어',
            'id' => 'Bahasa Indonesia',
            'lt' => 'lietuvių kalba',
        ],
        'user' => [
            // Minimum username length
            'minUsernameLength' => 4,
            // Administrators can change profile image/banners of alle users
            'adminCanChangeProfileImages' => false
        ],
        'availableRelationship' => [
			'' => '--Select--',
            'Immediate Family' => [
                'dad' => 'father',
                'mon' => 'mother',
                'son' => 'son',
                'dau' => 'daughter',
                'bro' => 'brother',
                'sis' => 'sister',
                'husb' => 'husband',
                'wife' => 'wife',
            ],
            'Different Generations' => [
                'g_g_dad' => 'great grandfather',
                'g_g_mon' => 'great grandmother',
                'g_dad' => 'grandfather',
                'g_mon' => 'grandmother',
                'g_son' => 'grandson',
                'g_dau' => 'granddaughter',
                'g_g_son' => 'great grandson',
                'g_g_dau' => 'great granddaugter',
            ],
            'Extended Relation' => [
                'unc' => 'uncle',
                'aunt' => 'aunt',
                'cous' => 'cousin',
                'nep' => 'nephew',
                'nie' => 'niece'
            ],
            'The In-law' => [
                'dad_i_l' => 'father-in-law',
                'mon_i_l' => 'mother-in-law',
                'son_i_l' => 'son-in-law',
                'dau_i_l' => 'daughter-in-law',
                'bro_i_l' => 'brother-in-law',
                'sis_i_l' => 'sister-in-law'
            ],
            'Other relative' => [
                'fd' => 'friend',
                'nei' => 'neighbour'
            ],
            'Care Giver' => [],
        ],
        'ldap' => [
            // LDAP date field formats
            'dateFields' => [
            //'birthday' => 'Y.m.d'
            ],
        ],
        'formatter' => [
            // Default date format, used especially in DatePicker widgets
            // Deprecated: Use Yii::$app->formatter->dateInputFormat instead.
            'defaultDateFormat' => 'short',
            // Seconds before switch from relative time to date format
            // Set to false to always use relative time in TimeAgo Widget
            'timeAgoBefore' => 172800,
            // Use static timeago instead of timeago js
            'timeAgoStatic' => false,
            // Seconds before hide time from timeago date
            // Set to false to always display time
            'timeAgoHideTimeAfter' => 259200,
        // Optional: Callback for TimageAgo FullDateFormat
        //'timeAgoFullDateCallBack' => function($timestamp) {
        //    return 'formatted';
        //}
        ],
        'humhub' => [
            // Marketplace / New Version Check
            'apiEnabled' => true,
            'apiUrl' => 'https://api.humhub.com',
        ],
        'search' => [
            'zendLucenceDataDir' => '@runtime/searchdb',
        ],
        'curl' => [
            // Check SSL certificates on CURL requests
            'validateSsl' => true,
        ],
    ]
];




return $config;
