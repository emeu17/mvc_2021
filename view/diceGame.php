<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
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
<p><?= $dieLastRoll ?></p>

<p>DiceHand:</p>
<p><?= $diceHandRoll ?></p>
<p><?= $diceHandRoll1 ?></p>
