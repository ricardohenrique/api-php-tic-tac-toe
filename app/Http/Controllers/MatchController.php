<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Models\Matches;

class MatchController extends Controller
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

    public function index()
    {
        return view('index');
    }

    /**
     * Returns a list of matches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function matches()
    {
        return response()->json(Matches::all()->toArray());
    }

    /**
     * Returns the state of a single match
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function match($id)
    {
        return response()->json(Matches::find($id)->toArray());
    }

    /**
     * Makes a move in a match
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($id)
    {
        $position = Input::get('position');

        $match                     = Matches::find($id)->toArray();
        $match['board'][$position] = $match['next'];

        $win = $this->validateWinner($match['board'], $match['next']);

        Matches::where('id', $id)->update([
            'next'   => $this->getWhoIsNext($match['next']),
            'board'  => json_encode($match['board']),
            'winner' => $win
        ]);

        return response()->json(Matches::find($id)->toArray());
    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        Matches::create([
            'name' => 'Match',
            'next' => rand(1, 2),
            'winner' => 0,
            'board' => self::$BOARD,
        ]);
        return response()->json(Matches::all()->toArray());
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        Matches::destroy($id);
        return response()->json(Matches::all()->toArray());
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
        $canWin[0] = [$board[0], $board[1], $board[2]];
        $canWin[1] = [$board[3], $board[4], $board[5]];
        $canWin[2] = [$board[6], $board[7], $board[8]];
        $canWin[3] = [$board[0], $board[3], $board[6]];
        $canWin[4] = [$board[1], $board[4], $board[7]];
        $canWin[5] = [$board[2], $board[5], $board[8]];
        $canWin[6] = [$board[0], $board[4], $board[8]];
        $canWin[7] = [$board[2], $board[4], $board[6]];

        foreach ($canWin as $key => $can) {
            if (!(in_array(0, $can) || in_array($this->getWhoIsNext($player), $can))) {
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

    /**
     * Creates a fake array of matches
     *
     * @return \Illuminate\Support\Collection
     */
    private function fakeMatches()
    {
        return collect([
            [
                'id' => 1,
                'name' => 'Match1',
                'next' => 2,
                'winner' => 1,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 2, 1,
                ],
            ],
            [
                'id' => 2,
                'name' => 'Match2',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 0, 0,
                ],
            ],
            [
                'id' => 3,
                'name' => 'Match3',
                'next' => 1,
                'winner' => 0,
                'board' => [
                    1, 0, 2,
                    0, 1, 2,
                    0, 2, 0,
                ],
            ],
            [
                'id' => 4,
                'name' => 'Match4',
                'next' => 2,
                'winner' => 0,
                'board' => [
                    0, 0, 0,
                    0, 0, 0,
                    0, 0, 0,
                ],
            ],
        ]);
    }
}
