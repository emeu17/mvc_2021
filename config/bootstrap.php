<?php

/**
 * Setup the basis for the environment.
 */

declare(strict_types=1);

// require_once __DIR__ . "/.." . '/src/Dice/DiceHand.php';
// require_once __DIR__ . "/.." . '/src/Dice/Dice.php';

// Setup error reporting
error_reporting(-1);                // Report all type of errors
ini_set("display_errors", "1");     // Display all errors

// Start the session
if (php_sapi_name() !== "cli") {
    session_name(preg_replace("/[^a-z\d]/i", "", __DIR__));
    session_start();
}
