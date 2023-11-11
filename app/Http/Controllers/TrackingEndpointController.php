<?php

namespace App\Http\Controllers;

use DOMXPath;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DOMDocument;

class TrackingEndpointController extends Controller
{
    public function index() {
        // Render a list of a resource.

        return view('tracking-endpoints');
    }
    
    public function requestToTarget() {
        $target = request('target-url');

        // Create a new Guzzle Client
        $client = new Client();

        $response = $client->get($target);
        $htmlContent = $response->getBody()->getContents();

        $endpoints = $this->urlFromDOM($htmlContent);

        return view('tracking-endpoints', compact('endpoints', 'target'));
    }

    public function validateRequest() {
        return request()->validate( [
            'target-url' => 'required'
        ] );
    }

    public function urlFromDOM($htmlContent) {
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

        // Query for form tags and their action attributes
        foreach ($xpath->query("//form[@action]") as $formElement) {
            $endpoint = urldecode($formElement->getAttribute('action'));

            // Add to $endpoints array only if $endpoint is not empty
            if ($endpoint !== '') {
                $endpoints[] = [
                    'endpoint' => $endpoint,
                    'tag' => 'form',
                    'attribute' => 'action',
                ];
            }
        }

        foreach ($tags as $tag) {
            foreach ($xpath->query("//{$tag}[@href or @src or @action]") as $element) {
                $endpoint = urldecode($element->getAttribute('href') ?? $element->getAttribute('src') ?? $element->getAttribute('action'));

                // Add to $endpoints array only if $endpoint is not empty, get tag and attribute from $endpoint
                if ($endpoint !== '') {
                    $endpoints[] = [
                        'endpoint' => $endpoint,
                        'tag' => $tag,
                        'attribute' => $element->hasAttribute('href') ? 'href' : ($element->hasAttribute('src') ? 'src' : 'action'),
                    ];
                }

            }
        }

        return $endpoints;
    }
}
