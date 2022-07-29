<?php

namespace Laravel\Unfenced\Node;

use Laravel\Unfenced\Parser\FencedCodeAttributeParser;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Block\AbstractBlock;

class FencedCodeContainer extends AbstractBlock
{
    public array $attributes;

    public function __construct(FencedCode $fencedCode)
    {
        $this->attributes = $this->parseAttributes($fencedCode);

        $this->appendChild($fencedCode);
    }

    public function filename(): ?string
    {
        return $this->attributes['filename'] ?? null;
    }

    public function hasFilename(): bool
    {
        return isset($this->attributes['filename']);
    }

    public function tab(): ?string
    {
        return $this->attributes['tab'] ?? null;
    }

    public function hasTab(): bool
    {
        return isset($this->attributes['tab']);
    }

    protected function parseAttributes(FencedCode $fencedCode)
    {
        return (new FencedCodeAttributeParser())->parse($fencedCode);
    }
}
