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
            and how many you want to roll again. Good Luck!`
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
            "action" => url("/diceGame/process2"),
        ];
        $data["throw"] = $callable->getLastRoll();
        $data["sum"] = $callable->getSum();
        $body = renderView("layout/yatzyGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
