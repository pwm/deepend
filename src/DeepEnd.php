<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use Pwm\DeepEnd\Exception\CycleDetected;
use Pwm\DeepEnd\Exception\NodeAlreadyPresent;
use Pwm\DeepEnd\Exception\NodeDoesNotExist;

class DeepEnd
{
    /** @var Node[] */
    private $nodeIdMap = [];

    /** @var Node[][] */
    private $adjacencyList = [];

    public function add(string $nodeId, /* mixed */ $nodeData = null): void
    {
        if ($this->retrieveNode($nodeId) instanceof Node) {
            throw new NodeAlreadyPresent(sprintf('Node %s is already present.', $nodeId));
        }
        $this->nodeIdMap[$nodeId] = new Node($nodeId, $nodeData);
        $this->adjacencyList[$nodeId] = [];
    }

    public function draw(Arrow $arrow): void
    {
        if (! ($fromNode = $this->retrieveNode($arrow->getFromNodeId())) instanceof Node) {
            throw new NodeDoesNotExist(sprintf('Node %s does not exist.', $arrow->getFromNodeId()));
        }
        if (! ($toNode = $this->retrieveNode($arrow->getToNodeId())) instanceof Node) {
            throw new NodeDoesNotExist(sprintf('Node %s does not exist.', $arrow->getToNodeId()));
        }
        if ($this->isReachable($toNode, $fromNode)) {
            throw new CycleDetected(
                sprintf('An arrow from %s to %s would result in a cycle.', $fromNode->getId(), $toNode->getId())
            );
        }

        $this->drawArrow($fromNode, $toNode);
    }

    public function sort(): array
    {
        $nodesSortedByIndex = $this->topologicalSort();
        return array_map(function (Node $node): string {
            return $node->getId();
        }, $nodesSortedByIndex);
    }

    public function sortToMap(): array
    {
        $nodesSortedByIndex = $this->topologicalSort();
        $sortedNodeMap = [];
        foreach ($nodesSortedByIndex as $node) { /** @var Node $node */
            $sortedNodeMap[$node->getId()] = $node->getData();
        }
        return $sortedNodeMap;
    }

    private function retrieveNode(string $nodeId): ?Node
    {
        return $this->nodeIdMap[$nodeId] ?? null;
    }

    private function unvisitNodes(): void
    {
        foreach ($this->nodeIdMap as $node) {
            $node->unvisit();
        }
    }

    private function drawArrow(Node $fromNode, Node $toNode): void
    {
        if (! in_array($toNode, $this->adjacencyList[$fromNode->getId()], true)) {
            $this->adjacencyList[$fromNode->getId()][] = $toNode;
        }
    }

    private function isReachable(Node $fromNode, Node $toNode): bool
    {
        $explore = function (Node $node) use (&$explore): void {
            $node->visit();
            foreach ($this->adjacencyList[$node->getId()] as $nextNode) {
                if (! $nextNode->visited()) {
                    $explore($nextNode);
                }
            }
        };

        $this->unvisitNodes();
        $explore($fromNode);
        return $toNode->visited();
    }

    private function topologicalSort(): array
    {
        $explore = function (Node $node, int $index) use (&$explore): int {
            $node->visit();
            foreach ($this->adjacencyList[$node->getId()] as $nextNode) {
                if (! $nextNode->visited()) {
                    $index = $explore($nextNode, $index);
                }
            }
            $node->setIndex(++$index);
            return $index;
        };

        $this->unvisitNodes();
        $index = 0;
        foreach ($this->nodeIdMap as $node) {
            if (! $node->visited()) {
                $index = $explore($node, $index);
            }
        }

        return self::sortNodeByIndex($this->nodeIdMap);
    }

    private static function sortNodeByIndex(array $nodeIdMap): array
    {
        usort($nodeIdMap, function (Node $a, Node $b): int {
            return $b->getIndex() - $a->getIndex();
        });
        return $nodeIdMap;
    }
}
