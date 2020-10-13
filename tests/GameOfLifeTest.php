<?php
use PHPUnit\Framework\TestCase;
use GoL\GameOfLife;

final class GameOfLifeTest extends TestCase
{   
    /**
     * @dataProvider sizesToCreate
     */
    public function testCanCreateNSizeBoard(int $n):void
    {
        $board = array_fill(0, $n, array_fill(0, $n, false));
        $this->assertInstanceOf(
            GameOfLife::class,
            new GameOfLife( $board )
        );

    }
    public function sizesToCreate():array
    {
        return [[0],[1],[2],[100]];
    }
}
