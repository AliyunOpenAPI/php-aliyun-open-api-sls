<?php

use Aliyun\SLS\Requests\RequestCore;


/**
 * @author Adi Putra <adiputrapermana@gmail.com>
 */
class RequestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \Aliyun\SLS\Exception
     */
    public function testFailedRequest()
    {
        $request = new RequestCore('https://unreachable-api.com');
        $request->set_curlopts([CURLOPT_TIMEOUT => 1]);

        $this->setExpectedException(\Aliyun\SLS\Exception::class);
        $request->send_request();
    }
}
