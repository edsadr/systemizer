<?php
include "./vendor/autoload.php";

$token1 = new \Fmizzell\TicTacToe\Token("X");
$player1 = new \Fmizzell\TicTacToe\Player("Jay", $token1);

$token2 = new \Fmizzell\TicTacToe\Token("O");
$player2 = new \Fmizzell\TicTacToe\Player("Kay", $token2);

$grid = new \Fmizzell\TicTacToe\Grid();

$game = new \Fmizzell\TicTacToe\TicTacToe($player1, $player2, $grid);

while (!$game->draw() && !$game->gotWinner()) {
    $cp = $game->getCurrentPlayer();
    if (!isset($cp)) {
        $cp = $game->getPlayer1();
    }
    print "{$cp->getName()} Where are you placing your token? \n";
    $x = readline("X: ");
    $y = readline("Y: ");
    try {
        $game->playATurn((int) $x, (int) $y);
    } catch (Exception $e) {
        print "Try again\n";
        print $e->getMessage()."\n";
    }

    $game->getGrid()->display();
}

if ($game->draw()) {
    print "The game was a draw. \n";
} else {
    $game->switchCurrentPlayer();
    print "Congrats {$game->getCurrentPlayer()->getName()}!!! You won. \n";
}
