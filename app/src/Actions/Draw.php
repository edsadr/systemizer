<?php

namespace Fmizzell\TicTacToe\Actions;

trait Draw {
  public function draw() {
    if(!$this->gotWinner() && $this->gridIsFull()) {
      return TRUE;
    }
    return FALSE;
  }

  private function gridIsFull() {
    $full = TRUE;
    for($i = 0; $i < 3; $i++) {
      for($j = 0; $j < 3; $j++) {
        $token = $this->getGrid()->getQuadrant($i, $j);
        if(!is_object($token)) {
          $full = FALSE;
          break;
        }
      }
    }
    return $full;
  }
}
