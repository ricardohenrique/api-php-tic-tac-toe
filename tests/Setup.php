<?php
namespace Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery;

class Setup extends TestCase
{
    use DatabaseTransactions;
    // use RefreshDatabase;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @after
     */
    public function cleanTransaction()
    {
        // Mockery::close();
        // DB::rollBack();
        // DB::disconnect();
    }
}
