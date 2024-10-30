<?php
/**
 * Factory class to provide the client to use with the BolOpenApi SDK
 *
 *
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BolPartnerPlugin;

use Buzz\Client\Curl as BuzzClientCurl;
use Buzz\Browser as BuzzBrowser;
use BolOpenApi\Client;

/**
 * Created by JetBrains PhpStorm.
 * @Author: Danny Dörfel
 * @Date: 2012-05-31 11:38
 * @Copyright: Netvlies Internetdiensten
 */
class ApiClientFactory
{
    public static function getCreateClient($accessKey, $secretKey)
    {
        $buzzClient = new BuzzClientCurl();
        $buzzClient->setMaxRedirects(0);
        $buzzClient->setTimeout(10);

        $browser = new BuzzBrowser($buzzClient);
        $client = new Client($accessKey, $secretKey, $browser);
        return $client;
    }
}
