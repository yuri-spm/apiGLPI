<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class GLPIApi extends Client
{
    private $apiUrl;
    private $username;
    private $password;
    private $appToken;
    private $sessionToken;

    /**
     * __construct
     *
     * @param  mixed $apiUrl
     * @param  mixed $username
     * @param  mixed $password
     * @param  mixed $appToken
     * @param  mixed $sessionToken
     * @return void
     */
    public function __construct($apiUrl, $username, $password, $appToken, $sessionToken = '')
    {
        $this->apiUrl = $apiUrl;
        $this->username = $username;
        $this->password = $password;
        $this->appToken = $appToken;
        $this->sessionToken = $sessionToken;
    }

    /**
     * initSession
     *
     * @return void
     */
    public function initSession()
    {
        $client = new Client();

        $headers = [
            'Authorization' => $this->appToken,
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic Z2xwaTouQURNX1MzcnYxYzMu'
        ];

        $body = json_encode([
            'app_token' => $this->appToken,
            'username' => $this->username,
            'password' => $this->password
        ]);

        $uri =  $this->apiUrl . '/initSession';

        try {
            $request = new Request('POST', $uri, $headers, $body);
            $response = $client->sendAsync($request)->wait();
            $this->sessionToken = json_decode($response->getBody());
            return "Conectado com sucesso, seu session token e:" . $this->sessionToken->session_token;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response->getBody()->getContents();
            }
        }
    }

    /**
     * killSession
     *
     * @return void
     */
    public function killSession()
    {
        $client = new Client();

        $headers = [
            'app-token' => $this->appToken,
            'Session-Token' => $this->sessionToken->session_token,
        ];


        $url = $this->apiUrl . '/killSession';

        try {
            $response = $client->request('GET', $url,['headers' => $headers]);
                return "Sessão finalizada ";
            
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response->getBody()->getContents();
            }
        }
    }

    /**
     * requestItem
     *
     * @param  mixed $item
     * @param  mixed $params
     * @return void
     */
    public function requestItem($item, $params = null)
    {
        $client = new Client();

        if (isset($params)) {
            $url = $this->apiUrl . '/' . $item . '/' . $params;
        } else {
            $url = $this->apiUrl . '/' . $item;
        }

        $headers = [
            'Session-Token' => $this->sessionToken->session_token,
            'app-token' => $this->appToken,
            'Authorization' => 'Basic Z2xwaTouQURNX1MzcnYxYzMu'
        ];

        try {
            $response = $client->request('GET', $url, ['headers' => $headers]);
            return $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response->getBody()->getContents();
            }
        }
    }

    /**
     * addItem
     *
     * @param  mixed $item
     * @param  mixed $params
     * @return void
     */
    public function addItem($item, $params = [])
    {
        $client = new Client();

        $url = $this->apiUrl . '/' . $item;

        $headers = [
            'Session-Token' => $this->sessionToken->session_token,
            'app-token'   => $this->appToken,
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic Z2xwaTouQURNX1MzcnYxYzMu'
        ];

        $body = json_encode($params);

        try {
            $response = $client->request('POST', $url, ['headers' => $headers, 'body' => $body]);
            return $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response->getBody()->getContents();
            }
        }
    }

    /**
     * updateItem
     *
     * @param  mixed $item
     * @param  mixed $id
     * @param  mixed $params
     * @return void
     */
    public function updateItem($item, $id, $params)
    {
        $client = new Client();

        $url = $this->apiUrl . '/' . $item  . '/' . $id;

        $headers = [
            'Session-Token' => $this->sessionToken->session_token,
            'app-token'   => $this->appToken,
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic Z2xwaTouQURNX1MzcnYxYzMu'
        ];

        $body = json_encode($params);

        try {
            $response = $client->request('PUT', $url, $headers, $body);
            echo $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response->getBody()->getContents();
            }
        }
    }

    /**
     * deleteItem
     *
     * @param  mixed $item
     * @param  mixed $id
     * @return void
     */
    public function deleteItem($item, $id)
    {
        $client = new Client();

        $url = $this->apiUrl . '/' . $item  . '/' . $id;

        $headers = [
            'Session-Token' => $this->sessionToken->session_token,
            'app-token'   => $this->appToken,
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic Z2xwaTouQURNX1MzcnYxYzMu'
        ];

        try {
            $response = $client->request('DELETE', $url, ['headers' => $headers]);
            echo $response->getBody();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response->getBody()->getContents();
            }
        }
    }


    /**
     * purgeItem
     *
     * @param  mixed $item
     * @param  mixed $id
     * @return void
     */
    public function purgeItem($item, $id)
    {
        $client = new Client();

        $url = $this->apiUrl . '/' . $item  . '/' . $id . '?force_purge=true';

        $headers = [
            'Session-Token' => $this->sessionToken->session_token,
            'app-token'   => $this->appToken,
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic Z2xwaTouQURNX1MzcnYxYzMu'
        ];

        try {
            $response = $client->request('DELETE', $url, ['headers' => $headers]);
            echo $response->getBody();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response->getBody()->getContents();
            }
        }
    }

    // 
    
    public function sendDocuments($item, $filename)
{
    $client = new Client();

    $url = $this->apiUrl . '/' . $item;

    if (!file_exists($filename)) {
        echo "Arquivo não encontrado";
        return;
    }

    $headers = [
        'Authorization' => 'Basic Z2xwaTouQURNX1MzcnYxYzMu',
        'Session-Token' => $this->sessionToken->session_token,
        'app-token' => $this->appToken,
    ];

    $body = [
        'multipart' => [
            [
                'name' => 'uploadManifest',
                'contents' => json_encode([
                    'input' => [
                        'name' => $filename,
                        '_filename' => [$filename]
                    ]
                ])
            ],
            [
                'name' => 'filename',
                'contents' => Utils::tryFopen($filename, 'r'),
                'filename' =>  basename($filename),
            ],
        ],
    ];

    try {
        
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'multipart' => $body['multipart'],
        ]);

        echo $response->getBody();
    } catch (RequestException $e) {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            echo $response->getBody()->getContents();
        }
    }
}
}
