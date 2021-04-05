<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

// namespace Emeu17\Dice;
//
use Emeu17\Dice\Dice;
use Emeu17\Dice\DiceHand;

$diceHand = new DiceHand(1);
echo "testing";
$comp = $diceHand->simulateComputer(20);
echo "computer got sum " . $comp;
