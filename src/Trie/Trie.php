<?php

namespace Trie;

/**
 * Trie (also called 'Prefix tree') is a type of tree used for dictionaries. It's useful to find if a word exists
 * and if a matching prefix exists too.
 * If we want to store only a dictionary and make sure words are correct, then we can simply use a hash table.
 *
 * The biggest advantage of this tree is the possibility to look for words AND prefixes, whereas a hash table
 * wouldn't be able to look for prefixes.
 *
 * Complexity: O(n) where `n` is the length of the string.
 *
 * Class Trie
 * @package Trie
 */
class Trie
{
    /** @var Node $root */
    public $root;

    /**
     * Trie constructor.
     */
    public function __construct()
    {
        $this->root = new Node();
    }

    /**
     * Add a word to the dictionary.
     *
     * Under the hood, it will break down the string into an array of characters, use this array as a queue and add
     * each character recursively until the queue is empty.
     *
     * @param $word
     * @param null $currentNode
     */
    public function addWord($word, $currentNode = null) {
        // If string, convert to array
        if (is_string($word)) {
            $word = str_split($word);
        }

        if ($currentNode === null) {
            $currentNode = $this->root;
        }

        if (count($word) == 0) {
            $currentNode->isWord = true;
            return; // end of the word, exit now
        }

        $newCharacter = array_shift($word);

        // Check if we can add in existing child
        foreach ($currentNode->children as $child) {
            if ($child->value === $newCharacter) {
                $this->addWord($word, $child);
                return; // let's exit now as we added to the existing character
            }
        }

        // Otherwise, add a new child
        $newNode = new Node();
        $newNode->value = $newCharacter;
        $currentNode->children[] = $newNode;
        $this->addWord($word, $newNode);
    }

    /**
     * Builds all words from trie, then returns them in an array.
     *
     * @return string[]
     */
    public function getWords(): array {
        $words = [];
        $this->buildWord($this->root, $words);
        return $words;
    }

    /**
     * Check if given $value is a prefix and return true if a word starts with this string. False otherwise.
     *
     * @param string|string[] $value
     * @param Node|null $node
     * @return bool
     */
    public function isPrefix($value, Node $node = null): bool
    {
        // If string, convert to array
        if (is_string($value)) {
            $value = str_split($value);
        }

        if (!$node instanceof Node) {
            $node = $this->root;
        }

        $character = array_shift($value);
        if ($character === null) {
            // we are facing an empty string: either we provided an empty one or we couldn't find a valid prefix
            return false;
        }

        foreach ($node->children as $child) {
            if ($child->value === $character) {
                // if we just took last item from array, character is part of a word and it's not a full word,
                // then it's a prefix
                if (empty($value) && !$child->isWord) {
                    return true;
                }

                // Otherwise keep looking at children
                return $this->isPrefix($value, $child);
            }
        }

        // We couldn't find a character in the children, so this is not a valid prefix
        return false;
    }

    /**
     * Go through each node's children and build words.
     *
     * @param Node $node
     * @param string[] $words array of words; it's passed by reference so that we always add elements to it
     * @param string[] $currentWord
     */
    private function buildWord(Node $node, &$words = [], $currentWord = []) {
        $currentWord[] = $node->value;
        if ($node->isWord) {
            $words[] = implode($currentWord);
        }

        foreach ($node->children as $child) {
            $this->buildWord($child, $words, $currentWord);
        }
    }
}

class Node {
    /** @var Node[] array List of next characters. */
    public $children = [];

    /** @var string $value Character stored. */
    public $value;

    /** @var bool $isWord True if it's a complete word, false otherwise. */
    public $isWord = false;
}