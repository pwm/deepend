<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use Pwm\DeepEnd\Exception\CycleDetected;

class DeepEnd
{
    /** @var Node[] */
    private $nodeIdMap = [];

    /** @var Node[][] */
    private $adjacencyList = [];

    public function add(Arrow $arrow): void
    {
        $fromNode = $this->deduplicateNode($arrow->getFrom());
        $toNode = $this->deduplicateNode($arrow->getTo());
        $this->addNode($fromNode);
        $this->addNode($toNode);
        $this->connect($fromNode, $toNode);
    }

    public function sort(): array
    {
        foreach ($this->nodeIdMap as $node) {
            $node->unvisit();
        }

        $this->depthFirstSearch();

        return self::topologicalSort($this->nodeIdMap);
    }

    private function deduplicateNode(Node $node): Node
    {
        return array_key_exists($node->getId(), $this->nodeIdMap)
            ? $this->nodeIdMap[$node->getId()]
            : $node;
    }

    private function addNode(Node $node): void
    {
        if (! array_key_exists($node->getId(), $this->nodeIdMap)) {
            $this->nodeIdMap[$node->getId()] = $node;
            $this->adjacencyList[$node->getId()] = [];
        }
    }

    private function connect(Node $fromNode, Node $toNode): void
    {
        if (! in_array($toNode, $this->adjacencyList[$fromNode->getId()], true)) {
            $this->adjacencyList[$fromNode->getId()][] = $toNode;
        }
        $this->ensureDAG($fromNode, $toNode);
    }

    private function ensureDAG(Node $fromNode, Node $toNode): void
    {
        //@todo: ensure there's no path from $toNode to $fromNode
        //throw new CycleDetected();
    }

    private function depthFirstSearch(): void
    {
        $explore = function (Node $node, int $index) use (&$explore): int {
            $node->visit();
            $nodeAdjacencyList = $this->adjacencyList[$node->getId()];
            foreach ($nodeAdjacencyList as $nextNode) {
                if (! $nextNode->visited()) {
                    $index = $explore($nextNode, $index);
                }
            }
            $index++;
            $node->setIndex($index);
            return $index;
        };

        $index = 0;
        foreach ($this->nodeIdMap as $node) {
            if (! $node->visited()) {
                $index = $explore($node, $index);
            }
        }
    }

    private static function topologicalSort(array $nodeIdMap): array
    {
        uasort($nodeIdMap, function (Node $v1, Node $v2): int {
            return $v2->getIndex() - $v1->getIndex();
        });

        return array_map(function (Node $v): string {
            return $v->getId();
        }, $nodeIdMap);
    }
}
