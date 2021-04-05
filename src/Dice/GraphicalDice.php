<?php

declare(strict_types=1);

namespace Emeu17\Dice;

/**
 * Class GraphicalDice.
 */


/**
 * A graphic dice.
 */
class GraphicalDice extends Dice
{
    /**
     * Constructor to initiate the dice with six number of sides.
     */
    public function __construct()
    {
        parent::__construct(6);
    }

    /**
     * Get a graphic value of the last rolled dice.
     *
     * @return string as graphical representation of last rolled dice.
     */
    public function graphic()
    {
        return "dice-" . $this->getLastRoll();
    }
}
