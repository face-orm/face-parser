<?php
/**
 * @license see LICENSE
 */

namespace Face\Parser;

class TokenMatch
{

    protected $tokenName;
    protected $tokenValue;
    protected $line;
    protected $column;

    /**
     * Token constructor.
     * @param string $tokenName
     * @param string $tokenValue
     * @param int $line
     * @param int $column
     */
    public function __construct($tokenName, $tokenValue, $line, $column)
    {
        $this->tokenName = $tokenName;
        $this->tokenValue = $tokenValue;
        $this->line = $line;
        $this->column = $column;
    }

    /**
     * @return string
     */
    public function getTokenName()
    {
        return $this->tokenName;
    }

    /**
     * @return string
     */
    public function getTokenValue()
    {
        return $this->tokenValue;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    public function is($tokenName){

        if(is_array($tokenName)){
            return in_array($this->tokenName, $tokenName);
        }else{
            return $tokenName == $this->tokenName;
        }

    }

}