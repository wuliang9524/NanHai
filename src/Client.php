<?php

namespace Logan\Nanhai;

use GuzzleHttp\Client as HttpClient;
use Logan\Nanhai\exceptions\InitRuntimeException;

class Client
{
    /**
     * 接口域名(带端口号)
     *
     * @var string
     */
    protected $domain;

    /**
     * 请求接口使用的 appId
     *
     * @var string
     */
    protected $appId;

    /**
     * 请求接口使用的 appSecret
     *
     * @var string
     */
    protected $appSecret;

    /**
     * GuzzleHttp 实例
     *
     * @var GuzzleHttp\Client
     */
    protected $httpClient = null;

    /**
     * 接口返回值
     *
     * @var string
     */
    protected $response;

    /**
     * 构造方法
     *
     * @param string $domain    接口地址
     * @param string $appId     appid
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function __construct(string $domain, string $appId, string $appSecret)
    {
        $domain = rtrim($domain, '/');

        if (empty($appId)) {
            throw new InitRuntimeException("appId is not null", 0);
        }

        if (empty($appSecret)) {
            throw new InitRuntimeException("appSecret is not null", 0);
        }

        $this->domain     = $domain;
        $this->appId      = $appId;
        $this->appSecret  = $appSecret;
        $this->httpClient = new HttpClient();
    }

    /**
     * 设置请求参数
     *
     * @param array $params 各接口请求的独自参数
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    private function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * 获取请求参数
     *
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 获取实名制 Token
     *
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-07-18
     */
    public function getAppToken()
    {
        $url = $this->domain . '/Api/MachineThird/getTokenByAppRequest';

        $req = [
            'appId'     => $this->appId,
            'appSecret' => $this->appSecret
        ];

        $this->response = $this->httpClient->request('POST', $url, [
            'json' => $req
        ])
            ->getBody()
            ->getContents();
        return $this;
    }

    /**
     * 获取项目散列值
     *
     * @param string $token         appToken
     * @param string $code          项目唯一码
     * @param string|null $comCode  施工单位唯一码,
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-03-15
     */
    public function getProjectHash(string $token, string $code, ?string $comCode)
    {
        $url = $this->domain . '/Api/MachineThird/getProjectHash';

        $req = [
            'appId'           => $this->appId,
            'appToken'        => $token,
            'onlycode'        => $code,
            'companyonlycode' => $comCode,
        ];

        $this->response = $this->httpClient->request('POST', $url, [
            'json' => $req
        ])
            ->getBody()
            ->getContents();
        return $this;
    }

    /**
     * 获取项目人员列表
     *
     * @param string $token         appToken
     * @param string $code          项目唯一码
     * @param string|null $comCode  施工单位唯一码,不填默认所有施工单位
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-03-15
     */
    public function getProjectWorkers(string $token, string $code, ?string $comCode)
    {
        $url = $this->domain . '/Api/MachineThird/getProjectPeople';

        $req = [
            'appId'           => $this->appId,
            'appToken'        => $token,
            'onlycode'        => $code,
            'companyonlycode' => $comCode,
        ];

        $this->response = $this->httpClient->request('POST', $url, [
            'json' => $req
        ])
            ->getBody()
            ->getContents();
        return $this;
    }

    /**
     * 获取项目人员详细信息
     *
     * @param string $token
     * @param string $proCode
     * @param string $code
     * @param string|null $comCode
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-03-15
     */
    public function getProjectWorkerInfo(string $token, string $proCode, string $code, ?string $comCode)
    {
        $url = $this->domain . '/Api/MachineThird/getPersonInfo';

        $req = [
            'appId'           => $this->appId,
            'appToken'        => $token,
            'onlycode'        => $proCode,
            'personcode'      => $code,
            'companyonlycode' => $comCode,
        ];

        $this->response = $this->httpClient->request('POST', $url, [
            'json' => $req
        ])
            ->getBody()
            ->getContents();
        return $this;
    }

    /**
     * 上传考勤数据
     *
     * @param string $token
     * @param string $proCode
     * @param string $code
     * @param string $devCode
     * @param string $direction
     * @param string $timeStamp
     * @param string $image
     * @param int|null $delay
     * @param string|null $position
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-07-18
     */
    public function addAttendance(
        string $token,
        string $proCode,
        string $code,
        string $devCode,
        string $direction,
        string $timeStamp,
        string $image,
        ?int $delay,
        ?string $position
    ) {
        $url = $this->domain . '/Api/MachineThird/record';

        $req = [
            'appId'      => $this->appId,
            'appToken'   => $token,
            'onlycode'   => $proCode,
            'personcode' => $code,
            'machine'    => $devCode,
            'state'      => $direction,
            'time'       => $timeStamp,
            'images'     => $image,
            'delay'      => $delay ?? 0,
            'position'   => $position,
        ];

        $this->response = $this->httpClient->request('POST', $url, [
            'json' => $req
        ])
            ->getBody()
            ->getContents();
        return $this;
    }

    /**
     * 添加考勤设备
     *
     * @param string $token         appToken
     * @param string $code          项目唯一码
     * @param string $devCode       考勤机编码
     * @param string $state         进出场状态 in 进场 out 出场
     * @param string|null $comCode  施工单位唯一码
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-03-16
     */
    public function addDevice(
        string $token,
        string $code,
        string $devCode,
        string $state,
        string $comCode
    ) {
        $url = $this->domain . '/Api/MachineThird/addMachine';

        $req = [
            'appId'           => $this->appId,
            'appToken'        => $token,
            'onlycode'        => $code,
            'machine'         => $devCode,
            'state'           => $state,
            'companyonlycode' => $comCode,
        ];

        $this->response = $this->httpClient->request('POST', $url, [
            'json' => $req
        ])
            ->getBody()
            ->getContents();
        return $this;
    }

    /**
     * 获取扬尘 Token
     *
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-07-18
     */
    public function getDustAppToken()
    {
        $url = $this->domain . '/Api/YangchenThird/getTokenByAppRequest';

        $req = [
            'appId'     => $this->appId,
            'appSecret' => $this->appSecret
        ];

        $this->response = $this->httpClient->request('POST', $url, [
            'json' => $req
        ])
            ->getBody()
            ->getContents();
        return $this;
    }

    /**
     * 上传扬尘记录
     *
     * @param string $token     token
     * @param string $proCode   项目唯一码
     * @param string $devCode   扬尘设备编码
     * @param array $data       扬尘数据
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-07-18
     */
    public function addDustRecord(
        string $token,
        string $proCode,
        string $devCode,
        array $data
    ) {
        $url = $this->domain . '/Api/YangchenThird/receiveYCData';

        $req = [
            'appId'        => $this->appId,
            'appToken'     => $token,
            'onlycode'     => $proCode,
            'machine_sn'   => $devCode,
            'yangchenData' => $data
        ];

        $this->response = $this->httpClient->request('POST', $url, [
            'json' => $req
        ])
            ->getBody()
            ->getContents();
        return $this;
    }

    /**
     * 获取接口返回值
     *
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-03-15
     */
    public function getResponse()
    {
        if (!$this->response) {
            return null;
        }
        $response = trim($this->response, "\xEF\xBB\xBF"); // UTF-8 BOM
        return json_decode($response, true);
    }
}
