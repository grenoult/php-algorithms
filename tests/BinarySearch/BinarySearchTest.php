<?php


namespace BinarySearch;

/**
 * ./vendor/bin/phpunit tests/BinarySearch/BinarySearchTest.php
 */
class BinarySearchTest extends \PHPUnit\Framework\TestCase
{
    private $root;

    public function setUp(): void
    {
        // Binary Search Tree
        //      8
        //    /   \
        //   4    10
        //  / \    \
        // 2   6    20

        $this->root = new Node(
            8,
            new Node(
                4,
                new Node(2),
                new Node(6)
            ),
            new Node(
                10,
                null,
                new Node(20)
            )
        );
    }

    public function testInOrderTraversal()
    {
        $this->expectOutputString('2. 4. 6. 8. 10. 20. ');

        Node::inOrderTraversal($this->root);
    }

    public function testSearchNode()
    {
        /** @var Node $result */
        $result = Node::search(2, $this->root);

        $this->assertInstanceOf(Node::class, $result);
        $this->assertSame(2, $result->key);
        $this->assertSame(null, $result->left);
        $this->assertSame(null, $result->right);


        /** @var Node $result */
        $result = Node::search(10, $this->root);

        $this->assertInstanceOf(Node::class, $result);
        $this->assertSame(10, $result->key);
        $this->assertSame(null, $result->left);
        $this->assertInstanceOf(Node::class, $result->right);


        /** @var Node $result */
        $result = Node::search(15, $this->root);

        $this->assertSame(null, $result);
    }

    public function testInsertNode()
    {
        $result = Node::insertInOrder(15, $this->root);

        $this->expectOutputString('2. 4. 6. 8. 10. 15. 20. ');

        Node::inOrderTraversal($result);
    }

    public function testInsertNode2()
    {
        $result = Node::insertInOrder(5, $this->root);

        $this->expectOutputString('2. 4. 5. 6. 8. 10. 20. ');

        Node::inOrderTraversal($result);

        print_r($this->root);
    }
}