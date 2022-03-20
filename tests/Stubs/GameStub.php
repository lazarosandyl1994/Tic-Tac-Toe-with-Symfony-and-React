<?php

namespace App\Tests\Stubs;


use App\Entity\Game;

final class GameStub
{


    public static function create(array $customParams = []): Game
    {

        $params = [
            'id' => rand(1, 9999),
            'cells' => '---------',
            'winner' => '-',
            'next' => 'X',
        ];

        $intersectedParams = array_intersect_key($customParams, $params);
        $params = array_merge($params, $intersectedParams);

        return new Game($params);
    }

    public static function createDrawGame(array $customParams = []): Game
    {

        $params = [
            'id' => rand(1, 9999),
            'cells' => 'XXOOXXXOO',
            'winner' => 'D',
            'next' => '0',
        ];

        $intersectedParams = array_intersect_key($customParams, $params);
        $params = array_merge($params, $intersectedParams);

        return new Game($params);
    }

    public static function createXWonGame(array $customParams = []): Game
    {

        $params = [
            'id' => rand(1, 9999),
            'cells' => 'XXX-O-O--',
            'winner' => 'X',
            'next' => '0',
        ];

        $intersectedParams = array_intersect_key($customParams, $params);
        $params = array_merge($params, $intersectedParams);

        return new Game($params);
    }

    public static function createOWonGame(array $customParams = []): Game
    {

        $params = [
            'id' => rand(1, 9999),
            'cells' => 'XO-XOX-O-',
            'winner' => 'O',
            'next' => 'X',
        ];

        $intersectedParams = array_intersect_key($customParams, $params);
        $params = array_merge($params, $intersectedParams);

        return new Game($params);
    }


}