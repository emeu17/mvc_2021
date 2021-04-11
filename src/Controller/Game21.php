<?php

declare(strict_types=1);

namespace Mos\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

use function Mos\Functions\{
    redirectTo,
    renderView,
    sendResponse,
    url,
    destroySession
};

use Emeu17\Dice\Dice;
use Emeu17\Dice\DiceHand;


/**
 * Class Game. Used to start up, play and
 * print out the result of a dice game
 */
class Game21
{
    /**
     * Shows first page for starting a new game
     * Also retrieves data from session to print out scoreboard
     */
    public function startGame(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        if (!isset($_SESSION["noRounds"])) {
            $_SESSION["noRounds"] = 0;
            $_SESSION["compScore"] = 0;
            $_SESSION["playScore"] = 0;
        }
        $data = [
            "header" => "Game 21",
            "message" => "Play 21 against the computer.",
            "playerScore" => $_SESSION["playScore"],
            "computerScore" => $_SESSION["compScore"],
            "rounds" => $_SESSION["noRounds"],
            "action" => url("/startGame/process"),
        ];
        $body = renderView("layout/startGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    /**
     * Destroys current session
     * Initiates no of rounds and computer + player score to zero
     */
    public function resetGame(): ResponseInterface
    {
        destroySession();

        $_SESSION["noRounds"] = 0;
        $_SESSION["compScore"] = 0;
        $_SESSION["playScore"] = 0;

        return (new Response())
            ->withStatus(301)
            ->withHeader("Location", url("/startGame"));
    }

    /**
     * Initiates diceHand if it doesnt already exists
     * Starts new game with chosen amount of dices
     */
    public function processStart(): ResponseInterface
    {
        if (!isset($_SESSION["diceHand"])) {
            $_SESSION["diceHand"] = new DiceHand((int) $_POST["dices"]);
        }

        return (new Response())
            ->withStatus(301)
            ->withHeader("Location", url("/diceGame"));
    }

    /**
     * Play a started game, possible to roll dice(s)
     * or stop at current sum of dice(s)
     *
     */
    public function playGame(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $callable = $_SESSION["diceHand"];
        $data = [
            "header" => "Game 21: Throw dices",
            "message" => "Choose to throw dice(s) or stop at current sum",
            "action" => url("/diceGame/process2"),
        ];
        $data["throw"] = $callable->getLastRoll();
        $data["sum"] = $callable->getSum();
        $body = renderView("layout/diceGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    /**
     * Checks which button has been pressed
     * If Stop round redirect to result
     * otherwise throw dices, redirect to
     * result or back to game page
     */
    public function processThrow(): ResponseInterface
    {
        $stop = $_POST["stop"] ?? null;
        if ($stop) {
            return (new Response())
                ->withStatus(301)
                ->withHeader("Location", url("/diceGame/result"));
        }
        $callable = $_SESSION["diceHand"];
        $callable->roll();
        $_SESSION["diceHand"] = $callable;
        if ($callable->getSum() >= 21) {
            return (new Response())
                ->withStatus(301)
                ->withHeader("Location", url("/diceGame/result"));
        }

        return (new Response())
            ->withStatus(301)
            ->withHeader("Location", url("/diceGame"));
    }

    /**
     * Print out the results of a game.
     * Save no of rounds and who won to use in scoreboard.
     */
    public function resultGame(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $winner = null;
        $callable = $_SESSION["diceHand"];
        $data = [
            "header" => "Result",
            "message" => "Result of latest round: ",
            "throw" => $callable->getLastRoll(),
            "sum" => $callable->getSum(),
        ];

        if ($callable->getSum() == 21) {
            $data["result"] = "CONGRATULATIONS! You got 21!";
            $winner = "player";
        } elseif ($callable->getSum() > 21) {
            $data["result"] = "You passed 21 and lost, sum: " . $data["sum"];
            $winner = "computer";
        } else {
            $computerScore = $callable->simulateComputer((int) $data["sum"]);
            if($computerScore <= 21) {
                $data["result"] = "Computer wins, got sum = " . $computerScore . ", your sum = " . $data['sum'];
                $winner = "computer";
            } else {
                $data["result"] = "You win, computer got sum = " . $computerScore . ", your sum = " . $data['sum'];
                $winner = "player";
            }
        }
        if ($winner == "player") {
            $_SESSION["playScore"] += 1;
        } else {
            $_SESSION["compScore"] += 1;
        }
        $_SESSION["noRounds"] += 1;
        unset($_SESSION["diceHand"]);
        $body = renderView("layout/resultGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

}
