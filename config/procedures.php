<?php

// config for Wepa/Procedures
return [
    'backend_menu' => [
        [
            'label' => 'en:Procedures|es:Trámites',
            'icon' => 'document-text',
            'route' => '#',
            'active' => 'admin.procedures*',
            'can' => 'admin.auth',
            'children' => [
                [
                    'label' => 'en:Categories|es:Categorías',
                    'route' => 'admin.procedures.categories.index',
                    'active' => 'admin.procedures.categories*',
                    'can' => 'admin.auth',
                ],
                [
                    'label' => 'en:Procedures|es:Trámites',
                    'route' => 'admin.procedures.procedures.index',
                    'active' => 'admin.procedures.procedures*',
                    'can' => 'admin.auth',
                ],
            ],
        ],
    ],
];
