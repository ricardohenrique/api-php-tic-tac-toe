<?php

namespace App\Business;

use App\Models\Matches;
use App\Exceptions\MatchAlreadyFinishedException;
use App\Exceptions\IllegalMovementException;
use App\Support\TicTacToeSupport;

class MatchBusiness
{
    use TicTacToeSupport;

    /**
     * Returns a list of matches or only one of them
     * @access public
     * @param integer $matchId | id of some match
     * @return array
     */
    public function getMatch(int $matchId = 0) : array
    {
        $matches = new Matches();
        if ($matchId) {
            return $matches->where("id", $matchId)->first()->toArray();
        }
        return $matches->get()->toArray();
    }

    /**
     * Make a movement
     * @access public
     * @param integer $matchId | id of some match
     * @param integer $position | number of some position in the board
     * @return void
     */
    public function makeMovement(int $matchId, int $position) : void
    {
        $match = $this->getMatch($matchId);
        if (($match['winner'] != 0) || (!$this->validateBoard($match['board']))) {
            throw new MatchAlreadyFinishedException("match-already-finished");
        }
        if (!$this->validateMovement($match['board'], $position)) {
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
     * Creates a new match and return it
     * @access public
     * @return array
     */
    public function createMatch() : array
    {
        return factory(Matches::class)->create()->toArray();
    }

    /**
     * Delete a match
     * @access public
     * @param integer $matchId | id of some match
     * @return bool
     */
    public function deleteMatch($matchId) : bool
    {
        return Matches::destroy($matchId);
    }
}
