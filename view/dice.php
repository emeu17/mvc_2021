<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

use Emeu17\Dice\Dice;
use Emeu17\Dice\DiceHand;
use Emeu17\Dice\GraphicalDice;

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

<?php
$diceHand2 = new DiceHand();
$test = $diceHand2->simulateComputer(20);
?>

<p>Test: <?= $test ?> </p>

<h1>Rolling six graphic dices</h1>

<?php
$dice = new GraphicalDice();
$rolls = 6;
$res = [];
$class = [];
for ($i = 0; $i < $rolls; $i++) {
    $res[] = $dice->roll();
    $class[] = $dice->asString();
} ?>

<p><?= implode(", ", $res) ?></p>
<p>Sum is: <?= array_sum($res) ?>.</p>
<p>Average is: <?= round(array_sum($res) / 6, 1) ?>.</p>


<p class="dice-utf8">
<?php foreach($class as $value) : ?>
    <i class="<?= $value ?>"></i>
<?php endforeach; ?>
</p>
