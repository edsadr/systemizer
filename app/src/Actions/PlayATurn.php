<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author jay
 */
namespace Fmizzell\TicTacToe\Actions {

    trait PlayATurn {
        public function playATurn($x, $y) {
            $cp = $this->getCurrentPlayer();
            if (empty($cp)) {
              $this->setCurrentPlayer($this->getPlayer1());
            }

            $this->setX($x);
            $this->setY($y);

            // Set the current player's token into the grid.
            $this->getGrid()->setQuadrant($this->getX(), $this->getY(), $this->getCurrentPlayer()->getToken());

            // Set the current player bar to be ready for the next turn.
            $this->switchCurrentPlayer();
        }

        private function switchCurrentPlayer() {
            if ($this->getCurrentPlayer() == $this->getPlayer1()) {
              $this->setCurrentPlayer($this->getPlayer2());
            }
            else {
              $this->setCurrentPlayer($this->getPlayer1());
            }
        }
    }
}
