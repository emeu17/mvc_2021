<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

use function Mos\Functions\url;

use Emeu17\Dice\Dice;
use Emeu17\Dice\DiceHand;


$header = $header ?? null;
$message = $message ?? null;
$throw = $throw ?? null;
?>

<h1><?= $header ?></h1>

<p><?= $message ?></p>

<p> <?= $result ?> </p>

<p> <a href="<?= url("/startGame") ?>">Play again?</a> </p>
