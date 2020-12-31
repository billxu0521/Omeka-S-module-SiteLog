<?php
namespace SiteLog;

return  [
    'api_adapters' => [
        'invokables' => [
            'site_log' => Api\Adapter\SiteLogAdapter::class,
        ],
    ],
    'entity_manager' => [
        'mapping_classes_paths' => [
            dirname(__DIR__) . '/src/Entity',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'SiteLog\Controller\Site\SiteLog' => Controller\Site\SiteLogController::class,
        ],
    ],
    'sitelog' => [
        'settings' => [
            'sitelog_inline_public' => '',
        ],
        'trackers' => [
            'default' => Tracker\InlineScript::class,
        ],
    ],
    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'sitelog' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/sitelog[/:action]',
                            'constraints' => [
                                'action' => 'add',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'SiteLog\Controller\Site',
                                'controller' => 'SiteLog',
                                'action' => 'add',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'js_translate_strings' => [
        'No query is set.', // @translate
        'Your search is saved.', // @translate
        'You can find it in your account.', // @translate
    ],
    'savorite' => [
    ],
    ];
