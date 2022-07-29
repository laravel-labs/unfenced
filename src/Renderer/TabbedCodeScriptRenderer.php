<?php

namespace Laravel\Unfenced\Renderer;

use Laravel\Unfenced\Node\TabbedCodeScript;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class TabbedCodeScriptRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        assert($node instanceof TabbedCodeScript);

        $separator = $childRenderer->getInnerSeparator();

        return new HtmlElement(
            'script',
            [],
            [
                $separator,
                file_get_contents(__DIR__ . '/../../js/tabbed-code.js'),
                $separator,
            ]
        );
    }
}
