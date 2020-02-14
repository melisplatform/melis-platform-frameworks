<?php

return [
    'form-elements' => [
        'elements' => [
            [
                'spec' => [
                    'type' => 'Checkbox',
                    'name' => 'tcf-create-framework-tool',
                    'options' => [
                        'label' => 'tr_melis_platform_frameworks_tcf-create-framework-tool',
                        'tooltip' => 'tr_melis_platform_frameworks_tcf-create-framework-tool tooltip',
                        'switch_options' => [
                            'label-on' => 'tr_melis_platform_frameworks_yes',
                            'label-off' => 'tr_melis_platform_frameworks_no',
                            'icon' => "glyphicon glyphicon-resize-horizontal",
                        ],
                    ],
                    'attributes' => [
                        'id' => 'tcf-create-framework-tool',
                        'required' => 'required',
                    ],
                ],
            ],
            [
                'spec' => [
                    'type' => 'Radio',
                    'name' => 'tcf-tool-framework',
                    'options' => [
                        'label' => 'tr_melis_platform_frameworks_tcf-tool-framework',
                        'tooltip' => 'tr_melis_platform_frameworks_tcf-tool-framework tooltip',
                        'radio-button' => true,
                        'label_options' => [
                            'disable_html_escape' => true,
                        ],
                        'label_attributes' => [
                            'class' => 'melis-radio-box'
                        ],
                        'value_options' => [],
                    ],
                    'attributes' => [
                        'required' => 'required',
                        'class' => 'tcf-tool-type tcf-tool-type-db tcf-tool-type-blank'
                    ],
                ]
            ],
        ],
        'input_filter' => [
            'tcf-create-framework-tool' => [
                'name'     => 'tcf-create-framework-tool',
                'required' => true,
                'validators' => [],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'tcf-tool-framework' => [
                'name'     => 'tcf-tool-framework',
                'required' => false,
                'validators' => [],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
        ]
    ]
];