<?php
/**
 * @license see LICENSE
 */

namespace Face\Parser;

/**
 * Transforms a source string to tokens defined as regexp
 */
class RegexpLexer
{

    protected $tokensRegexp;
    protected $tokensName;
    protected $ignoredTokens = [];

    protected $caseSensitive = true;

    /**
     * @param array $tokens the token in an array.
     * Keys are regexp and values are names of tokens
     */
    public function setTokens($tokens)
    {
        $this->tokensName = array_values($tokens);
        $this->tokensRegexp = array_keys($tokens);
    }

    public function addIgnoredToken($token){
        $this->ignoredTokens[] = $token;
    }

    /**
     * @return boolean
     */
    public function isCaseSensitive()
    {
        return $this->caseSensitive;
    }

    /**
     * @param boolean $caseSensitive
     */
    public function setCaseSensitive($caseSensitive)
    {
        $this->caseSensitive = $caseSensitive;
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
            $count = count($matches);
            $match = null;
            for ($i = 1; $i < $count; $i++) {
                if ('' !== $matches[$i]) {
                    $match = $matches[$i];
                    break;
                }
            }

            if (null === $match) {
                throw new ParsingException(sprintf('Unexpected character "%s"', $source[$offset]));
            }

            $tokenName = $this->tokensName[$i - 1];
            if(!in_array($tokenName, $this->ignoredTokens)){
                // TODO line and column
                $line = 0;
                $column = 0;

                $foundTokens[] = new TokenMatch($tokenName, $matches[0], $line, $column);
            }
            $offset += strlen($matches[0]);
        }

        return $foundTokens;
    }


    public function compileTokens()
    {

        $compiled = '<(' . implode(")|(", $this->tokensRegexp) . ')>A';

        if(false == $this->caseSensitive){
            $compiled .= "i";
        }

        return $compiled;

    }
}
