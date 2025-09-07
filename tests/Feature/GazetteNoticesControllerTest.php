<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class GazetteNoticesControllerTest extends TestCase
{

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_loads_the_index_page_successfully()
    {
        // Fake an API response
        Http::fake([
            'https://www.thegazette.co.uk/*' => Http::response([
                'totalResults' => 100,
                'notices' => [
                    [
                        'title' => 'Sample Notice',
                        'link' => 'https://www.thegazette.co.uk/all-notices/notice/data.json',
                        'content' => 'This is a test notice',
                        'date' => '2025-09-01'
                    ]
                ]
            ], 200),
        ]);

        $response = $this->get('/gazette-notices?page=1');

        $response->assertStatus(200);
        $response->assertViewIs('gazette.index');
        $response->assertViewHas('paginator');
    }

    /** @test */
    public function it_handles_api_failure_gracefully()
    {
        // Simulate API failure
        Http::fake([
            'https://www.thegazette.co.uk/*' => Http::response(null, 500),
        ]);

        $response = $this->get('/gazette/notices?page=1');
        echo $response->assertStatue;
        $response->assertStatus(500);
        $response->assertJson(['error' => 'Unable to fetch Gazette notices.']);
    }
}

