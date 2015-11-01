<?php

namespace Face\Parser\Test;


use Face\Parser\ParsingException;
use Face\Parser\TokenMatch;
use Face\Parser\TokenNavigation;

class TokenNavigationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TokenNavigation
     */
    protected $tokens;

    public function setUp(){

        $this->tokens = new TokenNavigation([

            new TokenMatch("T_FOO", "foo"),
            new TokenMatch("T_WHITESPACE", " "),
            new TokenMatch("T_FOO", "foo"),
            new TokenMatch("T_WHITESPACE", " "),
            new TokenMatch("T_BAR", "bar")

        ]);

    }

    public function testNext(){

        $token = $this->tokens->next();
        $this->assertEquals("T_FOO", $token->getTokenName());
        $token = $this->tokens->next();
        $this->assertEquals("T_WHITESPACE", $token->getTokenName());
        $token = $this->tokens->next();
        $this->assertEquals("T_FOO", $token->getTokenName());
        $token = $this->tokens->next();
        $this->assertEquals("T_WHITESPACE", $token->getTokenName());

        $this->setExpectedException("Face\\Parser\\ParsingException");
        $this->tokens->next();

    }

    public function testLook(){

        $token = $this->tokens->look(0);
        $this->assertEquals("T_FOO", $token->getTokenName());
        $token = $this->tokens->look(1);
        $this->assertEquals("T_WHITESPACE", $token->getTokenName());
        $token = $this->tokens->look(2);
        $this->assertEquals("T_FOO", $token->getTokenName());
        $token = $this->tokens->look(3);
        $this->assertEquals("T_WHITESPACE", $token->getTokenName());
        $token = $this->tokens->look(4);
        $this->assertEquals("T_BAR", $token->getTokenName());

        $this->setExpectedException("Face\\Parser\\ParsingException");
        $this->tokens->look(5);

    }

    public function testCurrent(){
        $token = $this->tokens->current();
        $this->assertEquals("T_FOO", $token->getTokenName());
        $this->tokens->next();
        $token = $this->tokens->current();
        $this->assertEquals("T_WHITESPACE", $token->getTokenName());
    }

    public function testHasNext(){
        $this->assertTrue($this->tokens->hasNext());
        $this->tokens->next();
        $this->assertTrue($this->tokens->hasNext());
        $this->tokens->next();
        $this->assertTrue($this->tokens->hasNext());
        $this->tokens->next();
        $this->assertTrue($this->tokens->hasNext());
        $this->tokens->next();
        $this->assertEquals("T_BAR", $this->tokens->current()->getTokenName());
        $this->assertFalse($this->tokens->hasNext());
    }

    public function testExpectToBe(){
        $this->tokens->expectToBe("T_FOO");
        $this->tokens->expectToBe(["T_FOO", "T_BAR"]);

        try{
            $this->tokens->expectToBe("T_BAR");
            $this->fail("Exception was not thrown");
        }catch(ParsingException $e){}
        try{
            $this->tokens->expectToBe(["T_BAR", "T_BAZ"]);
            $this->fail("Exception was not thrown");
        }catch(ParsingException $e){}

    }

}
