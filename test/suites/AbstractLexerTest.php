<?php
/**
 * @license see LICENSE
 */
namespace Face\Parser\Test;

use Face\Parser\Lexer;

class LexerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Lexer
     */
    protected $abstractLexer;

    public function setup()
    {
        $this->abstractLexer = new Lexer();
    }


    public function testCompileTokens()
    {
        $tokens = [
            "[0-9]" => "T_DIGIT",
            "[A-Za-z]" => "T_CHAR"
        ];
        $this->abstractLexer->setTokens($tokens);
        $compiledTokens = $this->abstractLexer->compileTokens();
        $this->assertEquals("<([0-9])|([A-Za-z])>A", $compiledTokens);
    }

    public function testTokenizeAlphanumSource()
    {

        $tokens = [
            "[A-Za-z0-9]+"   => "T_ALNUM",
            '\s+'            => "T_WHITESPACE"
        ];
        $this->abstractLexer->setTokens($tokens);
        $tokens = $this->abstractLexer->tokenize("UPPER lower 1   145 4LPhanum");

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
        $this->abstractLexer->tokenize("UPPER lower 1   145 4LPhanum.");

    }
}
