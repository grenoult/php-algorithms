<?php

namespace Graph;

class DirectedGraphTest extends \PHPUnit\Framework\TestCase
{
    public function testFindNode()
    {
        // set up tree
        $s = new Node('s');
        $a = new Node('a');
        $b = new Node('b');
        $c = new Node('c');
        $d = new Node('d');
        $e = new Node('e');
        $f = new Node('f');
        $g = new Node('g');
        $h = new Node('h');
        $i = new Node('i');
        $j = new Node('j');

        $s->children = [$c, $b, $a]; // level 1

        $a->children = [$d]; // level 2
        $b->children = [$h]; // level 2
        $c->children = [$h]; // level 2

        $d->children = [$g]; // level 3
        $h->children = [$f]; // level 3

        $g->children = [$e,$j]; // level 4

        $f->children = [$j]; // level 5

        $e->children = [$i]; // level 6


        $directedGraph = new DirectedGraph();
        $this->assertTrue($directedGraph->findNode($s, $e));
    }
}