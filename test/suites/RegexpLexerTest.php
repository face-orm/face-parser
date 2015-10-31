<?php
/**
 * @license see LICENSE
 */
namespace Face\Parser\Test;

use Face\Parser\Exception;
use Face\Parser\RegexpLexer as Lexer;

class RegexpLexerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Lexer
     */
    protected $lexer;

    public function setup()
    {
        $this->lexer = new Lexer();
    }


    public function testCompileTokens()
    {
        $tokens = [
            "[0-9]" => "T_DIGIT",
            "[A-Za-z]" => "T_CHAR"
        ];
        $this->lexer->setTokens($tokens);
        $compiledTokens = $this->lexer->compileTokens();
        $this->assertEquals("<([0-9])|([A-Za-z])>A", $compiledTokens);
    }

    public function testTokenizeAlphanumSource()
    {

        $tokens = [
            "[A-Za-z0-9]+"   => "T_ALNUM",
            '\s+'            => "T_WHITESPACE"
        ];
        $this->lexer->setTokens($tokens);
        $tokens = $this->lexer->tokenize("UPPER lower 1   145 4LPhanum");

        $expected = [

            ["T_ALNUM", "UPPER"],
            ["T_WHITESPACE", " "],
            ["T_ALNUM", "lower"],
            ["T_WHITESPACE", " "],
            ["T_ALNUM", "1"],
            ["T_WHITESPACE", "   "],
            ["T_ALNUM", "145"],
            ["T_WHITESPACE", " "],
            ["T_ALNUM", "4LPhanum"]

        ];

        $this->assertCount(9, $tokens);

        foreach ($expected as $k => $e) {
            $this->assertInstanceOf("Face\\Parser\\TokenMatch", $tokens[$k]);
            $this->assertSame($e[0], $tokens[$k]->getTokenName());
            $this->assertSame($e[1], $tokens[$k]->getTokenValue());
        }

        // Test invalid

        $this->setExpectedException("Face\Parser\ParsingException");
        $this->lexer->tokenize("UPPER lower 1   145 4LPhanum.");

    }

    public function testCaseInsensitive(){
        $tokens = [
            "do something+"   => "T_DO_SOMETHING"
        ];
        $this->lexer->setTokens($tokens);

        try {
            $this->lexer->tokenize("do sOmething");
            $this->fail("Exception was not thrown: regex was case sensitive");
        } catch(Exception $e){}

        $this->lexer->setCaseSensitive(false);
        $tokens = $this->lexer->tokenize("do sOmeTHIng");
        $this->assertCount(1, $tokens);
        $this->assertSame("T_DO_SOMETHING", $tokens[0]->getTokenName());
        $this->assertSame("do sOmeTHIng", $tokens[0]->getTokenValue());

    }



}
