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

<p><b><?= $message ?></b></p>

<form method="post" action="<?= $action ?>">
    <?php
    if (!$newRound) {
        echo "Which dices do you want to keep?";
        $i = 0; ?>
        <p class="dice-utf8">
        <?php
        foreach ($graphic as $item) : ?>
            <input type="checkbox" name="diceThrow[]" value="<?= $i ?>" /><i class="<?= $item ?>"></i>
        <?php
            $i++;
        endforeach;
    } ?>
    </p>

<?php if($result) { ?>
<p>Result of rounds: </p>
    <?= $result ?>
<?php } ?>
<br>

    <fieldset>
        <label>Throw dices: </label>
        <p>
            <input type="submit" name="throw" value="Throw dices">
        </p>
    </fieldset>
</form>
