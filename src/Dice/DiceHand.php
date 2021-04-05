<?php

declare(strict_types=1);

namespace Emeu17\Dice;

/**
 * Class DiceHand.
 */
class DiceHand
{
    private array $dices;
    private int $sum;
    private int $no_dices;

    public function __construct(int $no_dices=2)
    {
        $this->no_dices = $no_dices;
        for ($i = 0; $i < $no_dices; $i++) {
            $this->dices[$i] = new Dice();
        }
        $this->sum = 0;
    }

    /**
     * Roll dice(s)
     *
     */
    public function roll(): void
    {
        for ($i = 0; $i < $this->no_dices; $i++) {
            $this->sum += $this->dices[$i]->roll();
        }
    }

    /**
     * Get sum of all rolled dice.
     *
     * @return int as sum of all rolled dices.
     */
    public function getSum(): int
    {
        return $this->sum;
    }

    /**
     * Set sum of all rolled dices.
     *
     */
    public function setSum(int $sum): void
    {
        $this->sum = $sum;
    }

    /**
     * Get a string of latest rolled dice(s)
     *
     * @return string with all dice values separated by ",".
     */
    public function getLastRoll(): string
    {
        $res = [];
        for ($i = 0; $i < $this->no_dices; $i++) {
            $res[] = $this->dices[$i]->getLastRoll();
        }
        return implode(", ", $res);
    }

    /**
     * Simulate the computers dice rolls. Continues rolling
     * until it has a higher number than the player score
     * @return int as computers score.
     */
    public function simulateComputer(int $playerScore): int
    {
        $this->sum = 0;
        $computerScore = 0;
        while ($computerScore < $playerScore) {
            // echo "\nPlayer score = " . $playerScore . ", computer = " . $computerScore . ", sum = " . $this->getSum() . "\n";
            $this->roll();
            // echo "dices: " . $this->getLastRoll();
            $computerScore = $this->getSum();
        }
        return $computerScore;
    }
}
