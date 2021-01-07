<?php
namespace SiteLog;

return  [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
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
            'SiteLog\Controller\Admin\Index' => Controller\Admin\IndexController::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            #Form\ConfigForm::class => Service\Form\QuickSearchFormFactory::class,
            #'SiteLog\Form\ConfigForm' => Service\Form\ConfigFormFactory::class,
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
    'navigation' => [
        'site' => [
            [
                'label' => 'Site Log', // @translate
                'route' => 'admin/site/slug/site-log/default',
                'useRouteMatch' => true,
                'class' => 'fa-list',
                'action' => 'index',
                'pages' => [
                    [
                        'route' => 'admin/site/slug/site-log/default',
                        'visible' => false,
                    ],
                ],
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'sitelog' => [
                        'type' => \Laminas\Router\Http\Segment::class,
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
            
            
            'admin' => [
                'child_routes' => [
                    'site' => [
                        'child_routes' => [
                            'slug' => [
                                'child_routes' => [
                                    'site-log' => [
                                        'type' => 'Literal',
                                        'options' => [
                                            'route' => '/site-log',
                                            'defaults' => [
                                                '__NAMESPACE__' => 'SiteLog\Controller\Admin',
                                                'controller' => 'index',
                                                'action' => 'index',
                                            ],
                                        ],
                                        'may_terminate' => true,
                                        'child_routes' => [
                                            'default' => [
                                                'type' => 'Segment',
                                                'options' => [
                                                    'route' => '/:action',
                                                    'constraints' => [
                                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
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
