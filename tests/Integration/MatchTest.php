<?php

namespace Tests\Integration;

use Tests\Setup;
use App\Models\Matches;

class MatchTest extends Setup
{
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

    private function getStructureMatch()
    {
        $match = factory(Matches::class)->make();
        return array_keys($match->getAttributes());

    }
}
