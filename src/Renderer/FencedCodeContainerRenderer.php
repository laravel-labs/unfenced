<?php

namespace Laravel\Unfenced\Renderer;

use Laravel\Unfenced\Node\FencedCodeContainer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class FencedCodeContainerRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        assert($node instanceof FencedCodeContainer);

        $separator = $childRenderer->getInnerSeparator();

        return new HtmlElement(
            'div',
            array_filter([
                'class' => 'code-container',
                'data-tab' => $node->tab(),
            ]),
            [
                $separator,
                $this->renderFilename($node, $separator),
                $node->hasFilename() ? $separator : null,
                $childRenderer->renderNodes($node->children()),
                $separator,
            ]
        );
    }

    protected function renderFilename(FencedCodeContainer $node)
    {
        if (! $node->hasFilename()) {
            return null;
        }

        return new HtmlElement(
            'div',
            ['class' => 'code-container-filename'],
            [
                $node->filename()
            ],
        );
    }
}
