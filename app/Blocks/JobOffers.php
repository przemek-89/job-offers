<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class JobOffers extends Block {
    public $name = 'Job offers';
    public $category = 'custom';
    public $icon = 'block-default';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'preview';
    public $align = '';
    public $align_text = '';
    public $align_content = '';
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => true,
        'mode' => 'preview',
        'multiple' => true,
        'jsx' => true,
    ];

    public function with()
    {
        return [
        ];
    }

    public function fields()
    {
        return [];
    }

    public $example = [
        'attributes' => [
            'mode' => 'preview',
            'data' => [
                'preview_image_help' => '/app/Blocks/previews/JobOffers.png',
            ],
        ],
    ];
}
