Face Parser
===========

[![Latest Stable Version](https://poser.pugx.org/face/parser/v/stable)](https://packagist.org/packages/face/parser)
[![Build Status](https://travis-ci.org/face-orm/face-parser.svg?branch=master)](https://travis-ci.org/face-orm/face-parser)
[![Test Coverage](https://codeclimate.com/github/face-orm/face-parser/badges/coverage.svg)](https://codeclimate.com/github/face-orm/face-parser/coverage)

Tool to help to create a parser with php. Originally built to parse FQL queries but might be used for anything else.


Usage
-----

```php
    use Face\Parser\RegexpLexer as Lexer;
    use Face\Parser\TokenNavigation;
    
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
    
    $string = 'class foo{
        function bar(){}
    }';
    
    $tokenArray = $lexer->tokenize($string);
    
    $tokens = new TokenNavigation($tokenArray);
    
    // Check if the first token is a "class" keyword or throws an exception
    $tokens->expectToBe("T_CLASS");
    
    $tokens->next();
    $tokens->expectToBe("T_IDENTIFIER");
    
    $className = $tokens->current()->getTokenValue();
    
    // while next token is a function keyword
    while($tokens->hasNext() && $tokens->look(1)->is("T_FUNCTION")){
        // Parse the function body
        someFunctionThatParsesTheFunctionAndJumpsToTheNextToken($tokens);
    }
    
```
