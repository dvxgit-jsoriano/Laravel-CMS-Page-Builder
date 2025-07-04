<?php

namespace App\Templates;

use App\Models\Block;

/**
 * This will be the common interface that all templates will implement
 */
interface PageTemplateInterface
{
    public function getDefaultBlockConfig(string $blockType, Block $block): array;
}
