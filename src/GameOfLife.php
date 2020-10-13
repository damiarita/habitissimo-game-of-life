<?php
namespace GoL;

class GameOfLife
{
    /**
     * This variable contains the state of all the cells in the board.
     * The structure of the board is an array of rows. Each row is an array of cells represented by a boolean. Cells can be alive (true) or dead (false).
     *
     * @var array
     */
    protected $board;

    /**
     * Number of rows in the board.
     *
     * @var int
     */
    protected $numRows;

    /**
     * Number of columns in the board
     *
     * @var int
     */
    protected $numColumns;

    /**
     * Create a GameOfLife instanct from an array of arrays of booleans.
     * The function takes an array of arrays of booleans which represents a board where cells can be alive (true) or dead (false)
     *
     * @param array $board This array must contain arrays (all with the same length) which must contain booleans.
     */
    public function __construct(array $board)
    {
        //We fill the protectes properties
        $this->board = $board;
        $this->numRows = \count($this->board);
        
        if( $this->numRows==0 ):
            $this->numColumns = 0;
        else:
            //we check that the array of rows is sequential
            if( !self::arrayIsSequential($this->board) ):
                throw new \InvalidArgumentException('The array of rows has to be sequential. The keys of the array have to be range from 0 to N-1');
            endif;

            //We check that the content of the array is correct
            foreach($this->board as $row):
                if( !\is_array($row) ):
                    throw new \InvalidArgumentException('All rows of the board must be defined as arrays.');
                endif;
                //we check that the array of cells is sequential
                if( !self::arrayIsSequential($row) ):
                    throw new \InvalidArgumentException('The array of cells has to be sequential. The keys of the array have to be range from 0 to N-1');
                endif;

                if( !isset($this->numColumns) ):
                    $this->numColumns = \count($row);
                else:
                    if( \count($row)!==$this->numColumns ):
                        throw new \InvalidArgumentException('All the rows of the board must have the same length to define a rectangle.');
                    endif;
                endif;
                foreach($row as $cell):
                    if( !\is_bool($cell) ):
                        throw new \InvalidArgumentException('All cells must be defined as booleans.');
                    endif;
                endforeach;
            endforeach;

        endif;
    }

    /**
     * Checks if the keys of an array are a range like 0, 1, 2....
     * This would return false in arrays like array('a'=>'foo','b'=>'bar') and would return true in arrays like array(0=>'foo', 1=>'bar')
     *
     * @param array $testArray The array that has to be tested
     * @return boolean
     */
    private static function arrayIsSequential(array $testArray):bool
    {
        return array_keys($testArray)===range( 0, \count($testArray)-1 );
    }

    /**
     * Getter for numRows.
     * It returns the number of rows of the board
     *
     * @return integer
     */
    public function getNumRows():int
    {
        return $this->numRows;
    }

    /**
     * Getter for numColumns.
     * It returns the number of columns of the board
     *
     * @return integer
     */
    public function getNumColumns():int
    {
        return $this->numColumns;
    }

    /**
     * Returns an array of booleans with the state of the cells that are in touch with the cell at row $rowIndex and column $columnIndex
     *
     * @param integer $rowIndex
     * @param integer $columnIndex
     * @return array
     */
    public function getNeighboursOfCell(int $rowIndex, int $columnIndex):array
    {
        $columnToTheLeftIsInsideBoard = $columnIndex>0;
        $columnToTheRightIsInsideBoard = $columnIndex<($this->getNumColumns()-1);
        $rowOnTopIsInsideBoard = $rowIndex>0;
        $rowBeneathIsInsideBoard = $rowIndex<($this->getNumColumns()-1);

        $result = array();

        //We check the neighbours on the row on top.
        if($rowOnTopIsInsideBoard):
            if($columnToTheLeftIsInsideBoard):
                $result[] = $this->isCellAlive($rowIndex-1, $columnIndex-1);
            endif;
            $result[] = $this->isCellAlive($rowIndex-1, $columnIndex);
            if($columnToTheRightIsInsideBoard):
                $result[] = $this->isCellAlive($rowIndex-1, $columnIndex+1);
            endif;
        endif;

        //We check the neighbours on the same row.
        if($columnToTheLeftIsInsideBoard):
            $result[] = $this->isCellAlive($rowIndex, $columnIndex-1);
        endif;
        if($columnToTheRightIsInsideBoard):
            $result[] = $this->isCellAlive($rowIndex, $columnIndex+1);
        endif;

        //We check the neighbours on the row below.
        if($rowBeneathIsInsideBoard):
            if($columnToTheLeftIsInsideBoard):
                $result[] = $this->isCellAlive($rowIndex+1, $columnIndex-1);
            endif;
            $result[] = $this->isCellAlive($rowIndex+1, $columnIndex);
            if($columnToTheRightIsInsideBoard):
                $result[] = $this->isCellAlive($rowIndex+1, $columnIndex+1);
            endif;
        endif;

        return $result;
    }

    /**
     * Returns the state of a cell at row $rowIndex and column $columnIndex.
     * This function is a wrapper of the array $this->board
     *
     * @param integer $rowIndex
     * @param integer $columnIndex
     * @return boolean If true is returned, the cell is alive. If false is returned, the cell is dead
     */
    protected function isCellAlive(int $rowIndex, int $columnIndex):bool
    {
        return $this->board[$rowIndex][$columnIndex];
    }

    /**
     * Returns the number of neightbours of ($rowIndex, $columnIndex) that are alive
     *
     * @param integer $rowIndex
     * @param integer $columnIndex
     * @return integer
     */
    public function getNumberOfAliveNeighbours(int $rowIndex, int $columnIndex):int
    {
        $neighbours = $this->getNeighboursOfCell($rowIndex, $columnIndex);

        return \count( array_filter($neighbours, function(bool $cellIsAlive):bool{return $cellIsAlive;}) );
    }

}