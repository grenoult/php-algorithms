<?php

namespace TopologicalSort;

use http\Exception\InvalidArgumentException;

/**
 * For example and how to use: https://en.wikipedia.org/wiki/Topological_sorting
 *
 * Class TopologicalSort
 * @package TopologicalSort
 */
class TopologicalSort
{
    const SORT_CHILDREN_FIRST = 1;
    const SORT_DFS = 2;

    /** @var string[] */
    private $projects = [];

    /** @var string[] */
    private $dependencies = [];

    /** @var int $sortingType */
    private $sortingType;

    private $buildOrder = [];

    /**
     * TopologicalSort constructor.
     * @param string[] $projects
     * @param string[][] $dependencies
     * @param int $sortingType
     */
    public function __construct(array $projects, array $dependencies, int $sortingType)
    {
        $this->projects = $projects;
        $this->dependencies = $dependencies;
        $this->sortingType = $sortingType;
    }

    public function getBuildOrder() {
        // Translate project and dependencies into tree(s)
        $nodes = $this->buildNodes();

        // Loop through childless nodes
        if ($this->sortingType === self::SORT_CHILDREN_FIRST) {
            $this->addToBuildOrderByChildren($nodes);
        } elseif ($this->sortingType === self::SORT_DFS) {
            $this->addToBuildOrderByDFS($nodes);
        } else {
            throw new InvalidArgumentException('Invalid sorting type provided.');
        }

        return $this->buildOrder;
    }

    /**
     * This algorithm works the following way:
     *  1. browse the list of nodes
     *  2. if a node has no children
     *   2a. add to build order
     *   2b. remove relationship with other nodes (parents), so that they can be added to the build order too.
     *  3. repeat 1. and 2. until the list of node is empty
     *
     * NOTE:
     * - if we get stuck in an infinite loop, this is because we have a cyclic graph (A -> B -> A). The check is not implemented.
     * - alternatively, we can perform a Depth First Search (DFS), see Forest for an example.
     * - this algorithm is not as intuitive as DFS and can cause confusion. Remember this approach is not recursive
     * and instead uses a queue mechanism.
     *
     * @param Node[] $nodes
     */
    private function addToBuildOrderByChildren($nodes) {
        $node = reset($nodes);
        while (!empty($nodes)) {
            // if no children, execute
            if (empty($node->children)) {
                // Add to queue
                $this->buildOrder[] = $node->name;

                // Remove his parent(s) linkage
                if (!empty($node->parents)) {
                    foreach ($node->parents as $parent) {
                        unset($parent->children[$node->name]);
                    }
                }

                // Remove this node from the list
                unset($nodes[$node->name]);
            }

            // Move to next item
            $node = next($nodes);
            if (!$node instanceof Node) {
                // if out of bound, go back to first item in array
                $node = reset($nodes);
            }
        }
    }

    /**
     * This algorithm works differently:
     *  1. go through each node in array
     *  2. if no children, mark it as `executed`, and add to build order, and remove from array. Same behaviour if all children are
     *      marked as `executed`.
     *  3. recursively call this method until array is empty.
     *
     * NOTE:
     * - if we get stuck in an infinite loop, this is because we have a cyclic graph (A -> B -> A). The check is not implemented.
     * - DFS is a bit more intuitive: we go down a branch until a node can't be executed, then move to the next branch,
     *  and so on.
     *
     * @param Node[] $nodes
     */
    private function addToBuildOrderByDFS(array $nodes) {
        foreach ($nodes as $key => $node) {
            if ($this->markAsExecutedIfNoChildren($node)) {
                unset($nodes[$key]);
            }
        }

        if (!empty($nodes)) {
            $this->addToBuildOrderByDFS($nodes);
        }
    }

    /**
     * From $dependencies and $projects passed in constructor, it builds a graph of Node objects.
     * Then, it's easier to manipulate.
     *
     * @return array
     */
    private function buildNodes() {
        // Build nodes (vertices)
        $nodes = [];
        foreach ($this->projects as $project) {
            $nodes[$project] = new Node();
            $nodes[$project]->name = $project;
        }

        // Build dependencies
        foreach ($this->dependencies as $dependency) {
            // 0 (parent) depends on 1 (child)
            $parent = $nodes[$dependency[0]];
            $child = $nodes[$dependency[1]];

            $parent->children[$child->name] = $child;
            $child->parents[$parent->name] = $parent;
        }

        return $nodes;
    }

    /**
     * Go through the node children. If all children are marked as executed, then mark this node as executed, add it
     * to build order and return true. Return false otherwise.
     *
     * @param Node $node
     * @return bool
     */
    public function markAsExecutedIfNoChildren(Node $node): bool {
        foreach ($node->children as $child) {
            if (!$child->executed) {
                return false;
            }
        }

        $node->executed = true;
        $this->buildOrder[] = $node->name;
        return true;
    }

}

class Node {
    /** @var string */
    public $name;

    /** @var Node[] */
    public $parents = [];

    /** @var Node[] */
    public $children = [];

    /** @var bool $executed */
    public $executed = false;
}
