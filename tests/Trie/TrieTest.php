<?php


namespace Trie;

class TrieTest extends \PHPUnit\Framework\TestCase
{
    public function testAddWord()
    {
        $trie = new Trie();
        $trie->addWord('tea');
        $trie->addWord('ten');
        $trie->addWord('in');
        $trie->addWord('inn');
        $trie->addWord('to');

        $this->assertContains('tea', $trie->getWords());
        $this->assertContains('ten', $trie->getWords());
        $this->assertContains('in', $trie->getWords());
        $this->assertContains('inn', $trie->getWords());
        $this->assertContains('to', $trie->getWords());
    }

    /**
     * @dataProvider isPrefixData
     * @param $value
     * @param $expectedResult
     */
    public function testIsPrefix($value, $expectedResult)
    {
        $trie = new Trie();
        $trie->addWord('tea');
        $trie->addWord('ten');

        $result = $trie->isPrefix($value);

        $this->assertSame($expectedResult, $result);

    }

    public function isPrefixData()
    {
        return [
            'Test valid prefix' => [
                'te', // prefix value
                true // expected result
            ],
            'Test invalid prefix 1' => [
                'xx', // prefix value
                false // expected result
            ],
            'Test invalid prefix 2' => [
                'tx', // prefix value
                false // expected result
            ],
            'Test invalid prefix 3' => [
                '', // prefix value
                false // expected result
            ],
        ];
    }
}