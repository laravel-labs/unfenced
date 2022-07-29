<?php

namespace Laravel\Unfenced;

use Laravel\Unfenced\Renderer\FencedCodeContainerRenderer;
use Laravel\Unfenced\Renderer\TabbedCodeRenderer;
use Laravel\Unfenced\Node\FencedCodeContainer;
use Laravel\Unfenced\Node\TabbedCode;
use Laravel\Unfenced\Node\TabbedCodeScript;
use Laravel\Unfenced\Renderer\TabbedCodeScriptRenderer;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Query;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationBuilderInterface;
use League\Config\ConfigurationInterface;
use Nette\Schema\Expect;

class UnfencedExtension implements ConfigurableExtensionInterface, ConfigurationAwareInterface
{
    protected ConfigurationInterface $config;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, [$this, 'onDocumentParsed']);
        $environment->addRenderer(FencedCodeContainer::class, new FencedCodeContainerRenderer());
        $environment->addRenderer(TabbedCode::class, new TabbedCodeRenderer());
        $environment->addRenderer(TabbedCodeScript::class, new TabbedCodeScriptRenderer());
    }

    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('unfenced', Expect::structure([
            'script' => Expect::bool()->default(true),
        ]));
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    public function onDocumentParsed(DocumentParsedEvent $event): void
    {
        $nodes = (new Query())
            ->where(Query::type(FencedCode::class))
            ->findAll($event->getDocument());

        $codeContainers = [];

        foreach ($nodes as $node) {
            $codeContainer = new FencedCodeContainer(clone $node);
            $node->replaceWith($codeContainer);
            $codeContainers[] = $codeContainer;
        }

        $hasTabs = false;

        foreach ($codeContainers as $codeContainer) {
            $hasTabs = $this->tab($codeContainer) || $hasTabs;
        }

        if ($hasTabs && $this->config->get('unfenced/script')) {
            $event->getDocument()->appendChild(new TabbedCodeScript());
        }
    }

    protected function tab(FencedCodeContainer $node): bool
    {
        if (! $node->parent() instanceof Document || ! $node->hasTab()) {
            return false;
        }

        $firstNode = $node;

        $tabs = [
            clone $firstNode,
        ];

        while ($node->next() instanceof FencedCodeContainer) {
            $node = $node->next();
            $tabs[] = clone $node;
            $node->detach();
        }

        $tabbedCode = new TabbedCode($tabs);

        $firstNode->replaceWith($tabbedCode);

        return true;
    }
}
