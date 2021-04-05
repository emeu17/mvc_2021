<?php

declare(strict_types=1);

namespace Emeu17\Dice;

use function Mos\Functions\{
    redirectTo,
    renderView,
    sendResponse,
    url
};

use Emeu17\Dice\Dice;
use Emeu17\Dice\DiceHand;


/**
 * Class Game. Used to start up, play and
 * print out the result of a dice game
 */
class Game
{
    /**
     * Shows first page for starting a new game
     * Also retrieves data from session to print out scoreboard
     */
    public function startGame(): void
    {
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
        ];
        $body = renderView("layout/startGame.php", $data);
        sendResponse($body);
    }

    /**
     * Play a started game, possible to roll dice(s)
     * or stop at current sum of dice(s)
     *
     */
    public function playGame(): void
    {
        $callable = $_SESSION["diceHand"];
        $data = [
            "header" => "Game 21: Throw dices",
            "message" => "Choose to throw dice(s) or stop at current sum",
        ];
        $data["throw"] = $callable->getLastRoll();
        $data["sum"] = $callable->getSum();
        $body = renderView("layout/diceGame.php", $data);
        sendResponse($body);
    }

    /**
     * Print out the results of a game.
     * Save no of rounds and who won to use in scoreboard.
     */
    public function resultGame(): void
    {
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
        sendResponse($body);
    }

}
