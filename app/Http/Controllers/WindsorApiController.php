<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WindsorApiController extends Controller
{
    public function getDataFromWindsor()
    {
        $client = new Client();

        $url = 'https://connectors.windsor.ai/all';

        // Parameters for the API request
        $query = [
            'api_key' => '2f2d0ad72530a8eda9eded077e58d77cf907', 
            'date_from' => '2024-01-01',        
            'date_to' => '2024-09-01',      
            'fields' => 'campaign,clicks,impressions,cost', 
            'source' => 'google_ads',
            '_renderer' => 'csv' // Explicitly set renderer to CSV
        ];

        try {
            // GET request to Windsor.ai API
            $response = $client->get($url, ['query' => $query]);

            $body = $response->getBody()->getContents();

            // Since the response is CSV, use str_getcsv to parse it
            $lines = explode(PHP_EOL, $body);
            $data = array_map('str_getcsv', $lines);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

