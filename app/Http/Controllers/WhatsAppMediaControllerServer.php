<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Auth\Models\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class WhatsAppMediaController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', '300');
        ini_set('default_socket_timeout', '300');
        ini_set('memory_limit', '256M');
        
        // Add these new settings
        ini_set('allow_url_fopen', '1');
        ini_set('curl.cainfo', dirname(__FILE__) . '/cacert.pem');
        
        // Try to clear DNS cache
        if (function_exists('dns_clear_cache')) {
            dns_clear_cache();
        }
    }

    public function downloadImage(Request $request)
    {
        $sslInfo = [
            'curl.cainfo' => ini_get('curl.cainfo'),
            'openssl.cafile' => ini_get('openssl.cafile'),
            'openssl.capath' => ini_get('openssl.capath')
        ];
        \Log::info('SSL Configuration', $sslInfo);

        $request->validate([
            'media_id' => 'required|string',
            'car_id' => 'required|string',
            'phone' => 'required|string',
        ]);

        $mediaId = $request->input('media_id');
        $phone = $request->input('phone');
        $accessToken = env('WhatsappImageAccessToken');
        $saveDir = 'public/WhatsappImages'; // Storage directory

        // Add proxy settings if needed on server
        $proxy = env('HTTP_PROXY', ''); // Add this to .env if your server needs a proxy
        
        // Check if the phone number exists in the auth table
        $user = Auth::where('phone', $phone)->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Check if the car entry exists
            $findCar = Carlist::find($request->car_id);
            if (!$findCar) {
                return response()->json(['error' => 'Car not found'], 404);
            }


            // $imageData = file_get_contents("https://graph.facebook.com/v18.0/{$mediaId}?fields=url&access_token={$accessToken}");
            // $finfo = new finfo(FILEINFO_MIME_TYPE);
            // $mimeType = $finfo->buffer($imageData);


            $fileName = $mediaId . '.jpg';
            $filePath = $saveDir . '/' . $fileName;
            // $fileUrl = url('storage/WhatsappImages/' . $fileName);
            // $fullFileUrl = env('BASE_URL') . $fileUrl;

            $image->storeAs('uploads', $imageName, 'public');
            $imagePaths = asset('storage/uploads/' . $imageName);

            // Check if the image is already in storage
            if (Storage::exists($filePath)) {
                // Check if the URL is already in the database
                $existingPhotos = explode(',', $findCar->photo_links);
                if (in_array($fullFileUrl, $existingPhotos)) {
                    return response()->json([
                        'message' => 'Image already exists',
                        'file_path' => $fullFileUrl
                    ]);
                }

                // If file exists but URL is not in the database, add it
                $findCar->photo_links = $findCar->photo_links 
                    ? $findCar->photo_links . ',' . $fullFileUrl 
                    : $fullFileUrl;
                $findCar->save();

                return response()->json([
                    'message' => 'Image already exists, URL added to database',
                    'file_path' => $fullFileUrl
                ]);
            }

            // Get media URL from WhatsApp API
            try {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => "https://graph.facebook.com/v18.0/{$mediaId}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer ' . $accessToken,
                        'Accept: application/json',
                    ],
                    // Proxy settings if needed
                    // CURLOPT_PROXY => 'your-proxy-if-needed',
                    // CURLOPT_PROXYPORT => 'proxy-port',
                    
                    // Debug options
                    CURLOPT_VERBOSE => true,
                    CURLOPT_STDERR => fopen(storage_path('logs/curl.log'), 'a+'),
                    
                    // SSL Options
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    
                    // Connection options
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TCP_KEEPALIVE => 1,
                    CURLOPT_TCP_KEEPIDLE => 120,
                    CURLOPT_TCP_NODELAY => 1,
                ]);

                \Log::info('Attempting direct CURL request to Graph API');
                $response = curl_exec($ch);
                $error = curl_error($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);

                \Log::info('CURL Response Info', [
                    'http_code' => $info['http_code'],
                    'total_time' => $info['total_time'],
                    'error' => $error,
                    'response' => $response
                ]);

                if ($error) {
                    throw new \Exception("CURL Error: " . $error);
                }

                $responseData = json_decode($response, true);
                if (!$responseData || !isset($responseData['url'])) {
                    throw new \Exception("Invalid response from Graph API: " . $response);
                }

                $mediaUrl = $responseData['url'];

                // Download image using same CURL settings
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $mediaUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer ' . $accessToken
                    ]
                ]);

                $imageContent = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);

                if ($error) {
                    throw new \Exception("Error downloading image: " . $error);
                }

                // Save the image
                Storage::put($filePath, $imageContent);

                // Append new image path to existing uploads column
                $findCar->photo_links = $findCar->photo_links 
                    ? $findCar->photo_links . ',' . $fullFileUrl 
                    : $fullFileUrl;
                $findCar->save();

                return response()->json([
                    'message' => 'Image downloaded successfully',
                    'file_path' => $fullFileUrl
                ]);
            } catch (\Exception $e) {
                \Log::error('Detailed error info', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'curl_log' => file_get_contents(storage_path('logs/curl.log'))
                ]);

                return response()->json([
                    'error' => 'Connection Error',
                    'message' => $e->getMessage(),
                    'debug_info' => [
                        'curl_log' => file_get_contents(storage_path('logs/curl.log'))
                    ]
                ], 500);
            }
        } catch (\Exception $e) {

            $maxExecutionTime = ini_get('max_execution_time');

            return response()->json(['error' => 'Error downloading image: ' . $e->getMessage(), 'max_execution_time' => $maxExecutionTime], 500);
        }
    }

    // Let's add a method to check HTTP client dependencies
    public function checkDependencies()
    {
        $dependencies = [
            'guzzlehttp/guzzle' => class_exists('\GuzzleHttp\Client'),
            'curl' => function_exists('curl_version'),
            'openssl' => extension_loaded('openssl'),
            'json' => function_exists('json_encode'),
        ];

        \Log::info('Dependency Check', [
            'dependencies' => $dependencies,
            'guzzle_version' => defined('\GuzzleHttp\Client::VERSION') ? \GuzzleHttp\Client::VERSION : 'unknown',
            'curl_version' => curl_version(),
            'php_version' => PHP_VERSION
        ]);

        return response()->json([
            'dependencies' => $dependencies,
            'php_info' => [
                'max_execution_time' => ini_get('max_execution_time'),
                'memory_limit' => ini_get('memory_limit'),
                'allow_url_fopen' => ini_get('allow_url_fopen'),
            ]
        ]);
    }

    // Add this new method to test connectivity
    public function testConnection()
    {
        try {
            // Test multiple Facebook endpoints with both HTTP and HTTPS
            $facebookEndpoints = [
                'graph-https' => 'https://graph.facebook.com',
                'graph-http' => 'http://graph.facebook.com',
                'graph-ip-https' => 'https://157.240.249.13',
                'graph-ip-http' => 'http://157.240.249.13'
            ];

            $tests = [
                'dns_resolution' => [
                    'status' => checkdnsrr('graph.facebook.com', 'A'),
                    'ip' => gethostbyname('graph.facebook.com'),
                    'all_ips' => gethostbynamel('graph.facebook.com')
                ],
                'curl_info' => curl_version(),
                'server_info' => [
                    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
                    'php_version' => PHP_VERSION,
                    'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown',
                    'ssl_support' => extension_loaded('openssl'),
                    'local_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                    'is_localhost' => in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']),
                    'environment' => app()->environment()
                ],
                'connection_test' => [
                    'google-https' => $this->testUrl('https://google.com'),
                    'google-http' => $this->testUrl('http://google.com')
                ]
            ];

            // Test each Facebook endpoint
            foreach ($facebookEndpoints as $name => $url) {
                $tests['connection_test'][$name] = $this->testUrl($url);
            }

            // Add SSL/TLS information
            $tests['ssl_info'] = [
                'openssl_version' => OPENSSL_VERSION_TEXT,
                'curl_ssl_version' => curl_version()['ssl_version'],
                'verify_peer' => ini_get('curl.cainfo'),
                'stream_ssl_verify_peer' => ini_get('openssl.cafile')
            ];

            return response()->json($tests);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    private function testUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            // Remove CURLOPT_INTERFACE as it might cause issues on localhost
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_DNS_USE_GLOBAL_CACHE => false,
            CURLOPT_VERBOSE => true,
            // Add these for better local testing
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_ENCODING => '',
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
        ]);

        // Create a temporary file for CURL verbose output
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);

        // Get verbose information
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        fclose($verbose);

        curl_close($ch);

        return [
            'accessible' => !empty($response),
            'http_code' => $info['http_code'],
            'total_time' => $info['total_time'],
            'error' => $error,
            'connect_time' => $info['connect_time'],
            'name_lookup_time' => $info['namelookup_time'],
            'verbose_log' => $verboseLog
        ];
    }
}
