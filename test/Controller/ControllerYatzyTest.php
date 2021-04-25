<?php

declare(strict_types=1);

namespace Mos\Controller;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Index.
 */
class ControllerYatzyTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new Yatzy();
        $this->assertInstanceOf("\Mos\Controller\Yatzy", $controller);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testControllerReturnsResponse()
    {
        $controller = new Yatzy();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->startGame();
        $this->assertInstanceOf($exp, $res);
    }
}
