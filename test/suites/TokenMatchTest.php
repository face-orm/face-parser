<?php
/**
 * Created by PhpStorm.
 * User: bob
 * Date: 11/1/15
 * Time: 5:59 PM
 */

namespace Face\Parser\Test;

use Face\Parser\TokenMatch;

class TokenMatchTest extends \PHPUnit_Framework_TestCase
{

    public function testIs()
    {

        $tokenMatch = new TokenMatch("T_FOO", "foo", 0, 0);

        $this->assertTrue($tokenMatch->is("T_FOO"));
        $this->assertFalse($tokenMatch->is("T_BAR"));

        $this->assertTrue($tokenMatch->is(["T_FOO", "T_BAR"]));
        $this->assertFalse($tokenMatch->is(["T_BAR", "T_BAZ"]));
    }
}
