<?php

namespace Graph;

class DirectedGraph
{
    /**
     * @param Node $s
     * @param Node $e
     * @return bool
     */
    public function findNode(Node $s, Node $e, $visitedNodes = [])
    {
        // Depth First Search
        $visitedNodes = [];
        echo ' ->'.$s->name;
        foreach ($s->children as $child) {
            if ($child === $e) {
                return true;
            }

            $nodeFound = $this->findNode($child, $e, $visitedNodes);

            if ($nodeFound) {
                return true;
            }
//            $visitedNodes[] = $child;
//            return $this->findNode($child, $e, $visitedNodes);
        }
        return false;

        // Breadth First Search
//        $queue = [$s];
//
//        while (array_key_exists(0, $queue)) {
//            $currentNode = array_shift($queue);
//            echo '->'.$currentNode->name;
//            foreach ($currentNode->children as $child) {
//                if ($child === $e) {
//                    return true;
//                }
//                $queue[] = $child;
//            }
//        }
//        return false;
    }
}