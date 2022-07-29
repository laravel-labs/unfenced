<?php

namespace Laravel\Unfenced\Renderer;

use Laravel\Unfenced\Node\FencedCodeContainer;
use Laravel\Unfenced\Node\TabbedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class TabbedCodeRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        assert($node instanceof TabbedCode);

        $separator = $childRenderer->getInnerSeparator();

        return new HtmlElement(
            'div',
            [
                'class' => 'tabbed-code',
                'data-group' => $node->group,
            ],
            [
                $separator,
                $this->renderTabNavigation($node, $separator),
                $separator,
                new HtmlElement(
                    'div',
                    ['class' => 'tabbed-code-body'],
                    [
                        $separator,
                        $childRenderer->renderNodes($node->children()),
                        $separator,
                    ]
                ),
                $separator,
            ]
        );
    }

    protected function renderTabNavigation(TabbedCode $node, string $separator)
    {
        return new HtmlElement(
            'div',
            ['class' => 'tabbed-code-nav'],
            [
                $separator,
                ...array_map(
                    fn (FencedCodeContainer $code) => new HtmlElement(
                        'button',
                        [
                            'class' => implode(' ', array_filter([
                                'tabbed-code-nav-button',
                                $code->previous() === null ? 'active' : null
                            ])),
                            'data-tab' => $code->tab(),
                            'onClick' => "setTab('{$code->tab()}', '{$node->group}')",
                        ],
                        $code->tab(),
                    ) . $separator,
                    $node->children()
                ),
            ]
        );
    }
}
