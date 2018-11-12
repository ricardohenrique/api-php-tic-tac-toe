<?php

namespace Tests\Integration;

use Tests\Setup;
use Tests\TestCase;
use App\Models\Matches;
use App\Support\TicTacToeSupport;

class MatchTest extends Setup
{
    use TicTacToeSupport;

    /**
     * Test to see index
     *
     * @return void
     */
    public function testSeeIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test to get all matches
     *
     * @return void
     */
    public function testGetAllMatches()
    {
        $response = $this->get('/api/match');

        $response->assertStatus(200);
        $response->assertJsonStructure([$this->getStructureMatch()]);
    }

    /**
     * Test to get only one match
     *
     * @return void
     */
    public function testGetMatch()
    {
        $match = factory(Matches::class)->create();
        $response = $this->get('/api/match/'.$match->id);
        $response->assertStatus(200);
        $response->assertJson($match->toArray());
    }

    /**
     * Test to create match
     *
     * @return void
     */
    public function testCreateMatch()
    {
        $response = $this->post('/api/match/');
        $response->assertStatus(200);
        $response->assertJsonStructure([$this->getStructureMatch()]);
    }

    /**
     * Test to play match
     *
     * @return void
     */
    public function testPlayMatch()
    {
        for ($i = 0; $i < 5; $i++) {
            $validateMatch = false;
            $match = factory(Matches::class)->create();

            while ($validateMatch == false) {
                $matchModel = Matches::find($match->id);
                $movement = $this->getMovement($matchModel->board);

                $response = $this->put('/api/match/'.$match->id, ["position" => $movement]);
                if ($response->getStatusCode() == 400) {
                    $response->assertStatus(400);
                    $response->assertJson(["match-already-finished"]);
                    $validateMatch = true;
                }
            }
        }
    }

    /**
     * Test to play match with illegal movement
     *
     * @return void
     */
    public function testPlayMatchIllegalMovement()
    {
        $validateMatch = false;
        $match = factory(Matches::class)->create();

        $matchModel = Matches::find($match->id);
        $movement = $this->getMovement($matchModel->board);
        while ($validateMatch == false) {
            $response = $this->put('/api/match/'.$match->id, ["position" => $movement]);
            if ($response->getStatusCode() == 400) {
                $response->assertStatus(400);
                $response->assertJson(["illegal-movement"]);
                $validateMatch = true;
            }
        }
    }

    /**
     * Test to delete match
     *
     * @return void
     */
    public function testDeleteMatch()
    {
        $match = factory(Matches::class)->create();
        $response = $this->delete('/api/match/'.$match->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([$this->getStructureMatch()]);
        $response->assertJsonMissing([$match->name]);
    }


    /**
     * Test to delete match
     *
     * @return void
     */
    public function testCommandMatch()
    {
        $this->artisan('php artisan tictactoe:play')
         // ->expectsQuestion('What is your name?', 'Taylor Otwell')
         // ->expectsQuestion('Which language do you program in?', 'PHP')
         // ->expectsOutput('Your name is Taylor Otwell and you program in PHP.')
         ->assertExitCode(0);
    }
}
