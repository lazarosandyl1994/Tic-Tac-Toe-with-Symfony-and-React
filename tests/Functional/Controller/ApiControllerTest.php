<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testCanCreateANewGame(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api',[]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("game_id", $response);
        $this->assertArrayHasKey("xWon", $response);
        $this->assertArrayHasKey("oWon", $response);
        $this->assertArrayHasKey("draws", $response);
    }

    public function testPlayerCannotPlayInXWonGameVsAnotherPlayer(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/1', ["cell" => "2", "playVs" => "player"]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("winner", $response);
        $this->assertArrayHasKey("message", $response);
        $this->assertEquals(400, $response["status"]);
        $this->assertEquals("X", $response["winner"]);
        $this->assertEquals("Game is over", $response["message"]);
    }

    public function testPlayerCannotPlayInXWonGameVsComputer(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/1', ["cell" => "2", "playVs" => "computer"]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("winner", $response);
        $this->assertArrayHasKey("message", $response);
        $this->assertEquals(400, $response["status"]);
        $this->assertEquals("X", $response["winner"]);
        $this->assertEquals("Game is over", $response["message"]);
    }

    public function testPlayerCannotPlayInOWonGameVsAnotherPlayer(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/2', ["cell" => "5", "playVs" => "player"]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("winner", $response);
        $this->assertArrayHasKey("message", $response);
        $this->assertEquals(400, $response["status"]);
        $this->assertEquals("O", $response["winner"]);
        $this->assertEquals("Game is over", $response["message"]);
    }

    public function testPlayerCannotPlayInOWonGameVsComputer(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/2', ["cell" => "5", "playVs" => "computer"]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("winner", $response);
        $this->assertArrayHasKey("message", $response);
        $this->assertEquals(400, $response["status"]);
        $this->assertEquals("O", $response["winner"]);
        $this->assertEquals("Game is over", $response["message"]);
    }

    public function testPlayerCanPlayInUnfinishedGameVsAnotherPlayer(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/3', ["cell" => "2", "playVs" => "player"]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("next", $response);
        $this->assertArrayHasKey("cells", $response);
        $this->assertArrayHasKey("winner", $response);
        $this->assertArrayHasKey("canBePlayed", $response);
        $this->assertEquals("X", $response["next"]);
        $this->assertIsArray($response["cells"]);
        $this->assertEquals("-", $response["winner"]);
        $this->assertTrue($response["canBePlayed"]);
    }

    public function testPlayerCanPlayInUnfinishedGameVsComputer(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/3', ["cell" => "2", "playVs" => "computer"]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("next", $response);
        $this->assertArrayHasKey("cells", $response);
        $this->assertArrayHasKey("winner", $response);
        $this->assertArrayHasKey("canBePlayed", $response);
        $this->assertEquals("O", $response["next"]);
        $this->assertIsArray($response["cells"]);
        $this->assertEquals("-", $response["winner"]);
        $this->assertTrue($response["canBePlayed"]);
    }

    public function testPlayerCanNotPlayAnAlreadyFinishedInDrawGameVsAnotherPlayer(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/4', ["cell" => "1", "playVs" => "player"]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("winner", $response);
        $this->assertArrayHasKey("message", $response);
        $this->assertEquals(400, $response["status"]);
        $this->assertNull($response["winner"]);
        $this->assertEquals("Game is over", $response["message"]);
    }

    public function testPlayerCanNotPlayAnAlreadyFinishedInDrawGameVsComputer(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/4', ["cell" => "1", "playVs" => "computer"]);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("winner", $response);
        $this->assertArrayHasKey("message", $response);
        $this->assertEquals(400, $response["status"]);
        $this->assertNull($response["winner"]);
        $this->assertEquals("Game is over", $response["message"]);
    }

}
