<?php

namespace App\Templates;

class WickedBlocks
{
    /**
     * Initiate properties for template blocks
     */
    public $templateId;
    public $hero = [];

    public function __construct($templateId)
    {
        $this->templateId = $templateId;
    }

    public function getHero($blockId)
    {
        $this->hero = [
            [
                'block_id' => $blockId,
                'field_key' => 'name',
                'field_value' => 'WB : Hero 4',
                'field_type' => 'text',
            ],
            [
                'block_id' => $blockId,
                'field_key' => 'title',
                'field_value' => 'Transform your business with our landing page blocks',
                'field_type' => 'text',
            ],
            [
                'block_id' => $blockId,
                'field_key' => 'description',
                'field_value' => 'Wicked Blocks offers a wide array of both free and premium components specifically designed for Tailwind CSS. Our extensive collection features meticulously crafted Tailwind blocks that cater to various design needs and preferences.',
                'field_type' => 'textarea',
            ],
            [
                'block_id' => $blockId,
                'field_key' => 'link_text',
                'field_value' => 'Read more about the offer',
                'field_type' => 'text',
            ],
            [
                'block_id' => $blockId,
                'field_key' => 'link_url',
                'field_value' => 'https://google.com',
                'field_type' => 'url',
            ]
        ];

        return $this->hero;
    }

    public function createBlock() {}
}
