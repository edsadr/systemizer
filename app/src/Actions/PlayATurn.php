<?php
namespace Fmizzell\TicTacToe\Actions;

use Exception;

trait PlayATurn
{
    public function playATurn($x, $y)
    {
        $cp = $this->getCurrentPlayer();
        if (empty($cp)) {
            $this->setCurrentPlayer($this->getPlayer1());
        }

        $this->setX($x);
        $this->setY($y);

        // Check that the quadrant is empty.
        $q = $this->getGrid()->getQuadrant($this->getX(), $this->getY());

        if (empty($q)) {
            // Set the current player's token into the grid.
          $this->getGrid()->setQuadrant($this->getX(), $this->getY(), $this->getCurrentPlayer()->getToken());
        } else {
            throw new Exception("Tokens can not be placed in full quadrants");
        }

        // Set the current player bar to be ready for the next turn.
        $this->switchCurrentPlayer();
    }

    public function switchCurrentPlayer()
    {
        if ($this->getCurrentPlayer() == $this->getPlayer1()) {
            $this->setCurrentPlayer($this->getPlayer2());
        } else {
            $this->setCurrentPlayer($this->getPlayer1());
        }
    }
}
