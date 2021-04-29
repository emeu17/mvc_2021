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
     * and that session key yatzyHand is not set
     */
    public function testStartGameReturnsResponse()
    {
        $controller = new Yatzy();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->startGame();
        $this->assertInstanceOf($exp, $res);
        $this->assertArrayNotHasKey("yatzyHand", $_SESSION);
    }

    /**
     * Check that the controller returns a response
     * and that setting session key yatzyHand and then
     * starting a game results in the session key being unset
     */
    public function testStartGameDestroyedSession()
    {
        $controller = new Yatzy();
        $_SESSION["yatzyHand"] = "Test123";

        $this->assertArrayHasKey("yatzyHand", $_SESSION);

        $res = $controller->startGame();
        $this->assertArrayNotHasKey("yatzyHand", $_SESSION);
    }


    /**
     * Check that the controller returns a response.
     */
    public function testProcessStartReturnsResponse()
    {
        $controller = new Yatzy();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->processStart();
        $this->assertInstanceOf($exp, $res);
    }

    /**
     * Check that method processStart sets session variables
     * and that they contain correct values
     */
    public function testProcessStartSessionVars()
    {
        $controller = new Yatzy();


        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->processStart();
        $this->assertInstanceOf($exp, $res);

        $this->assertArrayHasKey("yatzyRound", $_SESSION);
        $this->assertArrayHasKey("yatzySet", $_SESSION);
        $this->assertArrayHasKey("yatzyResult", $_SESSION);
        $this->assertArrayHasKey("yatzyNewRound", $_SESSION);

        $this->assertEquals($_SESSION["yatzyRound"], 1);
        $this->assertEquals($_SESSION["yatzySet"], 1);
        $this->assertEmpty($_SESSION["yatzyResult"]);
        $this->assertTrue($_SESSION["yatzyNewRound"]);

    }

    /**
     * Check that the controller returns a response.
     */
    public function testPlayGameReturnsResponse()
    {
        $controller = new Yatzy();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->playGame();
        echo(print_r($res));
        $this->assertInstanceOf($exp, $res);
    }

    // /**
    //  * Check that the controller returns a response.
    //  */
    // public function testPlayGameReturnsResponse()
    // {
    //     $controller = new Yatzy();
    //
    //     $exp = "\Psr\Http\Message\ResponseInterface";
    //     $res = $controller->playGame();
    //     echo(print_r($res));
    //     $this->assertInstanceOf($exp, $res);
    // }
}
