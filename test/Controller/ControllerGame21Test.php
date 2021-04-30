<?php

declare(strict_types=1);

namespace Mos\Controller;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Emeu17\Dice\DiceHand;

/**
 * Test cases for the controller Index.
 */
class ControllerGame21Test extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new Game21();
        $this->assertInstanceOf("\Mos\Controller\Game21", $controller);
    }

    /**
     * Check that the controller returns a response
     * and that session variables are set.
     */
    public function testStartGame()
    {
        $controller = new Game21();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->startGame();
        $this->assertInstanceOf($exp, $res);

        $this->assertEquals($_SESSION["noRounds"], 0);
        $this->assertEquals($_SESSION["compScore"], 0);
        $this->assertEquals($_SESSION["playScore"], 0);
    }

    /**
     * Check that the controller returns a response
     * and that session can be destroyed and new session
     * variables are set.
     * @runInSeparateProcess
     */
    public function testResetGame()
    {
        $controller = new Game21();

        session_start();
        new Session();

        $_SESSION = [
            "key" => "value"
        ];

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->resetGame();
        $this->assertInstanceOf($exp, $res);

        $this->assertEquals($_SESSION["noRounds"], 0);
        $this->assertEquals($_SESSION["compScore"], 0);
        $this->assertEquals($_SESSION["playScore"], 0);
    }

    /**
     * Check that the controller returns a response
     * and that new DiceHand is created.
     */
    public function testProcessStart()
    {
        $controller = new Game21();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->processStart();
        $this->assertInstanceOf($exp, $res);

        $this->assertInstanceOf("\Emeu17\Dice\DiceHand", $_SESSION["diceHand"]);
    }

    /**
     * Check that the controller returns a response
     *
     */
    public function testPlayGame()
    {
        $controller = new Game21();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->playGame();
        $this->assertInstanceOf($exp, $res);
    }

    /**
     * Check that the controller returns a response
     *
     */
    public function testProcessThrow()
    {
        $controller = new Game21();

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->processThrow();
        $this->assertInstanceOf($exp, $res);
    }

    /**
     * Check that the controller returns a response
     * and that number of rounds is increased by 1
     */
    public function testResultGame()
    {
        $controller = new Game21();
        $_SESSION["noRounds"] = 1;

        $exp = "\Psr\Http\Message\ResponseInterface";
        $res = $controller->resultGame();
        $this->assertInstanceOf($exp, $res);

        $this->assertEquals($_SESSION["noRounds"], 2);
    }

    /**
     * Test to get player as winner when sum is 21
     * and check that playerScore is increased by 1
     */
    public function testGetWinner21()
    {
        $controller = new Game21();
        $diceHand = new DiceHand(2);
        $diceHand->setSum(21);
        $_SESSION["playScore"] = 0;

        $exp = "CONGRATULATIONS! You got 21!";
        $res = $controller->getWinner($diceHand);
        $this->assertEquals($exp, $res);

        $this->assertEquals($_SESSION["playScore"], 1);
    }

    /**
     * Test to get player as winner when sum is > 21
     * and check that computerScore is increased by 1
     */
    public function testGetWinnerMoreThan21()
    {
        $controller = new Game21();
        $diceHand = new DiceHand(2);
        $diceHand->setSum(23);
        $_SESSION["compScore"] = 0;

        $exp = "You passed 21 and lost, sum: 23";
        $res = $controller->getWinner($diceHand);
        $this->assertEquals($exp, $res);

        $this->assertEquals($_SESSION["compScore"], 1);
    }

}
