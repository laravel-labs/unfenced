<?php

namespace Laravel\Unfenced\Node;

use League\CommonMark\Node\Block\AbstractBlock;

class TabbedCode extends AbstractBlock
{
    public readonly string $group;

    /**
     * @param array<FencedCodeContainer> $children
     */
    public function __construct(array $children)
    {
        $this->group = $this->generateTabHash($children);

        $this->replaceChildren($children);
    }

    /**
     * @param array<FencedCodeContainer> $children
     */
    protected function generateTabHash(array $children)
    {
        return md5(implode('', array_map(fn (FencedCodeContainer $child) => $child->tab(), $children)));
    }
}
