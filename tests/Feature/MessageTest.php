<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Provider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use DatabaseMigrations;

    public function testSuccessStore()
    {
        Provider::factory()->create();
        $response = $this->post('/api/messages/send', [
            'receiver_mobile' => '09375775947',
            'body' => 'Hello',
        ],[
            'accept' => 'application/json'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'track_id',
            ]
        ]);
    }

    public function testFailedStore()
    {
        $this->withExceptionHandling();
        $data = [
            [
                'receiver_mobile' => '09375775947',
            ],
            [
                'body' => 'Hello',
            ],
            [

            ],
        ];
        foreach ($data as $datum) {
            $response = $this->post('/api/messages/send', $datum,[
                'accept' => 'application/json'
            ]);

            $response->assertStatus(422);
            $response->assertJsonStructure([
                'message',
                'errors'
            ]);
        }
    }
}
