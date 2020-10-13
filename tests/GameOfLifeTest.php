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
        $this->assertFalse( $method->invokeArgs(null, [array('key1'=>'a', 'key2'=>'b', 'key3'=>'c')]) );
    }
}
