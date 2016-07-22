<?php namespace Aliyun\SLS;

use Aliyun\SLS\Log\Log;
use Aliyun\SLS\Log\LogContent;
use Aliyun\SLS\Log\LogGroup;
use Aliyun\SLS\Log\LogGroupList;
use Aliyun\SLS\Models\LogItem;
use Aliyun\SLS\Requests\ApplyConfigToMachineGroupRequest;
use Aliyun\SLS\Requests\BatchGetLogsRequest;
use Aliyun\SLS\Requests\CreateACLRequest;
use Aliyun\SLS\Requests\CreateConfigRequest;
use Aliyun\SLS\Requests\CreateLogStoreRequest;
use Aliyun\SLS\Requests\CreateMachineGroupRequest;
use Aliyun\SLS\Requests\DeleteACLRequest;
use Aliyun\SLS\Requests\DeleteConfigRequest;
use Aliyun\SLS\Requests\DeleteLogStoreRequest;
use Aliyun\SLS\Requests\DeleteMachineGroupRequest;
use Aliyun\SLS\Requests\GetACLRequest;
use Aliyun\SLS\Requests\GetConfigRequest;
use Aliyun\SLS\Requests\GetCursorRequest;
use Aliyun\SLS\Requests\GetHistogramsRequest;
use Aliyun\SLS\Requests\GetLogsRequest;
use Aliyun\SLS\Requests\GetMachineGroupRequest;
use Aliyun\SLS\Requests\GetMachineRequest;
use Aliyun\SLS\Requests\ListACLsRequest;
use Aliyun\SLS\Requests\ListConfigsRequest;
use Aliyun\SLS\Requests\ListLogStoresRequest;
use Aliyun\SLS\Requests\ListMachineGroupsRequest;
use Aliyun\SLS\Requests\ListShardsRequest;
use Aliyun\SLS\Requests\ListTopicsRequest;
use Aliyun\SLS\Requests\PutLogsRequest;
use Aliyun\SLS\Requests\RemoveConfigFromMachineGroupRequest;
use Aliyun\SLS\Requests\RequestCore;
use Aliyun\SLS\Requests\UpdateACLRequest;
use Aliyun\SLS\Requests\UpdateConfigRequest;
use Aliyun\SLS\Requests\UpdateLogStoreRequest;
use Aliyun\SLS\Requests\UpdateMachineGroupRequest;
use Aliyun\SLS\Responses\ApplyConfigToMachineGroupResponse;
use Aliyun\SLS\Responses\BatchGetLogsResponse;
use Aliyun\SLS\Responses\CreateACLResponse;
use Aliyun\SLS\Responses\CreateConfigResponse;
use Aliyun\SLS\Responses\CreateLogStoreResponse;
use Aliyun\SLS\Responses\CreateMachineGroupResponse;
use Aliyun\SLS\Responses\DeleteACLResponse;
use Aliyun\SLS\Responses\DeleteConfigResponse;
use Aliyun\SLS\Responses\DeleteLogStoreResponse;
use Aliyun\SLS\Responses\DeleteMachineGroupResponse;
use Aliyun\SLS\Responses\GetACLResponse;
use Aliyun\SLS\Responses\GetConfigResponse;
use Aliyun\SLS\Responses\GetCursorResponse;
use Aliyun\SLS\Responses\GetHistogramsResponse;
use Aliyun\SLS\Responses\GetLogsResponse;
use Aliyun\SLS\Responses\GetMachineGroupResponse;
use Aliyun\SLS\Responses\GetMachineResponse;
use Aliyun\SLS\Responses\ListACLsResponse;
use Aliyun\SLS\Responses\ListConfigsResponse;
use Aliyun\SLS\Responses\ListLogStoresResponse;
use Aliyun\SLS\Responses\ListMachineGroupsResponse;
use Aliyun\SLS\Responses\ListShardsResponse;
use Aliyun\SLS\Responses\ListTopicsResponse;
use Aliyun\SLS\Responses\PutLogsResponse;
use Aliyun\SLS\Responses\RemoveConfigFromMachineGroupResponse;
use Aliyun\SLS\Responses\UpdateACLResponse;
use Aliyun\SLS\Responses\UpdateConfigResponse;
use Aliyun\SLS\Responses\UpdateLogStoreResponse;
use Aliyun\SLS\Responses\UpdateMachineGroupResponse;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * Client class is the main class in the SDK. It can be used to
 * communicate with LOG server to put/get data.
 *
 * @author log_dev
 */
class Client
{

    /**
     * @var string aliyun accessKey
     */
    protected $accessKey;

    /**
     * @var string aliyun accessKeyId
     */
    protected $accessKeyId;

    /**
     * @var string aliyun sts token
     */
    protected $stsToken;

    /**
     * @var string LOG endpoint
     */
    protected $endpoint;

    /**
     * @var string Check if the host if row ip.
     */
    protected $isRowIp;

    /**
     * @var integer Http send port. The dafault value is 80.
     */
    protected $port;

    /**
     * @var string log sever host.
     */
    protected $logHost;

    /**
     * @var string the local machine ip address.
     */
    protected $source;


    /**
     * Client constructor
     *
     * @param string $endpoint    LOG host name, for example, http://cn-hangzhou.sls.aliyuncs.com
     * @param string $accessKeyId aliyun accessKeyId
     * @param string $accessKey   aliyun accessKey
     */
    public function __construct($endpoint, $accessKeyId, $accessKey, $token = "")
    {
        $this->setEndpoint($endpoint); // set $this->logHost
        $this->accessKeyId = $accessKeyId;
        $this->accessKey   = $accessKey;
        $this->stsToken    = $token;
        $this->source      = Util::getLocalIp();
    }


    private function setEndpoint($endpoint)
    {
        $pos = strpos($endpoint, "://");
        if ($pos !== false) { // be careful, !==
            $pos += 3;
            $endpoint = substr($endpoint, $pos);
        }
        $pos = strpos($endpoint, "/");
        if ($pos !== false) // be careful, !==
        {
            $endpoint = substr($endpoint, 0, $pos);
        }
        $pos = strpos($endpoint, ':');
        if ($pos !== false) { // be careful, !==
            $this->port = ( int ) substr($endpoint, $pos + 1);
            $endpoint   = substr($endpoint, 0, $pos);
        } else {
            $this->port = 80;
        }
        $this->isRowIp  = Util::isIp($endpoint);
        $this->logHost  = $endpoint;
        $this->endpoint = $endpoint . ':' . ( string ) $this->port;
    }


    /**
     * Put logs to Log Service.
     * Unsuccessful operation will cause an Exception.
     *
     * @param PutLogsRequest $request the PutLogs request parameters class
     *
     * @throws Exception
     * @return PutLogsResponse
     */
    public function putLogs(PutLogsRequest $request)
    {
        if (count($request->getLogItems()) > 4096) {
            throw new Exception("[InvalidLogSize] logItem's length exceeds maximum limitation: 4096 lines.");
        }

        $logGroup = new LogGroup ();
        $logGroup->setTopic($request->getTopic() ?: '');
        $source = $request->getSource();

        if ( ! $source) {
            $source = $this->source;
        }

        $logGroup->setSource($source);

        $logItems = $request->getLogItems();

        /**
         * @var LogItem $logItem
         */
        foreach ($logItems as $logItem) {

            $log = new Log();
            $log->setTime($logItem->getTime());
            $content = $logItem->getData();

            foreach ($content as $key => $value) {
                $content = new LogContent();
                $content->setKey($key);
                $content->setValue($value);
                $log->addContent($content);
            }

            $logGroup->addLogs($log);
        }

        $body = Util::toBytes($logGroup);

        unset ( $logGroup );

        $bodySize = strlen($body);

        if ($bodySize > 3 * 1024 * 1024) {
            throw new Exception("[InvalidLogSize] logItem's size exceeds maximum limitation: 3 MB.");
        }

        $params                         = array();
        $headers                        = array();
        $headers ["x-log-bodyrawsize"]  = $bodySize;
        $headers ['x-log-compresstype'] = 'deflate';
        $headers ['Content-Type']       = 'application/x-protobuf';
        $body                           = gzcompress($body, 6);

        $logStore = $request->getLogStore() ?: '';
        $project  = $request->getProject() ?: '';

        $resource = "/logstores/" . $logStore;

        list ( $resp, $header ) = $this->send("POST", $project, $body, $resource, $params, $headers);

        $requestId = isset ( $header['x-log-requestid'] ) ? $header['x-log-requestid'] : '';

        $this->parseToJson($resp, $requestId);

        return new PutLogsResponse($header);
    }


    /**
     * @return array
     * @throws Exception
     */
    private function send($method, $project, $body, $resource, $params, $headers)
    {
        if ($body) {
            $headers ['Content-Length'] = strlen($body);
            if (isset( $headers ["x-log-bodyrawsize"] ) == false) {
                $headers ["x-log-bodyrawsize"] = 0;
            }
            $headers ['Content-MD5'] = Util::calMD5($body);
        } else {
            $headers ['Content-Length']    = 0;
            $headers ["x-log-bodyrawsize"] = 0;
            $headers ['Content-Type']      = ''; // If not set, http request will add automatically.
        }

        $headers ['x-log-apiversion']      = '0.6.0';
        $headers ['x-log-signaturemethod'] = 'hmac-sha1';
        if (strlen($this->stsToken) > 0) {
            $headers ['x-acs-security-token'] = $this->stsToken;
        }
        if (is_null($project)) {
            $headers ['Host'] = $this->logHost;
        } else {
            $headers ['Host'] = "$project.$this->logHost";
        }
        $headers ['Date']          = $this->getGMT();
        $signature                 = Util::getRequestAuthorization($method, $resource, $this->accessKey,
            $this->stsToken, $params, $headers);
        $headers ['Authorization'] = "LOG $this->accessKeyId:$signature";

        $url = $resource;
        if ($params) {
            $url .= '?' . Util::urlEncode($params);
        }

        if ($this->isRowIp) {
            $url = "http://$this->endpoint$url";
        } else {
            if (is_null($project)) {
                $url = "http://$this->endpoint$url";
            } else {
                $url = "http://$project.$this->endpoint$url";
            }
        }

        return $this->sendRequest($method, $url, $body, $headers);
    }


    /**
     * GMT format time string.
     *
     * @return string
     */
    protected function getGMT()
    {
        return gmdate('D, d M Y H:i:s') . ' GMT';
    }


    /**
     * @return array
     * @throws Exception
     */
    private function sendRequest($method, $url, $body, $headers)
    {
        try {
            list ( $responseCode, $header, $resBody ) = $this->getHttpResponse($method, $url, $body, $headers);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() . $ex->__toString());
        }

        $requestId = isset ( $header['x-log-requestid'] ) ? $header['x-log-requestid'] : '';

        if ($responseCode == 200) {
            return array( $resBody, $header );
        } else {
            $exJson = $this->parseToJson($resBody, $requestId);
            if (isset( $exJson['error_code'] ) && isset( $exJson['error_message'] )) {
                throw new Exception($exJson['error_message'], $exJson['error_code'], $requestId);
            } else {
                if ($exJson) {
                    $exJson = ' The return json is ' . json_encode($exJson);
                } else {
                    $exJson = '';
                }
                throw new Exception("[RequestError] Request is failed. Http code is $responseCode.$exJson", 0,
                    $requestId);
            }
        }
    }


    /**
     * @return array
     */
    protected function getHttpResponse($method, $url, $body, $headers)
    {
        $request = new RequestCore($url);

        foreach ($headers as $key => $value) {
            $request->add_header($key, $value);
        }

        $request->set_method($method);
        $request->set_useragent('log-php-sdk-v-0.6.0');

        if ($method == "POST" || $method == "PUT") {
            $request->set_body($body);
        }

        $request->send_request();
        $response    = array();
        $response [] = ( int ) $request->get_response_code();
        $response [] = $request->get_response_header();
        $response [] = $request->get_response_body();

        return $response;
    }


    /**
     * Decodes a JSON string to a JSON Object.
     * Unsuccessful decode will cause an Exception.
     *
     * @return string
     * @throws Exception
     */
    protected function parseToJson($resBody, $requestId)
    {
        if ( ! $resBody) {
            return null;
        }

        $result = json_decode($resBody, true);

        if (is_null($result)) {
            throw new Exception ("[BadResponse] Bad format,not json: $resBody", 0, $requestId);
        }

        return $result;
    }


    /**
     * create logStore
     * Unsuccessful operation will cause an Exception.
     *
     * @param CreateLogStoreRequest $request the CreateLogStore request parameters class.
     *
     * @return CreateLogStoreResponse
     * @throws Exception
     * return CreateLogStoreResponse
     */
    public function createLogStore(CreateLogStoreRequest $request)
    {
        $headers                      = array();
        $params                       = array();
        $resource                     = '/logstores';
        $project                      = $request->getProject() !== null ? $request->getProject() : '';
        $headers["x-log-bodyrawsize"] = 0;
        $headers["Content-Type"]      = "application/json";
        $body                         = array(
            "logStoreName" => $request->getLogStore(),
            "ttl"          => (int) ( $request->getTtl() ),
            "shardCount"   => (int) ( $request->getShardCount() )
        );
        $body_str                     = json_encode($body);
        list( $resp, $header ) = $this->send("POST", $project, $body_str, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new CreateLogStoreResponse($resp, $header);
    }


    /**
     * update logStore
     * Unsuccessful operation will cause an Exception.
     *
     * @param UpdateLogStoreRequest $request the UpdateLogStore request parameters class.
     *
     * @throws Exception
     * return UpdateLogStoreResponse
     */
    public function updateLogStore(UpdateLogStoreRequest $request)
    {
        $headers                      = array();
        $params                       = array();
        $project                      = $request->getProject() !== null ? $request->getProject() : '';
        $headers["x-log-bodyrawsize"] = 0;
        $headers["Content-Type"]      = "application/json";
        $body                         = array(
            "logStoreName" => $request->getLogStore(),
            "ttl"          => (int) ( $request->getTtl() ),
            "shardCount"   => (int) ( $request->getShardCount() )
        );
        $resource                     = '/logstores/' . $request->getLogStore();
        $body_str                     = json_encode($body);
        list( $resp, $header ) = $this->send("PUT", $project, $body_str, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new UpdateLogStoreResponse($resp, $header);
    }


    /**
     * List all logStores of requested project.
     * Unsuccessful operation will cause an Exception.
     *
     * @param ListLogStoresRequest $request the ListLogStores request parameters class.
     *
     * @throws Exception
     * @return ListLogStoresResponse
     */
    public function listLogStores(ListLogStoresRequest $request)
    {
        $headers  = array();
        $params   = array();
        $resource = '/logstores';
        $project  = $request->getProject() !== null ? $request->getProject() : '';
        list ( $resp, $header ) = $this->send("GET", $project, null, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new ListLogStoresResponse ($resp, $header);
    }


    /**
     * Delete logStore
     * Unsuccessful operation will cause an Exception.
     *
     * @param DeleteLogStoreRequest $request the DeleteLogStores request parameters class.
     *
     * @throws Exception
     * @return DeleteLogStoreResponse
     */
    public function deleteLogStore(DeleteLogStoreRequest $request)
    {
        $headers = array();
        $params  = array();

        $project  = $request->getProject() ?: '';
        $logStore = $request->getLogStore() ?: '';

        $resource = "/logstores/$logStore";
        list ( $resp, $header ) = $this->send("DELETE", $project, null, $resource, $params, $headers);

        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new DeleteLogStoreResponse ($resp, $header);
    }


    /**
     * List all topics in a logStore.
     * Unsuccessful operation will cause an Exception.
     *
     * @param ListTopicsRequest $request the ListTopics request parameters class.
     *
     * @throws Exception
     * @return ListTopicsResponse
     */
    public function listTopics(ListTopicsRequest $request)
    {
        $headers = array();
        $params  = array();
        if ($request->getToken() !== null) {
            $params ['token'] = $request->getToken();
        }
        if ($request->getLine() !== null) {
            $params ['line'] = $request->getLine();
        }
        $params ['type'] = 'topic';

        $project  = $request->getProject() ?: '';
        $logStore = $request->getLogStore() ?: '';

        $resource = "/logstores/$logStore";
        list ( $resp, $header ) = $this->send("GET", $project, null, $resource, $params, $headers);

        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new ListTopicsResponse ($resp, $header);
    }


    /**
     * Get histograms of requested query from log service.
     * Unsuccessful operation will cause an Exception.
     *
     * @param GetHistogramsRequest $request the GetHistograms request parameters class.
     *
     * @throws Exception
     * @return GetHistogramsResponse
     */
    public function getHistograms(GetHistogramsRequest $request)
    {
        $headers = array();
        $params  = array();
        if ($request->getTopic() !== null) {
            $params ['topic'] = $request->getTopic();
        }
        if ($request->getFrom() !== null) {
            $params ['from'] = $request->getFrom();
        }
        if ($request->getTo() !== null) {
            $params ['to'] = $request->getTo();
        }
        if ($request->getQuery() !== null) {
            $params ['query'] = $request->getQuery();
        }
        $params ['type'] = 'histogram';

        $project  = $request->getProject() ?: '';
        $logStore = $request->getLogStore() ?: '';

        $resource = "/logstores/$logStore";
        list ( $resp, $header ) = $this->send("GET", $project, null, $resource, $params, $headers);

        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new GetHistogramsResponse ($resp, $header);
    }


    /**
     * Get logs from Log service.
     * Unsuccessful operation will cause an Exception.
     *
     * @param GetLogsRequest $request the GetLogs request parameters class.
     *
     * @throws Exception
     * @return GetLogsResponse
     */
    public function getLogs(GetLogsRequest $request)
    {
        $headers = array();
        $params  = array();
        if ($request->getTopic() !== null) {
            $params ['topic'] = $request->getTopic();
        }
        if ($request->getFrom() !== null) {
            $params ['from'] = $request->getFrom();
        }
        if ($request->getTo() !== null) {
            $params ['to'] = $request->getTo();
        }
        if ($request->getQuery() !== null) {
            $params ['query'] = $request->getQuery();
        }
        $params ['type'] = 'log';
        if ($request->getLine() !== null) {
            $params ['line'] = $request->getLine();
        }
        if ($request->getOffset() !== null) {
            $params ['offset'] = $request->getOffset();
        }
        if ($request->getOffset() !== null) {
            $params ['reverse'] = $request->getReverse() ? 'true' : 'false';
        }

        $project  = $request->getProject() ?: '';
        $logStore = $request->getLogStore() ?: '';

        $resource = "/logstores/$logStore";
        list ( $resp, $header ) = $this->send("GET", $project, null, $resource, $params, $headers);

        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new GetLogsResponse ($resp, $header);
    }


    /**
     * Get logs from Log service with shardid conditions.
     * Unsuccessful operation will cause an Exception.
     *
     * @param BatchGetLogsRequest $request the BatchGetLogs request parameters class.
     *
     * @throws Exception
     * @return BatchGetLogsResponse
     */
    public function batchGetLogs(BatchGetLogsRequest $request)
    {
        $params  = array();
        $headers = array();

        $project  = $request->getProject() ?: '';
        $logStore = $request->getLogStore() ?: '';
        $shardId  = ! is_null($request->getShardId()) ? $request->getShardId() : '';

        if ( ! is_null($request->getCount())) {
            $params['count'] = $request->getCount();
        }

        if ( ! is_null($request->getCursor())) {
            $params['cursor'] = $request->getCursor();
        }

        $params['type']             = 'log';
        $headers['Accept-Encoding'] = 'gzip';
        $headers['accept']          = 'application/x-protobuf';
        $resource                   = "/logstores/$logStore/shards/$shardId";

        list( $resp, $header ) = $this->send("GET", $project, null, $resource, $params, $headers);
        //$resp is a byteArray
        $resp = gzuncompress($resp);

        if ($resp === false) {
            $resp = new LogGroupList();
        } else {
            $resp = new LogGroupList($resp);
        }

        return new BatchGetLogsResponse($resp, $header);
    }


    /**
     * List Shards from Log service with Project and logStore conditions.
     * Unsuccessful operation will cause an Exception.
     *
     * @param ListShardsRequest $request the ListShards request parameters class.
     *
     * @throws Exception
     * @return ListShardsResponse
     */
    public function listShards(ListShardsRequest $request)
    {
        $params  = array();
        $headers = array();

        $project  = $request->getProject() ?: '';
        $logStore = $request->getLogStore() ?: '';

        $resource = '/logstores/' . $logStore . '/shards';
        list( $resp, $header ) = $this->send("GET", $project, null, $resource, $params, $headers);

        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new ListShardsResponse ($resp, $header);
    }


    /**
     * Get cursor from Log service.
     * Unsuccessful operation will cause an Exception.
     *
     * @param GetCursorRequest $request the GetCursor request parameters class.
     *
     * @throws Exception
     * @return GetCursorResponse
     */
    public function getCursor(GetCursorRequest $request)
    {
        $params  = array();
        $headers = array();

        $project  = $request->getProject() ?: '';
        $logStore = $request->getLogStore() ?: '';
        $shardId  = is_null($request->getShardId()) ? '' : $request->getShardId();

        $mode     = $request->getMode() ?: '';
        $fromTime = $request->getFromTime() ?: -1;

        if (( empty( $mode ) xor $fromTime == -1 ) == false) {
            if ( ! empty( $mode )) {
                throw new Exception ("[RequestError] Request is failed. Mode and fromTime can not be not empty simultaneously");
            } else {
                throw new Exception ("[RequestError] Request is failed. Mode and fromTime can not be empty simultaneously");
            }
        }
        if ( ! empty( $mode ) && strcmp($mode, 'begin') !== 0 && strcmp($mode, 'end') !== 0) {
            throw new Exception ("[RequestError] Request is failed. Mode value invalid:$mode");
        }
        if ($fromTime !== -1 && ( is_integer($fromTime) == false || $fromTime < 0 )) {
            throw new Exception ("[RequestError] Request is failed. FromTime value invalid:$fromTime");
        }
        $params['type'] = 'cursor';
        if ($fromTime !== -1) {
            $params['from'] = $fromTime;
        } else {
            $params['mode'] = $mode;
        }
        $resource = '/logstores/' . $logStore . '/shards/' . $shardId;

        list( $resp, $header ) = $this->send("GET", $project, null, $resource, $params, $headers);

        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';

        $resp = $this->parseToJson($resp, $requestId);

        return new GetCursorResponse($resp, $header);
    }


    public function createConfig(CreateConfigRequest $request)
    {
        $params  = array();
        $headers = array();
        $body    = null;

        if ( ! is_null($request->getConfig())) {
            $body = json_encode($request->getConfig()->toArray());
        }

        $headers ['Content-Type'] = 'application/json';
        $resource                 = '/configs';
        list( $resp, $header ) = $this->send("POST", null, $body, $resource, $params, $headers);

        return new CreateConfigResponse($header);
    }


    public function updateConfig(UpdateConfigRequest $request)
    {
        $params     = array();
        $headers    = array();
        $body       = null;
        $configName = '';

        if ( ! is_null($request->getConfig())) {
            $body       = json_encode($request->getConfig()->toArray());
            $configName = $request->getConfig()->getConfigName() ?: '';
        }

        $headers ['Content-Type'] = 'application/json';
        $resource                 = '/configs/' . $configName;
        list( $resp, $header ) = $this->send("PUT", null, $body, $resource, $params, $headers);

        return new UpdateConfigResponse($header);
    }


    public function getConfig(GetConfigRequest $request)
    {
        $params  = array();
        $headers = array();

        $configName = ( $request->getConfigName() !== null ) ? $request->getConfigName() : '';

        $resource = '/configs/' . $configName;
        list( $resp, $header ) = $this->send("GET", null, null, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new GetConfigResponse($resp, $header);
    }


    public function deleteConfig(DeleteConfigRequest $request)
    {
        $params  = array();
        $headers = array();

        $configName = $request->getConfigName() ?: '';

        $resource = '/configs/' . $configName;
        list( $resp, $header ) = $this->send("DELETE", null, null, $resource, $params, $headers);

        return new DeleteConfigResponse($header);
    }


    public function listConfigs(ListConfigsRequest $request)
    {
        $params  = array();
        $headers = array();

        if ( ! is_null($request->getConfigName())) {
            $params['configName'] = $request->getConfigName();
        }

        if ( ! is_null($request->getOffset())) {
            $params['offset'] = $request->getOffset();
        }

        if ( ! is_null($request->getSize())) {
            $params['size'] = $request->getSize();
        }

        $resource = '/configs';
        list( $resp, $header ) = $this->send("GET", null, null, $resource, $params, $headers);

        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new ListConfigsResponse($resp, $header);
    }


    public function createMachineGroup(CreateMachineGroupRequest $request)
    {
        $params  = array();
        $headers = array();
        $body    = null;

        if ( ! is_null($request->getMachineGroup())) {
            $body = json_encode($request->getMachineGroup()->toArray());
        }

        $headers ['Content-Type'] = 'application/json';
        $resource                 = '/machinegroups';
        list( $resp, $header ) = $this->send("POST", null, $body, $resource, $params, $headers);

        return new CreateMachineGroupResponse($header);
    }


    public function updateMachineGroup(UpdateMachineGroupRequest $request)
    {
        $params    = array();
        $headers   = array();
        $body      = null;
        $groupName = '';

        if ( ! is_null($request->getMachineGroup())) {
            $body      = json_encode($request->getMachineGroup()->toArray());
            $groupName = $request->getMachineGroup()->getGroupName() ?: '';
        }

        $headers ['Content-Type'] = 'application/json';
        $resource                 = '/machinegroups/' . $groupName;
        list( $resp, $header ) = $this->send("PUT", null, $body, $resource, $params, $headers);

        return new UpdateMachineGroupResponse($header);
    }


    public function getMachineGroup(GetMachineGroupRequest $request)
    {
        $params  = array();
        $headers = array();

        $groupName = $request->getGroupName() ?: '';

        $resource = '/machinegroups/' . $groupName;
        list( $resp, $header ) = $this->send("GET", null, null, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new GetMachineGroupResponse($resp, $header);
    }


    public function deleteMachineGroup(DeleteMachineGroupRequest $request)
    {
        $params  = array();
        $headers = array();

        $groupName = $request->getGroupName() ?: '';

        $resource = '/machinegroups/' . $groupName;
        list( $resp, $header ) = $this->send("DELETE", null, null, $resource, $params, $headers);

        return new DeleteMachineGroupResponse($header);
    }


    public function listMachineGroups(ListMachineGroupsRequest $request)
    {
        $params  = array();
        $headers = array();

        if ( ! is_null($request->getGroupName())) {
            $params['groupName'] = $request->getGroupName();
        }
        if ( ! is_null($request->getOffset())) {
            $params['offset'] = $request->getOffset();
        }
        if ( ! is_null($request->getSize())) {
            $params['size'] = $request->getSize();
        }

        $resource = '/machinegroups';
        list( $resp, $header ) = $this->send("GET", null, null, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new ListMachineGroupsResponse($resp, $header);
    }


    public function applyConfigToMachineGroup(ApplyConfigToMachineGroupRequest $request)
    {
        $params                   = array();
        $headers                  = array();
        $configName               = $request->getConfigName();
        $groupName                = $request->getGroupName();
        $headers ['Content-Type'] = 'application/json';
        $resource                 = '/machinegroups/' . $groupName . '/configs/' . $configName;
        list( $resp, $header ) = $this->send("PUT", null, null, $resource, $params, $headers);

        return new ApplyConfigToMachineGroupResponse($header);
    }


    public function removeConfigFromMachineGroup(RemoveConfigFromMachineGroupRequest $request)
    {
        $params                   = array();
        $headers                  = array();
        $configName               = $request->getConfigName();
        $groupName                = $request->getGroupName();
        $headers ['Content-Type'] = 'application/json';
        $resource                 = '/machinegroups/' . $groupName . '/configs/' . $configName;
        list( $resp, $header ) = $this->send("DELETE", null, null, $resource, $params, $headers);

        return new RemoveConfigFromMachineGroupResponse($header);
    }


    public function getMachine(GetMachineRequest $request)
    {
        $params  = array();
        $headers = array();

        $uuid = $request->getUuid() ?: '';

        $resource = '/machines/' . $uuid;
        list( $resp, $header ) = $this->send("GET", null, null, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new GetMachineResponse($resp, $header);
    }


    public function createACL(CreateACLRequest $request)
    {
        $params  = array();
        $headers = array();
        $body    = null;

        if ($request->getAcl() !== null) {
            $body = json_encode($request->getAcl()->toArray());
        }

        $headers ['Content-Type'] = 'application/json';
        $resource                 = '/acls';
        list( $resp, $header ) = $this->send("POST", null, $body, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new CreateACLResponse($resp, $header);
    }


    public function updateACL(UpdateACLRequest $request)
    {
        $params  = array();
        $headers = array();
        $body    = null;
        $aclId   = '';

        if ( ! is_null($request->getAcl())) {
            $body  = json_encode($request->getAcl()->toArray());
            $aclId = ( $request->getAcl()->getAclId() !== null ) ? $request->getAcl()->getAclId() : '';
        }

        $headers ['Content-Type'] = 'application/json';
        $resource                 = '/acls/' . $aclId;
        list( $resp, $header ) = $this->send("PUT", null, $body, $resource, $params, $headers);

        return new UpdateACLResponse($header);
    }


    public function getACL(GetACLRequest $request)
    {
        $params  = array();
        $headers = array();

        $aclId = $request->getAclId() ?: '';

        $resource = '/acls/' . $aclId;
        list( $resp, $header ) = $this->send("GET", null, null, $resource, $params, $headers);
        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new GetACLResponse($resp, $header);
    }


    public function deleteACL(DeleteACLRequest $request)
    {
        $params  = array();
        $headers = array();

        $aclId = $request->getAclId() ?: '';

        $resource = '/acls/' . $aclId;
        list( $resp, $header ) = $this->send("DELETE", null, null, $resource, $params, $headers);

        return new DeleteACLResponse($header);
    }


    public function listACLs(ListACLsRequest $request)
    {
        $params  = array();
        $headers = array();

        if ( ! is_null($request->getPrincipleId())) {
            $params['principleId'] = $request->getPrincipleId();
        }

        if ( ! is_null($request->getOffset())) {
            $params['offset'] = $request->getOffset();
        }

        if ( ! is_null($request->getSize())) {
            $params['size'] = $request->getSize();
        }

        $resource = '/acls';
        list( $resp, $header ) = $this->send("GET", null, null, $resource, $params, $headers);

        $requestId = isset ( $header ['x-log-requestid'] ) ? $header ['x-log-requestid'] : '';
        $resp      = $this->parseToJson($resp, $requestId);

        return new ListACLsResponse($resp, $header);
    }
}

