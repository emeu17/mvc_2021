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


<form method="post" action="<?= $action ?>">
    <fieldset>
        <label><h2>Start new game</h2></label>
        <p>
            <input type="submit" name="create" value="Start game">
        </p>
    </fieldset>
</form>
