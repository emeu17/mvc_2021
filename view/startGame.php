<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

use function Mos\Functions\url;
$url = url("/startGame/reset");

$header = $header ?? null;
$message = $message ?? null;
?>

<h1><?= $header ?></h1>

<p><?= $message ?></p>

<div class="scoreBoard">
    <h2>Scoreboard</h2>
    <p>Number of rounds played: <?= $rounds ?> </p>
    <p>Player score: <?= $playerScore ?> </p>
    <p>Computer score: <?= $computerScore ?> </p>
    <p><a href="<?= $url ?>">Reset scoreboard</a></p>
</div>


<form method="post" action="process">
    <fieldset>
        <label><h2>Start new game</h2></label>
        <p>
            <label for="title">Choose number of dices (1 or 2):</label>
            <input id="dices" type="number" name="dices" value=1 max=2 min=1>
        </p>
        <p>
            <input type="submit" name="create" value="Start game">
            <input type="reset" value="Reset">
        </p>
    </fieldset>
</form>
