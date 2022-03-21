<?php

namespace App\Service;

use App\Entity\Game;
use Symfony\Component\HttpFoundation\Request;

class AIGameService extends GameService
{
    private $huPlayer = "X";
    private $aiPlayer = "O";

    public function updateGame(Request $request, Game $currentGame): array
    {
        $content = json_decode($request->getContent());

        if ($this->isThereAWinner($currentGame) || $this->allCellsAreFilled($currentGame)) {
            return [
                'status' => 400,
                'winner' => $this->isThereAWinner($currentGame),
                'message' => 'Game is over'
            ];
        }

        if ($canBePlayed = $this->canBePlayedMove($currentGame, $content->cell)) {
            $currentGame->setCells($this->fillCell($currentGame, $content->cell, $currentGame->getNext()));
            if ($this->isThereAWinner($currentGame)) {
                $currentGame->setWinner("X");
            } else if ($this->allCellsAreFilled($currentGame)) {
                $currentGame->setWinner("D");
            } else {
                $cell = $this->minimax($this->toArray($currentGame->getCells(), true), $this->aiPlayer);
                $currentGame->setCells($this->fillCell($currentGame, $cell['index'], $this->aiPlayer));
                if ($this->isThereAWinner($currentGame)) {
                    $currentGame->setWinner("O");
                } else if ($this->allCellsAreFilled($currentGame)) {
                    $currentGame->setWinner("D");
                }
            }
        }
        $newGame = $this->gameRepository->update($currentGame);

        return [
            'next' => $newGame->getNext(),
            'cells' => $this->toArray($newGame->getCells(), true),
            'winner' => $newGame->getWinner(),
            'canBePlayed' => $canBePlayed
        ];
    }

    public function minimax(array $game, string $player)
    {
        for ($i = 0 ; $i < 9 ; $i++) {
            if (!$game[$i]) {
                $game[$i] = $i;
            }
        }
        $available = $this->availableMoves($game);
        if ($this->winning($game, $this->huPlayer)) {
            return ['score' => -10];
        } else if ($this->winning($game, $this->aiPlayer)) {
            return ['score' => 10];
        } else if (empty($available)) {
            return ['score' => 0];
        }
        $moves = [];
        for ($i = 0; $i < count($available); $i++) {
            $move = [];
            $move['index'] = $game[$available[$i]];
            $game[$available[$i]] = $player;
            if ($player == $this->aiPlayer) {
                $result = $this->minimax($game, $this->huPlayer);
            } else {
                $result = $this->minimax($game, $this->aiPlayer);
            }
            $move['score'] = $result['score'];
            $game[$available[$i]] = $move['index'];
            $moves[] = $move;
        }
        $bestMove = null;
        if ($player == $this->aiPlayer) {
            $bestScore = -10000;
            for ($i = 0; $i < count($moves); $i++) {
                if ($moves[$i]['score'] > $bestScore) {
                    $bestScore = $moves[$i]['score'];
                    $bestMove = $i;
                }
            }
        } else {
            $bestScore = 10000;
            for ($i = 0; $i < count($moves); $i++) {
                if ($moves[$i]['score'] < $bestScore) {
                    $bestScore = $moves[$i]['score'];
                    $bestMove = $i;
                }
            }
        }
        return $moves[$bestMove];
    }

    public function availableMoves(array $cells): array
    {
        $availableMoves = [];
        for ($i = 0; $i < 9; $i++) {
            if ($cells[$i] != "X" && $cells[$i] != "O") {
                $availableMoves[] = $i;
            }
        }
        return $availableMoves;
    }

    public function winning(array $board, string $player): bool
    {
        if (
            ($board[0] == $player && $board[1] == $player && $board[2] == $player) ||
            ($board[3] == $player && $board[4] == $player && $board[5] == $player) ||
            ($board[6] == $player && $board[7] == $player && $board[8] == $player) ||
            ($board[0] == $player && $board[3] == $player && $board[6] == $player) ||
            ($board[1] == $player && $board[4] == $player && $board[7] == $player) ||
            ($board[2] == $player && $board[5] == $player && $board[8] == $player) ||
            ($board[0] == $player && $board[4] == $player && $board[8] == $player) ||
            ($board[2] == $player && $board[4] == $player && $board[6] == $player)
        ) {
            return true;
        } else {
            return false;
        }
    }

}