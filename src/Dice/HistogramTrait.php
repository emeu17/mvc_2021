<?php

namespace Emeu17\Dice;

/**
 * A trait implementing histogram for integers.
 */
trait HistogramTrait
{
    /**
     * @var array $serie  The numbers stored in sequence.
     */
    private $serie = [];



    /**
     * Get the serie.
     *
     * @return array with the serie.
     */
    public function getHistogramSerie()
    {
        return $this->serie;
    }



    /**
     * Print out the histogram, default is to print out only the numbers
     * in the serie, but when $min and $max is set then print also empty
     * values in the serie, within the range $min, $max.
     *
     * @param int $min The lowest possible integer number.
     * @param int $max The highest possible integer number.
     *
     * @return string representing the histogram.
     */
    public function printHistogram(int $min = null, int $max = null)
    {
        $occurences = array_count_values($this->serie);
        $str = "";
        if ($min && $max) {
            for ($i = $min; $i <= $max; $i++) {
                $str .= $i . ": ";
                if (array_key_exists($i, $occurences)) {
                    for ($j = 0; $j < $occurences[$i]; $j++) {
                        $str .= "*";
                    }
                }
                $str .= "\n";
            }
            return $str;
        }


        for ($i = 1; $i <= 6; $i++) {
            if (array_key_exists($i, $occurences)) {
                $str .= $i . ": ";
                for ($j = 0; $j < $occurences[$i]; $j++) {
                    $str .= "*";
                }
                $str .= "\n";
            }
        }
        return $str;
    }
}
