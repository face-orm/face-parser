<?php
/**
 * @license see LICENSE
 */

namespace Face\Parser;

/**
 * Transforms a source string to tokens defined as regexp
 */
class Lexer
{

    protected $tokensRegexp;
    protected $tokensName;

    /**
     * @param array $tokens the token in an array.
     * Keys are regexp and values are names of tokens
     */
    public function setTokens($tokens)
    {
        $this->tokensName = array_values($tokens);
        $this->tokensRegexp = array_keys($tokens);
    }


    public function tokenize($source)
    {
        $compiledTokens = $this->compileTokens();
        $foundTokens = [];
        $offset = 0;
        $length = strlen($source);
        while ($offset < $length) {
            if (!preg_match($compiledTokens, $source, $matches, null, $offset)) {
                throw new ParsingException(sprintf('Unexpected character "%s"', $source[$offset]));
            }

            // Find the match
            for ($i = 1; '' === $matches[$i];
            ++$i) {
            }

            // TODO line and column
            $line = 0;
            $column = 0;

            $foundTokens[] = new TokenMatch($this->tokensName[$i - 1], $matches[0], $line, $column);

            $offset += strlen($matches[0]);
        }

        return $foundTokens;
    }


    public function compileTokens()
    {

        $compiled = '<(' . implode(")|(", $this->tokensRegexp) . ')>A';

        return $compiled;

    }
}
