<?php

namespace Face\Parser\Test;

use Face\Parser\RegexpLexer as Lexer;

class scenarioClassWithMethodParseTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildingClassAndMethod()
    {
        $lexer = new Lexer();

        $lexer->setTokens([

            "function"                  => "T_FUNCTION",
            "class"                     => "T_CLASS",
            "[a-zA-Z_][a-zA-Z0-9_]*"    => "T_IDENTIFIER",
            "\\{"                       => "T_L_BRACKET",
            "\\}"                       => "T_R_BRACKET",
            "\\("                       => "T_L_PARENTHESIS",
            "\\)"                       => "T_R_PARENTHESIS",
            ";"                         => "T_SEMICOLON",
            "\\s+"                      => "T_WHITESPACE"

        ]);

        $string = 'class blabla{

            function(){;}}';

        $tokens = $lexer->tokenize($string);

        $expected = [

            ["T_CLASS", "class"],
            ["T_WHITESPACE", " "],
            ["T_IDENTIFIER", "blabla"],
            ["T_L_BRACKET", "{"],
            ["T_WHITESPACE", "

            "],
            ["T_FUNCTION", "function"],
            ["T_L_PARENTHESIS", "("],
            ["T_R_PARENTHESIS", ")"],
            ["T_L_BRACKET", "{"],
            ["T_SEMICOLON", ";"],
            ["T_R_BRACKET", "}"],
            ["T_R_BRACKET", "}"]

        ];

        $this->assertCount(count($expected), $tokens);

        foreach ($expected as $k => $e) {
            $this->assertInstanceOf("Face\\Parser\\TokenMatch", $tokens[$k]);
            $this->assertSame($e[0], $tokens[$k]->getTokenName());
            $this->assertSame($e[1], $tokens[$k]->getTokenValue());
        }
    }
}
