<?php

namespace App\Support;

use App\Models\Matches;

trait TicTacToeSupport
{
    /**
      * Method to validate movement
      * @access public
      * @param array $board
      * @return boolean
    */
    public function validateMovement(array $board, int $position) : bool
    {
        $validate = false;
        if ($board[$position] == 0) {
            $validate = true;
        }
        return $validate;
    }

    /**
      * Method to validate board
      * @access public
      * @param array $board
      * @return boolean
    */
    public function validateBoard(array $board) : bool
    {
        $validate = false;
        foreach ($board as $key => $position) {
            if ($position == 0) {
                $validate = true;
                break;
            }
        }
        return $validate;
    }

    /**
      * Method to validate if someone wins the match
      * @access public
      * @param array $board
      * @param int $player
      * @return int
    */
    public function validateWinner(array $board, int $player) : int
    {
        $win = 0;

        $wouldWin = [
             // lines
            [$board[0], $board[1], $board[2]],
            [$board[3], $board[4], $board[5]],
            [$board[6], $board[7], $board[8]],

            // columns
            [$board[0], $board[3], $board[6]],
            [$board[1], $board[4], $board[7]],
            [$board[2], $board[5], $board[8]],

            // diagonals
            [$board[0], $board[4], $board[8]],
            [$board[2], $board[4], $board[6]]
        ];
        
        foreach ($wouldWin as $key => $possibility) {
            if (!(in_array(0, $possibility) || in_array($this->getWhoIsNext($player), $possibility))) {
                $win = $player;
            }
        }

        return $win;
    }

    /**
      * Method to get whi is the next player
      * @access public
      * @param int $currentPlayer
      * @return int
    */
    public function getWhoIsNext($currentPlayer) : int
    {
        if ($currentPlayer == 1) {
            return 2;
        }
        return 1;
    }

    /**
      * Method to get Structure of table match
      * @access public
      * @return int
    */
    public function getStructureMatch() : array
    {
        $match = factory(Matches::class)->make();
        return array_keys($match->getAttributes());
    }

    /**
      * Method to get valid movement
      * @access public
      * @param array $board
      * @return int
    */
    public function getMovement(array $board) : int
    {
        $board = $this->getClearBoard($board);
        if (count($board) == 0) {
            return 0;
        }
        return array_rand($board, 1);
    }

    /**
      * Method to get array of movement
      * @access public
      * @param array $board
      * @return array
    */
    public function getClearBoard(array $board) : array
    {
        foreach ($board as $key => $value) {
            if ($value != 0) {
                unset($board[$key]);
            }
        }

        return $board;
    }
}
