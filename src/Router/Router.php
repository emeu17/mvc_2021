<?php

declare(strict_types=1);

namespace Mos\Router;

use Emeu17\Dice\DiceHand;
use Emeu17\Dice\Dice;

use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url
};

/**
 * Class Router.
 */
class Router
{
    public static function dispatch(string $method, string $path): void
    {
        if ($method === "GET" && $path === "/") {
            $data = [
                "header" => "Index page",
                "message" => "Hello, this is the index page, rendered as a layout.",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/session") {
            $body = renderView("layout/session.php");
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/session/destroy") {
            destroySession();
            redirectTo(url("/session"));
            return;
        } else if ($method === "GET" && $path === "/debug") {
            $body = renderView("layout/debug.php");
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/twig") {
            $data = [
                "header" => "Twig page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderTwigView("index.html", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/some/where") {
            $data = [
                "header" => "Rainbow page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/dice") {
            $data = [
                "header" => "Dice game",
                "message" => "My Dice game",
            ];
            $body = renderView("layout/dice.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "POST" && $path === "/process") {
            if (!isset($_SESSION["diceHand"])) {
                $_SESSION["diceHand"] = new DiceHand((int) $_POST["dices"]);
            }
            redirectTo(url("/diceGame"));
            return;
        } else if ($method === "POST" && $path === "/process2") {
            if ($_POST["stop"]) {
                redirectTo(url("/resultGame"));
                return;
            }
            $callable = $_SESSION["diceHand"];
            $callable->roll();
            $_SESSION["diceHand"] = $callable;
            if ($callable->getSum() >= 21) {
                redirectTo(url("/resultGame"));
                return;
            }
            redirectTo(url("/diceGame"));
            return;
        } else if ($method === "GET" && $path === "/diceGame") {
            $callable = new \Emeu17\Dice\Game();
            $callable->playGame();
            return;
        } else if ($method === "GET" && $path === "/resultGame") {
            $callable = new \Emeu17\Dice\Game();
            $callable->resultGame();
            return;
        } else if ($method === "GET" && $path === "/startGame") {
            $callable = new \Emeu17\Dice\Game();
            $callable->startGame();
            return;
        } else if ($method === "GET" && $path === "/startGame/reset") {
            destroySession();
            $_SESSION["noRounds"] = 0;
            $_SESSION["compScore"] = 0;
            $_SESSION["playScore"] = 0;
            redirectTo(url("/startGame"));
            return;
        }


        $data = [
            "header" => "404",
            "message" => "The page you are requesting is not here. You may also checkout the HTTP response code, it should be 404.",
        ];
        $body = renderView("layout/page.php", $data);
        sendResponse($body, 404);
    }
}
