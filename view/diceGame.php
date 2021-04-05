<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

use Emeu17\Dice\Dice;
use Emeu17\Dice\DiceHand;


$header = $header ?? null;
$message = $message ?? null;
$throw = $throw ?? null;
?>

<h1><?= $header ?></h1>

<p><?= $message ?></p>

<p>
    <?php if ($sum != 0) { ?>
        Last dice throw: <?= $throw ?>
    <?php } ?>
</p>

<p>Sum: <?= $sum ?></p>

<form method="post" action="process2">
    <fieldset>
        <label>Choose to throw dices again or stop at current sum </label>
        <p>
            <input type="submit" name="throw" value="Throw dice(s)">
            <input type="submit" name="stop" value="Stop round">
        </p>
    </fieldset>
</form>
