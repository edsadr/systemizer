<?php

namespace Fmizzell\TicTacToe\Actions;

trait Draw
{
    public function draw()
    {
        if (!$this->gotWinner() && $this->gridIsFull()) {
            return true;
        }

        return false;
    }

    private function gridIsFull()
    {
        $full = true;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $token = $this->getGrid()->getQuadrant($i, $j);
                if (!is_object($token)) {
                    $full = false;
                    break;
                }
            }
        }

        return $full;
    }
}
