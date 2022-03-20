<?php

namespace App\Tests\Unit;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Tests\Stubs\GameStub;
use App\Tests\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class GameContextUnitTestCase extends UnitTestCase
{
    private $games;

    protected function games(): MockObject
    {
        return $this->games = $this->games ?: $this->mock(GameRepository::class);
    }


    protected function shouldSaveGame(Game $game)
    {
        $gameToReturn = GameStub::create([
            'id' => rand(1, 9999),
        ]);

        $this->games()
            ->method('add')
            ->with($game)
            ->willReturn($gameToReturn);
    }

    protected function shouldGetGamesWonBy()
    {
        $this->games()
            ->method('getGamesWonBy')
            ->withAnyParameters()
            ->willReturn(5);
    }

}