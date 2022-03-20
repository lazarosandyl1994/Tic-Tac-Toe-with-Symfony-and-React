<?php

namespace App\Tests\Unit\Service;

use App\Service\GameService;
use App\Tests\Stubs\GameStub;
use App\Tests\Unit\GameContextUnitTestCase;

class GameServiceTest extends GameContextUnitTestCase
{
    /**
     * @var GameService
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new GameService(
            $this->games()
        );
    }

    public function testallCellsAreEmpty(): void
    {
        $game = GameStub::create();

        $this->assertFalse($this->service->allCellsAreFilled($game));
    }

    public function testallCellsAreFilled(): void
    {
        $game = GameStub::createDrawGame();

        $this->assertTrue($this->service->allCellsAreFilled($game));
    }


    public function testCanBePlayedMove(): void
    {
        $game = GameStub::create(['cells' => 'x----o---']);

        $this->assertTrue($this->service->canBePlayedMove($game,1));
    }

    public function testCannotBePlayedMove(): void
    {
        $game = GameStub::create(['cells' => 'x----o---']);

        $this->assertFalse($this->service->canBePlayedMove($game,0));
    }

    public function testCanCreateNewGame(): void
    {
        $game = GameStub::create([
            'id' => null,
        ]);
        $this->shouldSaveGame($game);
        $this->shouldGetGamesWonBy();

        $data = $this->service->createNewGame();

        $this->assertArrayHasKey('game_id', $data);
        $this->assertArrayHasKey('xWon', $data);
        $this->assertArrayHasKey('oWon', $data);
        $this->assertArrayHasKey('draws', $data);
    }






    protected function tearDown(): void
    {
        parent::tearDown();
        $this->service = null;
    }
}
