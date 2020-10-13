<?php
require './vendor/autoload.php';

echo PHP_EOL . PHP_EOL . 'Blinker' . PHP_EOL;

$game = new GoL\GameOfLife([
    [false, false, false, false, false],
    [false, false, false, false, false],
    [false, true, true, true, false],
    [false, false, false, false, false],
    [false, false, false, false, false]
]);

printBoard(0, $game->getBoard());
$game->nextGen();
printBoard(1, $game->getBoard());
$game->nextGen();
printBoard(2, $game->getBoard());

echo PHP_EOL . PHP_EOL . 'Glider' . PHP_EOL;

$board = array_fill( 0, 10, array_fill(0,10,false) );
$board[0][1]=true;
$board[1][2]=true;
$board[2][0]=true;
$board[2][1]=true;
$board[2][2]=true;

$game = new GoL\GameOfLife($board);

for($i=0; $i<28; $i++){
    printBoard($i, $game->getBoard());
    $game->nextGen();
}
printBoard($i, $game->getBoard());

function printBoard(int $generation, array $board):void
{
    echo PHP_EOL;
    echo 'NumGeneration: ' . $generation . PHP_EOL;
    foreach($board as $row):
        foreach($row as $cell):
            echo $cell?'*':'O';
        endforeach;
        echo PHP_EOL;
    endforeach;
}