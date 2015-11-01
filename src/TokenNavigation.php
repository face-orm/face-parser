<?php
/**
 * @license see LICENSE
 */

namespace Face\Parser;

use Traversable;

class TokenNavigation
{

    /**
     * @var TokenMatch[]
     */
    protected $tokens;
    protected $pointer = 0;

    /**
     * @param TokenMatch[] $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Moves the internal pointer to the next token
     * @throws ParsingException
     * @return TokenMatch|null
     */
    public function next()
    {
        if (isset($this->tokens[$this->pointer + 1])) {
            $this->pointer++;
            return $this->tokens[$this->pointer - 1];
        }
        throw new ParsingException("Unexpected end of file");
    }

    /**
     * get the next token without moving the internal pointer
     * @param int $number
     * @throws ParsingException
     * @return TokenMatch|null
     */
    public function look($number = 1)
    {
        $number = $this->pointer + $number;
        if (isset($this->tokens[$number])) {
            return $this->tokens[$number];
        }
        throw new ParsingException("Unexpected end of file");
    }

    public function current()
    {
        return $this->tokens[$this->pointer];
    }

    public function hasNext()
    {
        return $this->pointer + 1 < count($this->tokens);
    }

    public function expectToBe($tokenType, $number = 0)
    {
        $token = $this->look($number);
        if (!$token->is($tokenType)) {
            if (is_array($tokenType)) {
                $tokenMessage = "one of " . implode(", ", $tokenType);
            } else {
                $tokenMessage = $tokenType;
            }
            $currentTokenName = $token->getTokenName();
            throw new ParsingException("Invalide token. Found $currentTokenName but expected $tokenMessage ");
        }
    }
}
