<?php
use PHPUnit\Framework\TestCase;
use GoL\GameOfLife;

final class GameOfLifeTest extends TestCase
{   
    /**
     * @dataProvider sizesToCreate
     */
    public function testCanCreateNSizeBoard(int $n_rows, int $n_columns):void
    {
        $board = array_fill(0, $n_rows, array_fill(0, $n_columns, false));
        $this->assertInstanceOf(
            GameOfLife::class,
            new GameOfLife( $board )
        );

    }
    public function sizesToCreate():array
    {
        return [
            [0,0],
            [1,1],
            [1,2],
            [2,1],
            [2,2],
            [100,90]
        ];
    }
}
