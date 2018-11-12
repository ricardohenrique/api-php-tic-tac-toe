<?php

namespace App\Business;

use App\Models\Matches;
use App\Exceptions\MatchAlreadyFinishedException;
use App\Exceptions\IllegalMovementException;

class MatchBusiness
{
    /**
      * Variable to define board default
      * @access public
      * @name $BOARD
    */
    public static $BOARD = [
        0, 0, 0,
        0, 0, 0,
        0, 0, 0,
    ];

	/**
	 * Returns a list of matches or only one of them
	 * @access public
	 * @param integer $matchId | id of some match
	 * @return array
	 */ 
    public function getMatch(int $matchId = 0) : array
    {
    	$matches = new Matches;
    	if($matchId) {
    		return $matches->where("id", $matchId)->first()->toArray();
    	}
        return $matches->get()->toArray();
    }

	/**
	 * Make a movement
	 * @access public
	 * @param integer $matchId | id of some match
	 * @param integer $position | number of some position in the board
	 * @return array
	 */ 
    public function makeMovement(int $matchId, int $position) : void
    {
        $match = $this->getMatche($matchId);
        if(($match['winner'] != 0) || (!$this->validateBoard($match['board']))) {
        	throw new MatchAlreadyFinishedException("match-already-finished");
        }
        if(!$this->validateMovement($match['board'], $position)) {
        	throw new IllegalMovementException("illegal-movement");
        }
        $match['board'][$position] = $match['next'];
        $win = $this->validateWinner($match['board'], $match['next']);

        Matches::where('id', $matchId)->update([
            'next'   => $this->getWhoIsNext($match['next']),
            'board'  => json_encode($match['board']),
            'winner' => $win
        ]);
    }

    /**
	 * Creates a new match and returns it
	 * @access public
	 * @return array
	 */ 
    public function createMatch() : array
    {
    	return factory(Matches::class)->create()->toArray();
    }

    /**
	 * Delete the match
	 * @access public
	 * @param integer $matchId | id of some match
	 * @return array
	 */ 
    public function deleteMatch($matchId) : bool
    {
    	return Matches::destroy($matchId);
    }

    /**
      * Method to validate movement
      * @access private
      * @param array $board
      * @return boolean
    */
    private function validateMovement(array $board, int $position) : bool
    {
    	$validate = false;
        if ($board[$position] == 0) {
            $validate = true;
        }
        return $validate;
    }

    /**
      * Method to validate board
      * @access private
      * @param array $board
      * @return boolean
    */
    private function validateBoard(array $board) : bool
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
      * @access private
      * @param array $board
      * @param int $player
      * @return int
    */
    private function validateWinner(array $board, int $player) : int
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
      * @access private
      * @param int $currentPlayer
      * @return int
    */
    private function getWhoIsNext($currentPlayer) : int
    {
        if ($currentPlayer == 1) {
            return 2;
        }
        return 1;
    }
}
