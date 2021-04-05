<?php

/**
 * Rolling dice(s) and shows the result. Possible to roll again or stop.
 */

declare(strict_types=1);

use Emeu17\Dice\Dice;
use Emeu17\Dice\DiceHand;


$header = $header ?? null;
$message = $message ?? null;

$die = new Dice();
$die->roll();

$diceHand = new DiceHand();
$diceHand->roll();

?><h1><?= $header ?></h1>

<p><?= $message ?></p>

<p>Dice:</p>

<p><?= $die->getLastRoll() ?></p>

<p>DiceHand:</p>
<p><?= $diceHand->getLastRoll() ?></p>
