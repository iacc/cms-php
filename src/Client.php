<?php

namespace Cms;

use Cms\Exceptions\BrowserException;
use GuzzleHttp\Client;

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
    public function __construct($authKey, $siteId, $host)
    {
        $this->authKey     = $authKey;
        $this->siteId      = $siteId;
        $this->host        = $host;
    }
}
