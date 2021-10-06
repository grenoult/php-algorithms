<?php

namespace BinarySearch;

class Node
{
    /** @var Node|null $left */
    public $left;

    /** @var Node|null $right */
    public $right;

    /** @var int $key */
    public $key;

    /**
     * Node constructor.
     *
     * @param int $key
     * @param Node|null $left
     * @param Node|null $right
     */
    public function __construct($key, Node $left = null, Node $right = null)
    {
        $this->key = $key;
        $this->left = $left;
        $this->right = $right;
    }

    public static function inOrderTraversal($node)
    {
        if (!$node instanceof Node) {
            // Exit early because there's nothing to do
            return;
        }

        // Visit left
        self::inOrderTraversal($node->left);

        echo $node->key.'. ';

        // Visit right
        self::inOrderTraversal($node->right);
    }

    /**
     * @param int $key
     * @param Node|null $node
     * @return Node|null
     */
    public static function search(int $key, ?Node $node)
    {
        if (!$node instanceof Node) {
            // Exit early because there's nothing to do
            return null;
        }

        if ($node->key === $key) {
            return $node;
        }

        if ($node->key > $key) {
            return self::search($key, $node->left);
        }

        if ($node->key < $key) {
            return self::search($key, $node->right);
        }

        return null;
    }

    /**
     * @param int $key
     * @param Node|null $node
     * @return Node
     */
    public static function insertInOrder(int $key, ?Node $node) {
        if ($node === null) {
            $node = new Node($key);
            return $node;
        }

        if ($key < $node->key) {
            // go left
            if ($node->left === null) {
                $node->left = self::insertInOrder($key, $node->left);
            } else {
                self::insertInOrder($key, $node->left);
            }
        }

        if ($key > $node->key) {
            // go right
            if ($node->right === null) {
                $node->right = self::insertInOrder($key, $node->right);
            } else {
                self::insertInOrder($key, $node->right);
            }
        }
        return $node;
    }
}