<?php

declare(strict_types=1);

namespace Emeu17\Dice;

/**
 * Class DiceHand.
 */
class DiceHandUpgrade extends DiceHand
{
    /**
     * Add dice of class $dice to DiceHand 
     *
     */
    public function addDice(DiceInterface $dice) {
            $this->noDices++;
            $this->dices[] = $dice;
    }


    /**
     * Constructor to initiate noDices to zero.
     */
    public function __construct()
    {
        $this->noDices = 0;
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
            $class[$i] = $this->dices[$i]->asString();
        }
        return $class;
    }


}
