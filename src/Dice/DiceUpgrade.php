<?php

declare(strict_types=1);

namespace Emeu17\Dice;

// use function Mos\Functions\{
//     destroySession,
//     redirectTo,
//     renderView,
//     renderTwigView,
//     sendResponse,
//     url
// };

/**
 * Class Dice.
 */
class DiceUpgrade extends Dice
{
    use HistogramTrait;

    /**
     * Roll the dice, remember its value in the serie and return
     * its value.
     *
     * @return string the value of the rolled dice.
     */
    public function getHistogram()
    {
        $this->serie[] = $this->getLastRoll();
        return $this->printHistogram();
    }
}
