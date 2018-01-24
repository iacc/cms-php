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
    private $client = null;
    private $query = [];

    /**
     * Set up HttpClient for request
     * @param string $cmsHost CMS host
     * @param string $cmsSite CMS Site ID (PPS etc.)
     * @param string $authKey CMS Authentication key
     */
    public function __construct($cmsHost, $cmsSite, $authKey)
    {
        $this->query['auth_key'] = $authKey;
        $this->client = new HttpClient(['base_uri' => "http://{$cmsHost}/api/sites/{$cmsSite}/"]);
    }

    // list_articles
    public function getArticles($query)
    {
        if (!isset($query['page']) || !isset($query['per']))
            throw new ClientException("CMS Client: [page] and [per] are required for query");

        return $this->getContent("articles", $query);
    }

    // find_article
    public function getArticleById($id, $query = [])
    {
        return $this->getContent("articles/{$id}", $query);
    }

    // list_categories
    public function getCategory($path)
    {
        return $this->getContent("categories/{$path}");
    }
    
    public function getSubCategory($query = [])
    {
        return $this->getContent("categories", $query);
    }

    // 指定された path の子孫にあたるカテゴリを返す
    // list_category_dancestors
    public function getCategoryContent($path, $query = [])
    {
        return $this->getContent("categories/{$path}/dancestors", $query);
    }

    // find_category_module
    public function getArticlesByModule($name)
    {
        return $this->getContent("article_modules/{$name}");
    }

    private function getContent($path, $query = [])
    {
        try
        {
          $res = $this->client->request('GET', $path, [
              'query' => array_merge($this->query, $query)
          ]);
        }
        catch (ServerException $e)
        {
            throw new ClientException("CMS Server: Internal server error");
        }

        $code = $res->getStatusCode();

        if ($code == 200)
        {
            $content = json_decode($res->getBody(), true);

            if (isset($content['result']) && $content['result'] == false)
                throw new ClientException("CMS Server: {$content['message']}");

            return $content;
        }
        elseif ($code == 403)
        {
            throw new ClientException("CMS Server: URL is forbidden");
        }
        elseif ($code == 404)
        {
            throw new ClientException("CMS Server: URL is not found");
        }
        else
        {
            throw new ClientException("CMS Server: Returned status code - {$code}");
        }
    }
}
