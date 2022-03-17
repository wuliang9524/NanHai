<?php

declare(strict_types=1);

use Logan\Nanhai\Client;
use PHPUnit\Framework\TestCase;

class ProTest extends TestCase
{
    protected $domain    = 'http://zjapi.nanhai.gov.cn/';
    protected $appId     = 'gdzh14418888r5pW';
    protected $appSecret = 'ggA22ZK1L3Nk5NteDxgTIbYB7sdPUjUX';
    protected $appToken  = 'MCpMyMQIg3JXGfcZ';

    public function testGetAppToken()
    {
        $instance = new Client($this->domain, $this->appId, $this->appSecret);
        $res = $instance->getAppToken('431127202101280101')->getResponse();
        var_dump($res);
    }

    public function testGetProjectHash()
    {
        $instance = new Client($this->domain, $this->appId, $this->appSecret);
        $res = $instance->getProjectHash($this->appToken, '2b94-c63a-af67c7ae-9f72-4a01d11f1e21', null)->getResponse();
        var_dump($res);
    }
}
