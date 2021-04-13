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
use Emeu17\Dice\DiceHandUpgrade;


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
        $_SESSION["yatzyRound"] = 1;
        $_SESSION["yatzySet"] = 1;
        $_SESSION["yatzyResult"] = [];
        $_SESSION["yatzyNewRound"] = true;
        if (!isset($_SESSION["yatzyHand"])) {
            $_SESSION["yatzyHand"] = new DiceHandUpgrade(5);
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
            "message" => "Get as many as possible of face " . $_SESSION["yatzySet"],
            "action" => url("/yatzy/process2"),
            "result" => $this->printResult($_SESSION["yatzySet"]),
        ];
        $data["throw"] = $callable->getLastRollArray();
        $data["graphic"] =  $callable->getGraphic();
        $data["newRound"] = $_SESSION["yatzyNewRound"];
        $body = renderView("layout/yatzyGame.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    /**
     * Throw dices 3 times each round.
     * The game is played getting as many dices
     * of face 1 through 6 (in that order).
     */
    public function processThrow(): ResponseInterface
    {
        $diceThrow = $_POST["diceThrow"] ?? [];

        $callable = $_SESSION["yatzyHand"];
        //if no dices chosen, throw all
        //otherwise throw only chosen dices anew
        // if(empty($diceThrow)) {
        //     $callable->roll();
        // } else {
        //     $callable->rollChosenDices($diceThrow);
        // }

        empty($diceThrow) ? $callable->roll() : $callable->rollChosenDices($diceThrow);

        if ($_SESSION["yatzyRound"] < 3) {

            $_SESSION["yatzyHand"] = $callable;
            $_SESSION["yatzyRound"] += 1;
            $_SESSION["yatzyNewRound"] = false;
            return (new Response())
                ->withStatus(301)
                ->withHeader("Location", url("/yatzy/game"));
        }
        $_SESSION["yatzyResult"][$_SESSION["yatzySet"]] = $callable->getLastRollArray();
        $_SESSION["yatzySet"] += 1;
        $_SESSION["yatzyRound"] = 1;
        $_SESSION["yatzyNewRound"] = true;

        if($_SESSION["yatzySet"] == 7) {
            return (new Response())
                ->withStatus(301)
                ->withHeader("Location", url("/yatzy/result"));
        }

        return (new Response())
            ->withStatus(301)
            ->withHeader("Location", url("/yatzy/game"));
    }

    /**
     * Print out the results of so far played rounds
     */
    public function printResult($currentSet)
    {
        $str = "";
        if($currentSet > 1) {
            for ($i = 1; $i < $currentSet; $i++) {
                $str .= nl2br($i . ": " . implode(", ", $_SESSION["yatzyResult"][$i]) . "\n");
            }
        }
        return $str;
    }

    /**
     * Print out the results of so far played rounds
     * print out with stars for no of correct dices, eg:
     * 1: **
     * 2: ***
     */
    public function printResultStars($currentSet)
    {
        $str = "";
        if($currentSet > 1) {
            for ($i = 1; $i < $currentSet; $i++) {
                $noDices = count($_SESSION["yatzyResult"][$i]);
                $str .= $i . ": ";
                for ($j = 0; $j < $noDices; $j++) {
                    if ($i == $_SESSION["yatzyResult"][$i][$j]) {
                        $str .= "*";
                    }
                }
                $str .= nl2br("\n");
            }
        }
        return $str;
    }

    /**
     * Calculate sum of dices by adding
     * together correct dices for each round.
     */
    public function getSum($diceArray)
    {
        $sum = 0;
        for ($i = 1; $i <= 6; $i++) {
            $noDices = count($diceArray[$i]);
            for ($j = 0; $j < $noDices; $j++) {
                if ($diceArray[$i][$j] == $i) {
                    $sum += $i;
                }
            }
        }
        return $sum;
    }

    /**
     * Check if correct dices (sum) are enough to get bonus.
     */
    public function getBonus($sum) {
        if($sum > 63) {
            return 50;
        }
        return 0;
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
            "message" => "Result of game: ",
            "result" => $this->printResult($_SESSION["yatzySet"]),
            "result2" => $this->printResultStars($_SESSION["yatzySet"]),
            "sum" => $this->getSum($_SESSION["yatzyResult"]),

        ];
        $data["bonus"] = $this->getBonus($data["sum"]);
        $body = renderView("layout/resultYatzy.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

}
