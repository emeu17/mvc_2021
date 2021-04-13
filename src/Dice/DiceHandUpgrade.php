<?php

declare(strict_types=1);

namespace Emeu17\Dice;

/**
 * Class DiceHand.
 */
class DiceHandUpgrade extends DiceHand
{
    /**
     * Constructor to initiate the chosen no of dices.
     */
    public function __construct(int $noDices)
    {
        $this->noDices = $noDices;
        for ($i = 0; $i < $noDices; $i++) {
            $this->dices[$i] = new GraphicalDice();
        }
    }

    public function rollChosenDices(array $chosenDices): void
    {
        for ($i = 0; $i < $this->noDices; $i++) {
            if (!in_array($i, $chosenDices)) {
                $this->dices[$i]->roll();
            }
        }
    }

    public function getGraphic()
    {
        $class = [];
        for ($i = 0; $i < $this->noDices; $i++) {
            $class[$i] = $this->dices[$i]->graphic();
        }
        return $class;
    }


}
