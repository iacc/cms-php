<?php

namespace Cms;

use Cms\Exceptions\ClientException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ServerException;

/**
 * Class use HTTP Requests for get HTML content from iacc Cms server
 * 
 * @category Client
 * @package  Cms\Client
 * @author   Zazimko Alexey <notfoundsam@gmail.com>
 * @link     https://github.com/iacc/cms-php
 *
 */
class Client
{
    private $client   = null;
    private $query    = [];

    /**
     * [__construct description]
     * @param [type] $cmsHost [description]
     * @param [type] $cmsSite  [description]
     * @param [type] $authKey    [description]
     */
    public function __construct($cmsHost, $cmsSite, $authKey)
    {
        $this->query['auth_key'] = $authKey;
        $this->client = new HttpClient(['base_uri' => "http://{$cmsHost}/api/sites/{$cmsSite}/"]);
    }

    public function getListOfArticlesByCategory()
    {
        // $client = new GuzzleHttp\HttpClient();
    }

    public function getCategory($path)
    {
        return $this->getContent("categories/{$path}");
    }

    private function getContent($path, $query = array())
    {
        try
        {
          $res = $this->client->request('GET', $path, [
              'query' => array_merge($this->query, $query)
          ]);
        }
        catch (ServerException $e)
        {
            throw new ClientException("CMS: Internal server error");
        }

        $code = $res->getStatusCode();

        if ($code == 200)
        {
            $content = json_decode($res->getBody(), true);

            if (isset($content['result']) && $content['result'] == false)
                throw new ClientException("CMS: {$content['message']}");

            return $content;
        }
        elseif ($code == 403)
        {
            throw new ClientException("CMS: URL is forbidden");
        }
        elseif ($code == 404)
        {
            throw new ClientException("CMS: URL is not found");
        }
        else
        {
            throw new ClientException("CMS: Returned status code - {$code}");
        }
    }
}
