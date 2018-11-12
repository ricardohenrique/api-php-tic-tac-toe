<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Business\MatchBusiness;
use App\Support\TicTacToeSupport;

class PlayTicTacToe extends Command
{
    use TicTacToeSupport;

    /**
      * Variable to define board default
      * @access public
      * @name $BOARD
    */
    public static $BOARD = [
        [0, 0, 0],
        [0, 0, 0],
        [0, 0, 0],
    ];

    /**
      * Variable to define board default
      * @access public
      * @name $BOARD
    */
    public static $PLAYERS = [
        'X' => 1,
        'O' => 2
    ];

    /**
      * Variable to define business
      * @access protected
      * @name $matchBusiness
    */
    protected $matchBusiness;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tictactoe:play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play Tic Tac Toe';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MatchBusiness $matchBusiness)
    {
        parent::__construct();
        $this->matchBusiness = $matchBusiness;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $player = $this->choice('Who would you like to be?', ['X', 'O'], 0);

        $this->table([], self::$BOARD);
        $match = $this->matchBusiness->createMatch();
        $matchId = $match['id'];
        $validateMatch = false;
        while ($validateMatch == false) {
            if (self::$PLAYERS[$player] == $match['next']) {
                $movement = $this->choice('What your movement?', $this->getRemainingMovement($match['board']));
            } else {
                $this->info("Rodada da máquina");
                sleep(2);
                $movement = $this->getMovement($match["board"]);
            }
            $this->matchBusiness->makeMovement($matchId, $movement);
            $match = $this->matchBusiness->getMatch($matchId);
            $this->updateBoardDefault($match['board']);
            $this->table([], self::$BOARD);

            if (($match['winner'] != 0) || (!$this->validateBoard($match['board']))) {
                $validateMatch = true;
            }
        }

        if ($match['winner'] == 0) {
            $this->info("TIE!");
        } else {
            $this->info("WINNER: " . $match['winner']);
        }
    }

    /**
      * Method to update board default by another
      * @access private
      * @param array $board
    */
    private function updateBoardDefault($board)
    {
        self::$BOARD[0][0] = $board[0];
        self::$BOARD[0][1] = $board[1];
        self::$BOARD[0][2] = $board[2];

        self::$BOARD[1][0] = $board[3];
        self::$BOARD[1][1] = $board[4];
        self::$BOARD[1][2] = $board[5];

        self::$BOARD[2][0] = $board[6];
        self::$BOARD[2][1] = $board[7];
        self::$BOARD[2][2] = $board[8];
    }

    /**
      * Method to get remaining movement
      * @access private
      * @param array $board
      * @return array
    */
    private function getRemainingMovement($board) : array
    {
        $board = $this->getClearBoard($board);
        $newOptions = [];
        foreach ($board as $key => $value) {
            $newOptions[$key] = $key;
        }

        return $newOptions;
    }
}
