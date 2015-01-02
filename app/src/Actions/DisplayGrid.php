<?php
namespace Fmizzell\TicTacToe\Actions;

trait DisplayGrid
{
    public function display()
    {
        // Print the board.
    for ($i = 0; $i < 3; $i++) {
        print "| ";
        for ($j = 0; $j < 3; $j++) {
            $token = $this->getQuadrant($i, $j);
            print !empty($token) ? $token->getValue() : " ";
            print " | ";
        }
        print "\n";
    }
    }
}
