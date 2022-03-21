<?php

namespace App\Tests\Unit\Service;

use App\Service\AIGameService;
use App\Tests\Stubs\GameStub;
use App\Tests\Unit\GameContextUnitTestCase;

class AIGameServiceTest extends GameContextUnitTestCase
{
    /**
     * @var AIGameService
     */
    private $service;

    private $huPlayer = "X";
    private $aiPlayer = "O";

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AIGameService(
            $this->games()
        );
    }

    public function testMinimaxAiAlgorithmReturnValidIndexAndScoresWhenAvailableMoves(): void
    {
        $game = GameStub::create(['cells' => 'X--------']);
        $minimaxAnalysis = $this->service->minimax($this->service->toArray($game->getCells(),true),$this->aiPlayer);
        $this->assertIsArray($minimaxAnalysis);
        $this->assertTrue(in_array($minimaxAnalysis['index'], [0,1,2,3,4,5,6,7,8]));
        $this->assertTrue(in_array($minimaxAnalysis['score'], [-10,0,10]));
    }

    public function testMinimaxAiAlgorithmReturnValidIndexAndScoresWhenOWon(): void
    {
        $game = GameStub::createOWonGame();
        $minimaxAnalysis = $this->service->minimax($this->service->toArray($game->getCells(),true),$this->aiPlayer);
        $this->assertIsArray($minimaxAnalysis);
        $this->assertArrayNotHasKey('index',$minimaxAnalysis);
        $this->assertEquals(10,$minimaxAnalysis['score']);
    }

    public function testMinimaxAiAlgorithmReturnValidIndexAndScoresWhenXWon(): void
    {
        $game = GameStub::createXWonGame();
        $minimaxAnalysis = $this->service->minimax($this->service->toArray($game->getCells(),true),$this->aiPlayer);
        $this->assertIsArray($minimaxAnalysis);
        $this->assertArrayNotHasKey('index',$minimaxAnalysis);
        $this->assertEquals(-10,$minimaxAnalysis['score']);
    }

    public function testMinimaxAiAlgorithmReturnValidIndexAndScoresWhenDraw(): void
    {
        $game = GameStub::createDrawGame();
        $minimaxAnalysis = $this->service->minimax($this->service->toArray($game->getCells(),true),$this->aiPlayer);
        $this->assertIsArray($minimaxAnalysis);
        $this->assertArrayNotHasKey('index',$minimaxAnalysis);
        $this->assertEquals(0,$minimaxAnalysis['score']);
    }

    public function testAreThereAvailableMoves(): void
    {
        $game = GameStub::create(['cells' => 'X--------']);
        $this->assertNotEmpty($this->service->availableMoves($this->service->toArray($game->getCells())));
    }

    public function testNoAvailableMoves(): void
    {
        $game = GameStub::createDrawGame();
        $this->assertEmpty($this->service->availableMoves($this->service->toArray($game->getCells())));
    }

    public function testXWonTheGame(): void
    {
        $game = GameStub::createXWonGame();
        $this->assertTrue($this->service->winning($this->service->toArray($game->getCells()),"X"));
    }

    public function testOWonTheGame(): void
    {
        $game = GameStub::createOWonGame();
        $this->assertTrue($this->service->winning($this->service->toArray($game->getCells()),"O"));
    }

    public function testTheGameWasDraw(): void
    {
        $game = GameStub::createDrawGame();
        $this->assertFalse($this->service->winning($this->service->toArray($game->getCells()),"X"));
        $this->assertFalse($this->service->winning($this->service->toArray($game->getCells()),"O"));
    }



    protected function tearDown(): void
    {
        parent::tearDown();
        $this->service = null;
    }
}