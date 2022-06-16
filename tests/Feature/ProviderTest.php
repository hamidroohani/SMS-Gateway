<?php

namespace Tests\Feature;

use App\Models\Provider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProviderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->post('/api/providers/all');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'current_page',
                'data',
                'first_page_url',
            ]
        ]);
    }

    public function testSuccessStore()
    {
        $response = $this->post('/api/providers/create', [
            'name' => 'sms-ir',
            'number' => '10010015',
            'info' => [
                'api_key' => 'this is api key'
            ]
        ],[
            'accept' => 'application/json'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'name',
                'number',
                'info',
            ]
        ]);
        $response->assertSee('sms-ir');
        $response->assertSee('10010015');
        $response->assertSee('this is api key');
    }

    public function testFailedStore()
    {
        $this->withExceptionHandling();
        $data = [
            [
                'icon' => 'icon name',
                'option' => ['key1' => 'value1', 'key2' => 'value2'],
            ],
            [

            ],
        ];
        foreach ($data as $datum) {
            $response = $this->post('/api/providers/create', $datum,[
                'accept' => 'application/json'
            ]);

            $response->assertStatus(422);
            $response->assertJsonStructure([
                'message',
                'errors'
            ]);
        }
    }

    public function testShow()
    {
        $provider = Provider::factory()->create();
        $response = $this->post('/api/providers/show/' . $provider->_id,[
            'accept' => 'application/json'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'name',
                'number',
                'info',
            ]
        ]);

        $response->assertSee($provider->name);
        $response->assertSee($provider->provider_id);
    }

    public function testFailedShow()
    {
        $response = $this->post('/api/providers/show/wrong_id');

        $response->assertStatus(404);
    }

    public function testSuccessUpdate()
    {
        $provider = Provider::factory()->create();
        $response = $this->post('/api/providers/update/' . $provider->_id, [
            'name' => 'sms-ir',
        ],[
            'accept' => 'application/json'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'name',
                'number',
                'info',
            ]
        ]);
        $response->assertSee('sms-ir');
        $response->assertSee($provider->number);
    }

    public function testFailedUpdate()
    {
        $this->withExceptionHandling();
        $provider = Provider::factory()->create();
        $response = $this->post('/api/providers/update/' . $provider->_id, [

        ],[
            'accept' => 'application/json'
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
    }

    public function testDelete()
    {
        $provider = Provider::factory()->create();
        $response = $this->post('/api/providers/delete/' . $provider->_id,[
            'accept' => 'application/json'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data'
        ]);
    }
}
