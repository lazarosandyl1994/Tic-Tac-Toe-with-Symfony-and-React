<?php

namespace App\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameService extends AbstractController
{
    /**
     * @var GameRepository
     */
    public $gameRepository;

    /**
     * @param GameRepository $gameRepository
     */
    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param Game $game
     * @return bool
     */
    private function allCellsAreFilled(Game $game): bool
    {
        $cells = $this->toArray($game->getCells());
        foreach ($cells as $cell) {
            if ($cell == "-") {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Game $currentGame
     * @param int $cell
     * @return bool
     */
    public function canBePlayedMove(Game $currentGame, int $cell): bool
    {
        if ($this->isThereAWinner($currentGame) || $this->allCellsAreFilled($currentGame) || ($currentGame->getCells())[$cell] != "-") {
            return false;
        }
        return true;
    }

    /**
     * @return Response
     */
    public function createNewGame(): Response
    {
        $game = new Game();
        $game->setCells("---------");
        $game->setWinner("-");
        $game->setNext("X");

        return $this->json([
            'game_id' => $this->gameRepository->add($game)->getId(),
            'xWinned' => $this->gameRepository->getGamesWinnedBy('X'),
            'oWinned' => $this->gameRepository->getGamesWinnedBy('O'),
            'draws' => $this->gameRepository->getGamesWinnedBy('D'),
        ]);
    }

    /**
     * @param Game $currentGame
     * @param int $cell
     * @param String $cellType
     * @return string
     */
    public function formatCells(Game $currentGame, int $cell, string $cellType): string
    {
        $currentGame = $this->toArray($currentGame->getCells());
        for ($i = 0; $i < 9; $i++) {
            if ($i == $cell) {
                $currentGame[$i] = $cellType;
            }
        }
        return $this->toString($currentGame);
    }

    /**
     * @param Game $game
     * @return string|null
     */
    public function isThereAWinner(Game $game): ?string
    {
        $cells = $this->toArray($game->getCells());

        $lines = [
            [0, 1, 2],
            [3, 4, 5],
            [6, 7, 8],
            [0, 3, 6],
            [1, 4, 7],
            [2, 5, 8],
            [0, 4, 8],
            [2, 4, 6],
        ];
        for ($i = 0; $i < count($lines); $i++) {
            [$a, $b, $c] = $lines[$i];
            if ($cells[$a] != "-" && $cells[$a] === $cells[$b] && $cells[$a] === $cells[$c]) {
                return $cells[$a];
            }
        }
        return null;
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function playMove(int $id, Request $request): Response
    {
        $currentGame = $this->gameRepository->find($id);

        if (!$currentGame) {
            return $this->json(['status' => 404, 'message' => 'Game not found']);
        }

        return $this->updateGame($request, $currentGame);
    }

    /**
     * @param Request $request
     * @param Game $currentGame
     * @return Response
     */
    private function updateGame(Request $request, Game $currentGame): Response
    {
        $content = json_decode($request->getContent());

        if($this->isThereAWinner($currentGame) || $this->allCellsAreFilled($currentGame)) {
            return $this->json([
                'status' => 400,
                'winner' => $this->isThereAWinner($currentGame),
                'message' => 'Game is over'
            ]);
        }

        if ($canBePlayed = $this->canBePlayedMove($currentGame, $content->cell)) {
            $currentGame->setCells($this->formatCells($currentGame, $content->cell, $currentGame->getNext()));
            $currentGame->toogleNext();
        }

        if ($winner = $this->isThereAWinner($currentGame)) {
            $currentGame->setWinner($winner);
        } else if ($this->allCellsAreFilled($currentGame)) {
            $currentGame->setWinner("D");
        }

        $newGame = $this->gameRepository->update($currentGame);

        return $this->json([
            'next' => $newGame->getNext(),
            'cells' => $this->toArray($newGame->getCells(),true),
            'winner' => $newGame->getWinner(),
            'canBePlayed' => $canBePlayed
        ]);
    }

    /**
     * @param string $cells
     * @param bool $nullable
     * @return array
     */
    private function toArray(string $cells, bool $nullable = false): array
    {
        $cellsAux = [];
        for ($i = 0; $i < 9; $i++) {
            if ($cells[$i] == "X") {
                $cellsAux[] = "X";
            } else if ($cells[$i] == "O") {
                $cellsAux[] = "O";
            } else {
                $cellsAux[] = $nullable ? null : "-";
            }
        }
        return $cellsAux;
    }

    /**
     * @param array $cells
     * @param bool $nullable
     * @return string
     */
    private function toString(array $cells, bool $nullable = false): string
    {
        $cellsAux = "";

        for ($i = 0; $i < 9; $i++) {
            if ($cells[$i] == "X") {
                $cellsAux .= "X";
            } else if ($cells[$i] == "O") {
                $cellsAux .= "O";
            } else {
                $cellsAux .= $nullable ? null : "-";
            }
        }

        return $cellsAux;
    }

}