<?php

namespace App\Controller;

use App\Service\AIGameService;
use App\Service\GameService;
use App\Traits\TranslateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    use TranslateRequest;

    /**
     * @var GameService
     */
    public $gameService;

    /**
     * @var AIGameService
     */
    private $aIGameService;

    public function __construct(GameService $gameService, AIGameService $aIGameService)
    {
        $this->gameService = $gameService;
        $this->aIGameService = $aIGameService;
    }

    /**
     * @Route("/api", name="api_new", methods={"POST"})
     */
    public function new(): Response
    {
        return $this->json($this->gameService->createNewGame());
    }

    /**
     * @Route("/api/{id}", name="api_edit", methods={"PUT", "PATCH"})
     */
    public function edit(Request $request, int $id): Response
    {
        if ($this->getAttribute($request, "playVs") == "player") {
            return $this->json($this->gameService->playMove($id, $request));
        } else {
            return $this->json($this->aIGameService->playMove($id, $request));
        }
    }
}
