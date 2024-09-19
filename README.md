# Windsor API Integration

This Laravel controller retrieves data from the Windsor.ai API using the Guzzle HTTP client. The controller sends a GET request to the Windsor API, fetches campaign-related data (clicks, impressions, cost), and returns the data as a JSON response.

## Installation and Setup

1. Clone the repository or copy the controller code to your existing Laravel project.

2. Ensure that Guzzle HTTP client is installed in your Laravel project (Guzzle is included by default in Laravel). If you don't have it installed, you can do so via Composer:
   ```bash
   composer require guzzlehttp/guzzle
   ```

3. Add the following route in your `routes/web.php` or `routes/api.php` file (based on your application type).

   ```php
   use App\Http\Controllers\WindsorApiController;

   Route::get('/windsor-data', [WindsorApiController::class, 'getDataFromWindsor']);
   ```

4. Ensure you have valid API credentials for Windsor.ai. Replace the `api_key` in the controller with your actual API key.

## WindsorApiController

The `WindsorApiController` makes an HTTP request to the Windsor.ai API, retrieves campaign performance data, and returns it in JSON format.

### Controller Code:

```php
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
```

### Route

The route `/windsor-data` is set up to call the `getDataFromWindsor` method, which fetches data from the Windsor.ai API and returns it as JSON.

#### Example Route Setup (in `routes/web.php` or `routes/api.php`):

```php
use App\Http\Controllers\WindsorApiController;

Route::get('/windsor-data', [WindsorApiController::class, 'getDataFromWindsor']);
```

### URL Endpoint

To fetch the data, make a GET request to:
```
http://your-domain.com/windsor-data
```

This will return campaign data from Windsor.ai for the specified date range (`2024-01-01` to `2024-09-01`) and fields (campaign, clicks, impressions, cost).

### Response Format

The response will be returned in JSON format. Here’s a sample structure of the response:

```json
[
    ["campaign", "clicks", "impressions", "cost"],
    ["Campaign 1", "100", "2000", "50.00"],
    ["Campaign 2", "150", "3000", "75.00"]
]
```

### Error Handling

If an error occurs during the API request, the following JSON response will be returned:

```json
{
    "error": "Error message from the API or the request"
}
```

## Requirements

- PHP 7.4 or later
- Laravel 8.x or later
- Guzzle HTTP client
- A valid Windsor.ai API key

## License

This project is licensed under the MIT License.

---
