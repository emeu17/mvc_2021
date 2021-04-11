<?php

declare(strict_types=1);

namespace Mos\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Emeu17\Dice\DiceHand;
use Emeu17\Dice\Dice;

use function Mos\Functions\renderView;

/**
 * Controller for a sample route an controller class.
 */
class DicePage
{
    public function __invoke(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $data = [
            "header" => "Dice game",
            "message" => "My Dice game",
        ];

        $body = renderView("layout/dice.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
