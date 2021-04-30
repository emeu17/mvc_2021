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

        $controller->startGame();
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
        // echo(print_r($res));
        $this->assertInstanceOf($exp, $res);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testProcessThrowReturnsResponse()
    {
        $controller = new Yatzy();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->processThrow();
        $this->assertInstanceOf($exp, $res);
    }

    /**
     * Check that the controller returns a response
     * and that setting yatzyRound to 3 makes the
     * method go to a new round
     */
    public function testProcessThrowThreeRounds()
    {
        $controller = new Yatzy();
        $_SESSION["yatzyRound"] = 3;

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->processThrow();
        $this->assertInstanceOf($exp, $res);

        $res = $_SESSION["yatzyRound"];
        $exp = 1;
        $this->assertEquals($exp, $res);

        $this->assertTrue($_SESSION["yatzyNewRound"]);
    }

    /**
     * Check that the controller returns a response
     * and that setting yatzyRound to 3 and set to 6
     * (changes to 7 in method)
     * makes the method go to result page
     */
    public function testProcessThrowThreeRoundsSevenSet()
    {
        $controller = new Yatzy();
        $_SESSION["yatzyRound"] = 3;
        $_SESSION["yatzySet"] = 6;

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->processThrow();
        $this->assertInstanceOf($exp, $res);

        $res = $_SESSION["yatzyRound"];
        $exp = 1;
        $this->assertEquals($exp, $res);
        $res = $_SESSION["yatzySet"];
        $exp = 7;
        $this->assertEquals($exp, $res);

        $this->assertTrue($_SESSION["yatzyNewRound"]);
    }

    /**
     * Check that method printResults prints the correct
     * result string
     */
    public function testPrintResult()
    {
        $controller = new Yatzy();
        $_SESSION["yatzyResult"][1] = [1, 1, 2, 3, 4];

        $res = $controller->printResult(2);
        // echo("Res: " . $res);

        $this->assertEquals($res, "1: 1, 1, 2, 3, 4<br />\n");
    }

    /**
     * Check that method printResults prints the correct
     * result string
     */
    public function testPrintResultStars()
    {
        $controller = new Yatzy();
        $_SESSION["yatzyResult"][1] = [1, 1, 1, 3, 4];

        $res = $controller->printResultStars(2);
        // echo("Res: " . $res);

        $this->assertEquals($res, "1: ***<br />\n");
    }

    /**
     * Check that method counts together correct sum
     * from result of the 6 sets in the yatzy game
     */
    public function testGetSum()
    {
        $controller = new Yatzy();
        $_SESSION["yatzyResult"][1] = [1, 1, 1, 3, 4];
        $_SESSION["yatzyResult"][2] = [2, 2, 2, 3, 4];
        $_SESSION["yatzyResult"][3] = [1, 3, 3, 3, 4];
        $_SESSION["yatzyResult"][4] = [1, 1, 4, 4, 4];
        $_SESSION["yatzyResult"][5] = [1, 5, 5, 5, 4];
        $_SESSION["yatzyResult"][6] = [1, 6, 6, 6, 4];
        $sum = 1*3 + 2*3 + 3*3 + 4*3 + 5*3 + 6*3;

        $res = $controller->getSum($_SESSION["yatzyResult"]);

        $this->assertEquals($res, $sum);
    }

    /**
     * Check that method works with getting a bonus
     */
    public function testGetBonus()
    {
        $controller = new Yatzy();
        $sum = 65;

        $res = $controller->getBonus($sum);
        $bonus = 50;
        $this->assertEquals($res, $bonus);
    }

    /**
     * Check that method works with not getting a bonus
     */
    public function testNotGetBonus()
    {
        $controller = new Yatzy();
        $sum = 60;

        $res = $controller->getBonus($sum);
        $bonus = 0;
        $this->assertEquals($res, $bonus);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testResultReturnsResponse()
    {
        $controller = new Yatzy();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->result();
        $this->assertInstanceOf($exp, $res);
    }

}
