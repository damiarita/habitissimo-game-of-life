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
            [1,0],
            [1,1],
            [1,2],
            [2,1],
            [2,2],
            [100,90]
        ];
    }

    public function testExceptionThrownWhenRowsSizesAreNotEqual():void
    {
        $board = [ array_fill(0, 2, false), array_fill(0,4,false)];
        $this->expectException(InvalidArgumentException::class);
        $game = new GameOfLife($board);
    }

    public function testExceptionThrownWhenBoardContainsNotboolean():void
    {
        $board = [ array_fill(0, 2, 'a'), array_fill(0,2,false)];
        $this->expectException(InvalidArgumentException::class);
        $game = new GameOfLife($board);
    }

    public function testExceptionThrownWhenBoardContainsNotarray():void
    {
        $board = ['a', array_fill(0,4,false)];
        $this->expectException(InvalidArgumentException::class);
        $game = new GameOfLife($board);
    }

    public function testExceptionThrownWhenBoardArrayIsNotSequential():void
    {
        $board = array( 'a'=>array_fill(0, 2, false), 'b'=>array_fill(0,2,false) );
        $this->expectException(InvalidArgumentException::class);
        $game = new GameOfLife($board);
    }

    public function testExceptionThrownWhenColumnArrayIsNotSequential():void
    {
        $board = array( array('a'=>false, 'b'=>false), array_fill(0,2,false) );
        $this->expectException(InvalidArgumentException::class);
        $game = new GameOfLife($board);
    }

    public function testGameOfLifeCanDetectSquentialArrays():void
    {
        $class = new ReflectionClass(GameOfLife::class);
        $method = $class->getMethod('arrayIsSequential');
        $method->setAccessible(true);
        
        //invokeArgs expects an array of arguments. We send and array of arrays because the first argument of the method is an array
        $this->assertTrue( $method->invokeArgs(null, [['a', 'b', 'c']]) );
        $this->assertTrue( $method->invokeArgs(null, [[]]) );
        $this->assertFalse( $method->invokeArgs(null, [array('key1'=>'a', 'key2'=>'b', 'key3'=>'c')]) );
    }

    public function testBoundsAreCheckedCorrectly():void
    {
        //We create a 3x3 grid and check that cells on boundaries and in the center return the correct number of neighbours
        $game = new GameOfLife( array_fill(0,3, array_fill(0,3, false)) );
        
        //Position 0,0 is top left corner, only 3 neighbours should be found
        $this->assertCount(3, $game->getNeighboursOfCell(0,0));        

        //Position 0,1 is top boundary, only 5 neighbours should be found
        $this->assertCount(5, $game->getNeighboursOfCell(0,1));

        //Position 0,2 is top right corner, only 3 neighbours should be found
        $this->assertCount(3, $game->getNeighboursOfCell(0,2));         

        //Position 1,0 is left boundary, only 5 neighbours should be found
        $this->assertCount(5, $game->getNeighboursOfCell(1,0));          

        //Position 1,1 is center cell, 8 neighbours should be found
        $this->assertCount(8, $game->getNeighboursOfCell(1,1));     

        //Position 1,2 is right boundary, only 5 neighbours should be found
        $this->assertCount(5, $game->getNeighboursOfCell(1,2));

        //Position 2,0 is bottom left corner, only 3 neighbours should be found
        $this->assertCount(3, $game->getNeighboursOfCell(2,0));       

        //Position 2,1 is bottom boundary, only 5 neighbours should be found
        $this->assertCount(5, $game->getNeighboursOfCell(2,1)); 

        //Position 2,2 is bottom right corner, only 3 neighbours should be found
        $this->assertCount(3, $game->getNeighboursOfCell(2,2)); 


        //We create a 1x1 grid
        $game = new GameOfLife([[false]]);

        //Position 0,0 is surrounded by the boundary, 0 neighbours should be found.
        $this->assertCount(0, $game->getNeighboursOfCell(0,0));

    }

    public function testCellStatesAreReadCorrectly():void
    {
        $game = new GameOfLife( [[false, false],[true, false]] );
        $class = new ReflectionClass(GameOfLife::class);
        $method = $class->getMethod('isCellAlive');
        $method->setAccessible(true);

        $this->assertFalse( $method->invokeArgs($game, [0,0]) );
        $this->assertFalse( $method->invokeArgs($game, [0,1]) );
        $this->assertTrue( $method->invokeArgs($game, [1,0]) );
        $this->assertFalse( $method->invokeArgs($game, [1,1]) );
    }

    public function testAliveNeighboutsAreCountedCorrectly():void
    {
        //We create a 1x1
        $game = new GameOfLife([[false]]);

        //Position 0,0 is surrounded by the boundary, 0 neighbours should be found.
        $this->assertEquals(0, $game->getNumberOfAliveNeighbours(0,0));


        //We create a 3x3 grid with all cells in the central row alive and all other cells dead.
        $game = new GameOfLife( [ [false, false, false] , [true, true, true] , [false, false, false] ] );
        
        //Position 0,0 is top left corner
        $this->assertEquals(2, $game->getNumberOfAliveNeighbours(0,0));        

        //Position 0,1 is top boundary
        $this->assertEquals(3, $game->getNumberOfAliveNeighbours(0,1));

        //Position 0,2 is top right corner
        $this->assertEquals(2, $game->getNumberOfAliveNeighbours(0,2));         

        //Position 1,0 is left boundary
        $this->assertEquals(1, $game->getNumberOfAliveNeighbours(1,0));          

        //Position 1,1 is center cell
        $this->assertEquals(2, $game->getNumberOfAliveNeighbours(1,1));     

        //Position 1,2 is right boundary
        $this->assertEquals(1, $game->getNumberOfAliveNeighbours(1,2));

        //Position 2,0 is bottom left corner
        $this->assertEquals(2, $game->getNumberOfAliveNeighbours(2,0));       

        //Position 2,1 is bottom boundary
        $this->assertEquals(3, $game->getNumberOfAliveNeighbours(2,1)); 

        //Position 2,2 is bottom right corner
        $this->assertEquals(2, $game->getNumberOfAliveNeighbours(2,2)); 
    }

    public function testCellLiveCorrectlyCalculated():void
    {
        /*** CENTRAL CELL ALIVE ***/
        $board = array_fill( 0, 3, array_fill(0,3,false) );
        $board[1][1]=true;
        
        //3x3 grids with central cell alive and rest dead
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell alive and 1 neighbour alive
        $board[0][0]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell alive and 2 neighbours alive
        $board[0][1]=true;
        $game = new GameOfLife($board);
        $this->assertTrue( $game->willCellLive(1, 1) );

        //3x3 grids with central cell alive and 3 neighbours alive
        $board[0][2]=true;
        $game = new GameOfLife($board);
        $this->assertTrue( $game->willCellLive(1, 1) );

        //3x3 grids with central cell alive and 4 neighbours alive
        $board[1][0]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell alive and 5 neighbours alive
        $board[1][2]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell alive and 6 neighbours alive
        $board[2][0]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell alive and 7 neighbours alive
        $board[2][1]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell alive and 8 neighbours alive
        $board[2][2]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        
        /*** CENTRAL CELL DEAD ***/
        $board = array_fill( 0, 3, array_fill(0,3,false) );
        
        //3x3 grids with central cell dead and rest dead
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell dead and 1 neighbour alive
        $board[0][0]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell dead and 2 neighbours alive
        $board[0][1]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell dead and 3 neighbours alive
        $board[0][2]=true;
        $game = new GameOfLife($board);
        $this->assertTrue( $game->willCellLive(1, 1) );

        //3x3 grids with central cell dead and 4 neighbours alive
        $board[1][0]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell dead and 5 neighbours alive
        $board[1][2]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell dead and 6 neighbours alive
        $board[2][0]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell dead and 7 neighbours alive
        $board[2][1]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

        //3x3 grids with central cell dead and 8 neighbours alive
        $board[2][2]=true;
        $game = new GameOfLife($board);
        $this->assertFalse( $game->willCellLive(1, 1) );

    }

    /**
     * @dataProvider boardsBeforeAndAfterOneGeneration
     */
    public function testBoardUpdatesCorrectly($initialBoard, $expectedBoardAfterOneGeneration):void
    {
        $game = new GameOfLife($initialBoard);
        $game->nextGen();
        $this->assertEquals( $expectedBoardAfterOneGeneration, $game->getBoard() );
    }

    public function boardsBeforeAndAfterOneGeneration():array
    {
        return [
            [ 
                [
                    []
                ],
                [
                    []
                ]
            ],
            [
                [
                    [true]
                ],
                [
                    [false]
                ]
            ],
            [
                [ //"Block" according to https://en.wikipedia.org/wiki/Conway's_Game_of_Life#Examples_of_patterns
                    [false, false, false, false],
                    [false, true, true, false],
                    [false, true, true, false],
                    [false, false, false, false]
                ],
                [
                    [false, false, false, false],
                    [false, true, true, false],
                    [false, true, true, false],
                    [false, false, false, false]
                ]
            ],
            [
                [ //"Blinker" according to https://en.wikipedia.org/wiki/Conway's_Game_of_Life#Examples_of_patterns
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                    [false, true, true, true, false],
                    [false, false, false, false, false],
                    [false, false, false, false, false]
                ],
                [
                    [false, false, false, false, false],
                    [false, false, true, false, false],
                    [false, false, true, false, false],
                    [false, false, true, false, false],
                    [false, false, false, false, false]
                ],
            ],
            [
                [ // Rotated "Blinker" according to https://en.wikipedia.org/wiki/Conway's_Game_of_Life#Examples_of_patterns
                    [false, false, false, false, false],
                    [false, false, true, false, false],
                    [false, false, true, false, false],
                    [false, false, true, false, false],
                    [false, false, false, false, false]
                ],
                [
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                    [false, true, true, true, false],
                    [false, false, false, false, false],
                    [false, false, false, false, false]
                ],
            ]
        ];
    }
}
