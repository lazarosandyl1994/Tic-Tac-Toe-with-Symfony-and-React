<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cells;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $winner;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $next;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCells(): ?string
    {
        return $this->cells;
    }

    public function setCells(string $cells): self
    {
        $this->cells = $cells;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function setNext(string $next): self
    {
        $this->next = $next;

        return $this;
    }

    public function toogleNext(): self
    {
        if($this->getNext() == 'X')
            $this->setNext('O');
        else
            $this->setNext('X');

        return $this;
    }
}
