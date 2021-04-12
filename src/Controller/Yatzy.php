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
class Yatzy
{
    /**
     * Shows first page for starting a new game
     * Also retrieves data from session to print out scoreboard
     */
    public function startGame(): ResponseInterface
    {
        if (isset($_SESSION["yatzyHand"])) {
            unset($_SESSION["yatzyHand"]);
        }
        $psr17Factory = new Psr17Factory();
        //
        // if (!isset($_SESSION["noRounds"])) {
        //     $_SESSION["noRounds"] = 0;
        //     $_SESSION["compScore"] = 0;
        //     $_SESSION["playScore"] = 0;
        // }
        $data = [
            "header" => "Yatzy",
            "message" => <<<EOT
            Welcome to the game Yatzy! The game is played with
            5 dices. You should get as many dices of face 1 through 6
            in that order. Each round (on each face) is 3 turns. You
            get to choose how many dices you want to keep between the turns
            and how many you want to roll again. Good Luck!
            EOT,
            "action" => url("/yatzy/process"),
        ];
        $body = renderView("layout/startYatzy.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    /**
     * Initiates diceHand if it doesnt already exists
     * Starts new game with chosen amount of dices
     */
    public function processStart(): ResponseInterface
    {
        $_SESSION["round"] = 1;
        if (!isset($_SESSION["yatzyHand"])) {
            $_SESSION["yatzyHand"] = new DiceHand(5);
        }

        return (new Response())
            ->withStatus(301)
            ->withHeader("Location", url("/yatzy/game"));
    }

    /**
     * Play a started game, possible to roll dices
     * or stop at current dice faces.
     *
     */
    public function playGame(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $callable = $_SESSION["yatzyHand"];
        $data = [
            "header" => "Yatzy: Throw dices",
            "message" => "Choose to throw dice(s) or stop at current sum",
            "action" => url("/yatzy/process2"),
        ];
        $data["throw"] = $callable->getLastRoll();
        $data["sum"] = $callable->getSum();
        $body = renderView("layout/yatzyGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    /**
     * Throw dices 3 times each round.
     *
     */
    public function processThrow(): ResponseInterface
    {
        if ($_SESSION["round"] < 3) {
            $callable = $_SESSION["yatzyHand"];
            $callable->roll();
            $_SESSION["yatzyHand"] = $callable;
            $_SESSION["round"] += 1;
            return (new Response())
                ->withStatus(301)
                ->withHeader("Location", url("/yatzy/game"));
        }
        return (new Response())
            ->withStatus(301)
            ->withHeader("Location", url("/yatzy/result"));
    }

    /**
     * Print out the results of a game.
     * Save no of rounds and who won to use in scoreboard.
     */
    public function result(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();
        $data = [
            "header" => "Result",
            "message" => "Result of round: ",
        ];

        $body = renderView("layout/resultYatzy.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

}
