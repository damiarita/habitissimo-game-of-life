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
            //We check that the content of the array is correct
            foreach($this->board as $row):
                if( !\is_array($row) ):
                    throw new Exception('All rows of the board must be defined as arrays.');
                endif;

                if( !isset($this->numColumns) ):
                    $this->numColumns = \count($row);
                else:
                    if( \count($row)!==$this->numColumns ):
                        throw new Exception('All the rows of the board must have the same length to define a rectangle.');
                    endif;
                endif;
                foreach($row as $cell):
                    if( !\is_bool($cell) ):
                        throw new Exception('All cells must be defined as booleans.');
                    endif;
                endforeach;
            endforeach;

        endif;
    }
}