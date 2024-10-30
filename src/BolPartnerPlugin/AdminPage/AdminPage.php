<?php
namespace BolPartnerPlugin\AdminPage;

/**
 * Interface describing the methods of a Bol.com partner plugin admin page
 *
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
interface AdminPage
{
    public function init();

    public function setParams(array $params);

    public function process();

    public function display();
}
