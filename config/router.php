<?php

/**
 * Load the routes into the router, this file is included from
 * `htdocs/index.php` during the bootstrapping to prepare for the request to
 * be handled.
 */

declare(strict_types=1);

use FastRoute\RouteCollector;
use Emeu17\Dice;

$router = $router ?? new RouteCollector(
    new \FastRoute\RouteParser\Std(),
    new \FastRoute\DataGenerator\MarkBased()
);

$router->addRoute("GET", "/test", function () {
    // A quick and dirty way to test the router or the request.
    return "Testing response";
});

$router->addRoute("GET", "/", "\Mos\Controller\Index");
$router->addRoute("GET", "/debug", "\Mos\Controller\Debug");
$router->addRoute("GET", "/twig", "\Mos\Controller\TwigView");

$router->addGroup("/session", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Mos\Controller\Session", "index"]);
    $router->addRoute("GET", "/destroy", ["\Mos\Controller\Session", "destroy"]);
});

$router->addGroup("/some", function (RouteCollector $router) {
    $router->addRoute("GET", "/where", ["\Mos\Controller\Sample", "where"]);
});

$router->addGroup("/form", function (RouteCollector $router) {
    $router->addRoute("GET", "/view", ["\Mos\Controller\Form", "view"]);
    $router->addRoute("POST", "/process", ["\Mos\Controller\Form", "process"]);
});

$router->addRoute("GET", "/dice", "\Mos\Controller\DicePage");

$router->addGroup("/startGame", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Mos\Controller\Game21", "startGame"]);
    $router->addRoute("GET", "/reset", ["\Mos\Controller\Game21", "resetGame"]);
    $router->addRoute("POST", "/process", ["\Mos\Controller\Game21", "processStart"]);
});

$router->addGroup("/diceGame", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Mos\Controller\Game21", "playGame"]);
    $router->addRoute("POST", "/process2", ["\Mos\Controller\Game21", "processThrow"]);
    $router->addRoute("GET", "/result", ["\Mos\Controller\Game21", "resultGame"]);
});

$router->addGroup("/yatzy", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Mos\Controller\Yatzy", "startGame"]);
    $router->addRoute("POST", "/process", ["\Mos\Controller\Yatzy", "processStart"]);
    $router->addRoute("GET", "/game", ["\Mos\Controller\Yatzy", "playGame"]);
    $router->addRoute("POST", "/process2", ["\Mos\Controller\Yatzy", "processThrow"]);
    $router->addRoute("GET", "/result", ["\Mos\Controller\Yatzy", "result"]);
});
