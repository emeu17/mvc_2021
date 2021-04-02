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
 * Class Game.
 */
class Game
{

    public function playGame(): void
    {
        $data = [
            "header" => "Dice game",
            "message" => "Hey",
        ];

        $die = new Dice();
        $die->roll();

        $diceHand = new DiceHand();
        $diceHand->roll();

        $data["dieLastRoll"] = $die->getLastRoll();

        $data["diceHandRoll"] = $diceHand->getLastRoll();
        $diceHand->roll();
        $data["diceHandRoll1"] = $diceHand->getLastRoll();

        $body = renderView("layout/diceGame.php", $data);
        sendResponse($body);
    }
}
