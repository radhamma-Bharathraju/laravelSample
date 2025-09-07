<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GazetteNoticesControllerTest extends TestCase
{

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function testLoadsTheIndexPageSuccessfully()
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

        $response = $this->get('/gazette/notices');

        $response->assertStatus(200);
        $response->assertViewIs('gazette.index');
        $response->assertViewHas('paginator');
    }

    public function testRouteNotFound()
    {
        $this->withoutExceptionHandling(); // ensures exception bubbles up

        $this->expectException(NotFoundHttpException::class);

        $this->get('/non-existing-route');
    }
    /** @test */
    public function testHandlesApiFailureGracefully()
    {
        Http::fake([
            'https://www.thegazette.co.uk/*' => Http::response(null, 404),
        ]);

        $response = $this->get('/gazette-notices?page=1');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'The route gazette-notices could not be found.']);
    }

}

