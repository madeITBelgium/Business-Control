<?php

namespace MadeITBelgium\BusinessControl;

use GuzzleHttp\Client;

/**
 * Business Control PHP SDK
 *
 * @version    0.0.1
 *
 * @copyright  Copyright (c) 2018 Made I.T. (http://www.madeit.be)
 * @author     Made I.T. <info@madeit.be>
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-3.txt    LGPL
 */
class BusinessControl
{
    protected $version = '0.1.0';
    private $server = 'https://businesscontrol.be';
    private $apiKey = "";
    private $apiSecret = "";
    private $accessToken = "";
    private $refreshToken = "";
    private $scope = "*";
    private $expiresAt = null;
    private $redirectUri = "";

    private $client;

    /**
     * Construct Business Control.
     *
     * @param $server
     * @param $hash
     * @param $client
     */
    public function __construct($apiKey = null, $apiSecret = null, $accessToken = null, $client = null)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->accessToken = $accessToken;
        $this->setAllScopes();

        if ($client == null) {
            $this->client = new Client([
                'base_uri' => $this->server,
                'timeout'  => 5.0,
                'headers'  => [
                    'User-Agent' => 'Made I.T. Business Control SDK V'.$this->version,
                    'Accept'     => 'application/json',
                ],
                'verify' => true,
            ]);
        }
        else {
            $this->client = $client;
        }
    }
    
    public function setAllScopes()
    {
        $scopes = [
            'user',
            'user-detail',
            'user-update',
            'team',
            'dashboard',
            'company',
            'client',
            'offer',
            'product',
            'invoice',
            'workflow',
            'workflow-item',
            'notification',
            'invoice-type',
            'invoice-category',
            'supplier',
            'incomming-invoice',
            'support-source',
            'support-source-email',
            'support-source-chat',
            'support-session',
            'todo',
            'attachment',
            'settings',
            'public-vat-search',
            'company-payment',
        ];
        $this->scope = implode(" ", $scopes);
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }
    
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }
    
    public function setApiSecret($apiSecret) {
        $this->apiSecret = $apiSecret;
    }
    
    public function setRedirectUri($redirectUri) {
        $this->redirectUri = $redirectUri;
    }

    /**
     * Execute API call.
     *
     * @param $endpoint
     * @param $returnCode
     * @param $parameters
     */
    public function call($method, $uri, $data = null, $rawResult = false)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->accessToken,
        ];
        
        $options = ['headers' => $headers];
        
        if($data !== null)
        {
            $options['form_params'] = $data;
        }
        
        $response = $this->client->request($method, $uri, $options);
        
        if ($response->getStatusCode() == 200) {
            $body = (string) $response->getBody();
        } else {
            throw \Exception($response->getStatusCode());
        }
        
        if($rawResult) {
            return $body;
        }
        return json_decode($body);
    }
    
    public function get($uri, $rawResult = false)
    {
        return $this->call('GET', $uri, null, $rawResult);
    }
    
    public function post($uri, $data = [], $rawResult = false)
    {
        return $this->call('POST', $uri, $data, $rawResult);
    }
    
    public function put($uri, $data = [], $rawResult = false)
    {
        return $this->call('PUT', $uri, $data, $rawResult);
    }
    
    public function delete($uri, $rawResult = false)
    {
        return $this->call('DELETE', $uri, null, $rawResult);
    }
    
    public function getAuthorizationUrl()
    {
        $query = [
            'client_id'     => $this->apiKey,
            'response_type' => 'code',
            'redirect_uri'  => $this->redirectUri,
            'scope' => $this->scope,
        ];

        $url = $this->server.'/oauth/authorize?'.http_build_query($query);
        return $url;
    }

    public function requestAccessToken($code)
    {
        $result = $this->post('/oauth/token', [
            'code'          => $code,
            'client_id'     => $this->apiKey,
            'client_secret' => $this->apiSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
        ]);
        dd($result);
        $this->accessToken = $result->access_token;
        $this->expiresAt = Carbon::now()->addSeconds($result->expires_in);
        $this->refreshToken = $result->refresh_token;

        return $result;
    }

    public function regenerateAccessToken()
    {
        $result = $this->post('/oauth/token/refresh', [
            'client_id'     => $this->apiKey,
            'client_secret' => $this->apiSecret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ]);

        $this->accessToken = $result->access_token;
        $this->expiresAt = Carbon::now()->addSeconds($result->expires_in);
        $this->refreshToken = $result->refresh_token;
        return $result;
    }

    public function user()
    {
        return null;
    }

    public function company()
    {
        return null;
    }

    public function team()
    {
        return null;
    }
    
    public function client()
    {
        $client = new Object\Client();
        $client->setBusinessControl($this);
        return $client;
    }
    
    public function product()
    {
        return null;
    }
    
    public function offer()
    {
        $offer = new Object\Offer();
        $offer->setBusinessControl($this);
        return $offer;
    }

    public function invoice()
    {
        $invoice = new Object\Invoice();
        $invoice->setBusinessControl($this);
        return $invoice;
    }
    
    public function incommingInvoice()
    {
        return null;
    }
    
    public function deleteEmptyValues($params)
    {
        $result = [];
        foreach($params as $key => $value) {
            if(!empty($value) || is_array($value)) {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
}