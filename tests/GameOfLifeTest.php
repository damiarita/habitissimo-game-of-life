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
        $gameOfLife = new GameOfLife( $board );
        $this->assertInstanceOf(
            GameOfLife::class,
            $gameOfLife
        );

        $this->assertEquals($n_rows, $gameOfLife->getNumRows());
        $this->assertEquals($n_columns, $gameOfLife->getNumColumns());

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
