<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

// namespace Emeu17\Dice;
//
use Emeu17\Dice\Dice;
use Emeu17\Dice\DiceHandUpgrade;
//
// $diceHand = new DiceHand(5);
echo "testing\n";
// $diceHand->roll();
// $comp = $diceHand->getLastRollArray();
// foreach ($comp as $item) {
//     echo $item . "\n";
// }
//
// $test = $_POST["none"] ?? [];
// if(empty($test)) {
//     echo "Empty array";
// }

// $diceRoll = new DiceHand(5);
// $diceRoll->roll();
// $comp1 = $diceRoll->getLastRollArray();
// echo "Last roll: \n";
// for ($i = 0; $i < 5; $i++) {
//     echo $comp1[$i] . "\n";
// }
// $myArray = [2, 4];
// $diceRoll->rollChosenDices($myArray);
// $comp2 = $diceRoll->getLastRollArray();
// echo "Last roll: \n";
// for ($i = 0; $i < 5; $i++) {
//     echo $comp2[$i] . "\n";
// }

$diceRoll = new DiceHandUpgrade();
$diceRoll->roll();
// $diceRoll->printHistogram();
$diceRoll->getGraphic();

// $newArray = [];
// $face = 1;
// for ($i = 1; $i < 6; $i++) {
//     $newArray[$face][$i] = rand(1, 6);
// }
// print_r($newArray);
// $face = 2;
// for ($i = 1; $i < 6; $i++) {
//     $newArray[$face][$i] = rand(1, 6);
// }
// print_r($newArray);
// echo "array: " . $comp;
