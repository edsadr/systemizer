<?php
namespace Fmizzell\TicTacToe\Actions;

trait Winner
{
    public function gotWinner()
    {
        $tokens = array($this->getPlayer1()->getToken(), $this->getPlayer2()->getToken());

        $wins = array();
        $wins[] = [[0,0],[0,1],[0,2]];
        $wins[] = [[1,0],[1,1],[1,2]];
        $wins[] = [[2,0],[2,1],[2,2]];
        $wins[] = [[0,0],[1,0],[2,0]];
        $wins[] = [[0,1],[1,1],[2,1]];
        $wins[] = [[0,2],[1,2],[2,2]];
        $wins[] = [[0,0],[1,1],[2,2]];
        $wins[] = [[0,2],[1,1],[2,0]];

        foreach ($tokens as $token) {
            foreach ($wins as $win) {
                $yep = true;
                foreach ($win as $pos) {
                    if ($this->getGrid()->getQuadrant($pos[0], $pos[1]) !== $token) {
                        $yep = false;
                    }
                }
                if ($yep) {
                    return true;
                }
            }
        }

        return false;
    }
}
