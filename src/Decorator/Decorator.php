<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Decorator;

use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\ITag;

abstract class Decorator
{
    /** @var Rule[] */
    protected $rules = [];

    /**
     * @param Rule $rule
     *
     * @return Decorator
     */
    public function addRule(Rule $rule): Decorator
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @param INode[] $nodes
     */
    public function decorate(array $nodes)
    {
        foreach ($this->rules as $rule) {
            foreach ($nodes as $node) {
                $this->decorateNode($rule, $node);
            }
        }
    }

    /**
     * @param Rule  $rule
     * @param INode $node
     */
    protected function decorateNode(Rule $rule, INode $node)
    {
        if (!$node->isMatch($rule->getRequiredClassName(), ...$rule->getRequiredIntents())) {
            return;
        }

        if ($rule->getCallback()) {
            call_user_func($rule->getCallback(), $node);
        }

        if (!($node instanceof ITag)) {
            return;
        }

        $node->appendToClass(...$rule->getDefaultClasses());

        $intentClassMap = $rule->getIntentClassMap();
        if (empty($intentClassMap)) {
            return;
        }

        foreach ($node->getIntents() as $intent) {
            if (!isset($intentClassMap[$intent])) {
                continue;
            }
            $node->appendToClass(...$intentClassMap[$intent]);
        }
    }

    /**
     * @return $this
     */
    abstract public function init(): Decorator;
}
