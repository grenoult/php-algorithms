<?php

namespace Graph;

class Node
{
    /** @var Node[] */
    public $children = [];

    public $name;

    public function __construct($name) {
        $this->name = $name;
    }
}
