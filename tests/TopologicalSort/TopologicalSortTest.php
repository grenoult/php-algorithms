<?php

namespace TopologicalSort;

class TopologicalSortTest extends \PHPUnit\Framework\TestCase
{
    private $projects;
    private $dependencies;
    private $possibleResults;

    public function setUp(): void
    {
        // Example: https://en.wikipedia.org/wiki/Topological_sorting
        $this->projects = ['2','3','5','7','8','9','10','11'];
        $this->dependencies = [['11','5'],['11','7'],['8','7'],['8','3'],['2','11'],['9','11'],['9','8'],['10','11'],['10','3']];

        $this->possibleResults = [
            ['5', '7', '3', '11', '8', '2', '9', '10'], // (visual top-to-bottom, left-to-right)
            ['3', '5', '7', '8', '11', '2', '9', '10'], // (smallest-numbered available vertex first)
            ['5', '7', '3', '8', '11', '10', '9', '2'], // (fewest edges first)
            ['7', '5', '11', '3', '10', '8', '9', '2'], // (largest-numbered available vertex first)
            ['5', '7', '11', '2', '3', '8', '9', '10'], // (attempting top-to-bottom, left-to-right)
            ['3', '7', '8', '5', '11', '10', '2', '9'], // (arbitrary)
            ['3', '7', '5', '11', '2', '10', '8', '9'], // (one found during testing)
        ];
    }

    public function testGetBuildOrderByChildrenFirst() {
        $topologicalSort = new TopologicalSort($this->projects, $this->dependencies, TopologicalSort::SORT_CHILDREN_FIRST);

        $result = $topologicalSort->getBuildOrder();

        $this->assertContains($result, $this->possibleResults);
    }

    public function testGetBuildOrderByDFS() {
        $topologicalSort = new TopologicalSort($this->projects, $this->dependencies, TopologicalSort::SORT_DFS);

        $result = $topologicalSort->getBuildOrder();

        $this->assertContains($result, $this->possibleResults);
    }
}
