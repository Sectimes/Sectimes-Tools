<?php

namespace App\Jobs;

// use App\Http\Controllers\TrackingEndpointController;
use App\Models\TrackingEndpointResults;
use App\Models\TrackingEndpointTarget;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use DOMXPath;
use Exception;
use DOMDocument;

class ProcessRequestToTarget implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $target;
    public $cookies;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($target, $cookies)
    {
        $this->target = $target;
        $this->cookies = $cookies;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (isset($this->cookies)) {
            $result = $this->followRedirectsWithCookies($this->target, $this->cookies);
            $htmlContent = $result['response'];
        } else {
            // Create a new Guzzle Client
            $client = new Client([
                'verify' => false
            ]);

            $response = $client->get($this->target);
            $htmlContent = $response->getBody()->getContents();
        }
        
        $endpoints = $this->urlFromDOM($this->target, $htmlContent);

        // Add results into tracking_endpoint_results table
        foreach ($endpoints as $endpoint) {
            TrackingEndpointResults::updateOrCreate([
                'endpoint' => $endpoint['endpoint'],
                'status' => $endpoint['status'],
                'tag' => $endpoint['tag'],
                'attribute' => $endpoint['attribute'],
                'target' => $this->target
            ]);
        }
    }

    public function urlFromDOM($target, $htmlContent) {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent); // using @ in other to suppress the warnings, as `loadHTML()` may generate warnings for malformed HTML

        $xpath = new DOMXPath($dom);

        $tags = [
            'a', 'abbr', 'address', 'area', 'article', 'aside', 'audio', 'b', 'base', 'bdi', 'bdo', 'blockquote', 'body', 'br', 'button',
            'canvas', 'caption', 'cite', 'code', 'col', 'colgroup', 'command', 'datalist', 'dd', 'del', 'details', 'dfn', 'div', 'dl', 'dt',
            'em', 'embed', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hgroup',
            'hr', 'html', 'i', 'iframe', 'img', 'input', 'ins', 'kbd', 'label', 'legend', 'li', 'link', 'main', 'map', 'mark', 'meta', 'meter',
            'nav', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'picture', 'pre', 'progress', 'q', 'rp', 'rt', 'ruby',
            's', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'strong', 'style', 'sub', 'summary', 'sup', 'svg', 'table', 'tbody',
            'td', 'template', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'track', 'u', 'ul', 'var', 'video', 'wbr'
        ];
        $endpoints = [];

        foreach ($tags as $tag) {
            foreach ($xpath->query("//{$tag}[@href]") as $element) {
                $endpoint = urldecode($element->getAttribute('href'));

                // Add to $endpoints array only if $endpoint is not empty, get tag and attribute from $endpoint
                if ($endpoint !== '') {
                    $endpoints[] = [
                        'endpoint' => $endpoint,
                        'tag' => $tag,
                        'attribute' => 'href',
                        'status' => $this->checkStatusCode($target, $endpoint)
                    ];
                }

            }
        }

        foreach ($tags as $tag) {
            foreach ($xpath->query("//{$tag}[@src]") as $element) {
                $endpoint = urldecode($element->getAttribute('src'));

                // Add to $endpoints array only if $endpoint is not empty, get tag and attribute from $endpoint
                if ($endpoint !== '') {
                    $endpoints[] = [
                        'endpoint' => $endpoint,
                        'tag' => $tag,
                        'attribute' => 'src',
                        'status' => $this->checkStatusCode($target, $endpoint)
                    ];
                }

            }
        }

        foreach ($tags as $tag) {
            foreach ($xpath->query("//{$tag}[@action]") as $element) {
                $endpoint = urldecode($element->getAttribute('action'));

                // Add to $endpoints array only if $endpoint is not empty, get tag and attribute from $endpoint
                if ($endpoint !== '') {
                    $endpoints[] = [
                        'endpoint' => $endpoint,
                        'tag' => $tag,
                        'attribute' => 'action',
                        'status' => $this->checkStatusCode($target, $endpoint)
                    ];
                }

            }
        }

        return $endpoints;
    }

    public function checkStatusCode($target, $endpoint) {
        // Check status code of each URI / URL / Endpoints we got
    
        $client = new Client();
        try {
            if (strpos($endpoint, 'https') === 0 or strpos($endpoint, 'http') === 0) {
                $response = $client->get($endpoint);
                $statusCode = $response->getStatusCode();
            } else {
                $response = $client->get($target . "/" . $endpoint);
                $statusCode = $response->getStatusCode();
            }
        } catch (Exception $e) {
            $statusCode = "No connection";
        }
        
        return $statusCode;
    }

    public function followRedirectsWithCookies($url, $cookies) {
        $ch = curl_init($url);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow all redirections
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_COOKIE, $cookies); // Set the cookies
    
        $response = curl_exec($ch);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    
        curl_close($ch);
    
        return [
            'url' => $finalUrl,
            'response' => $response,
        ];
    }
}
