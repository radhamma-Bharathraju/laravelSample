<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Client;

class GazetteNoticesController extends Controller
{
    protected string $apiUrl = 'https://www.thegazette.co.uk/all-notices/notice/data.json';
    protected int $perPage = 5;

    public function index(Request $request)
    {
        
        $page = $request->query('page', 1);

        $client = new Client();

        try {
            $response = $client->get($this->apiUrl, [
                'query' => ['results-page' => $page],
                'verify' => false, // Disable SSL verification for self-signed cert
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch Gazette notices.'], 500);
        }

        $keys = array_keys($data);
        $values = array_values($data);
        $totalResults = Arr::get($data, 'totalResults', 1000);
        $paginator = new LengthAwarePaginator(
            $data,
            $totalResults,
            $this->perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('gazette.index', compact('paginator'));
    }
}
