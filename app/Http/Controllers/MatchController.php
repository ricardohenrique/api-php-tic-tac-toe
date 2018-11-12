<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Http\Requests\MatchesMoveRequest;
use App\Business\MatchBusiness;
use App\Exceptions\MatchAlreadyFinishedException;
use App\Exceptions\IllegalMovementException;
use Log;

class MatchController extends Controller
{
    public $matchBusiness;

    function __construct(MatchBusiness $matchBusiness)
    {
        $this->matchBusiness = $matchBusiness;
    }

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
        return response()->json($this->matchBusiness->getMatch()); 
    }

    /**
     * Returns the state of a single match
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function match($id)
    {
        return response()->json($this->matchBusiness->getMatch($id));
    }

    /**
     * Makes a move in a match
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function move(MatchesMoveRequest $request, $id)
    {
        try {
            $position = Input::get('position');
            $this->matchBusiness->makeMovement($id, $position);

            return response()->json($this->matchBusiness->getMatch($id));
        } catch (MatchAlreadyFinishedException $e) {
            Log::info($e->getMessage());
            return response()->json([$e->getMessage()], 400);
        } catch (IllegalMovementException $e) {
            Log::info($e->getMessage());
            return response()->json([$e->getMessage()], 400);
        }  
    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $this->matchBusiness->createMatch();
        return response()->json($this->matchBusiness->getMatch());
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $this->matchBusiness->deleteMatch($id);
        return response()->json($this->matchBusiness->getMatch());
    }
}
