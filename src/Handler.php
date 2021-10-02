<?php
namespace thinkTideways\tideways;

use think\exception\ErrorException;

class Handler
{
    protected $meta;
    protected $time;
    protected $profile;
    protected $profiles;
    protected $sql;

    public function __construct(Profiles $profiles)
    {
        $this->profiles = $profiles;
    }
    public function exec()
    {
        try {
            $this->setProfile();
            $this->setSql();
            ignore_user_abort(true);
            flush();
            $this->setMeta();
            $this->save();
        }catch (\Exception $e){
            throw new ErrorException($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine());
        }

    }
    protected function setProfile()
    {
        $this->profile = tideways_disable();
    }

    protected function setSql()
    {
        $sqlData = tideways_get_spans();
        $sql = array();
        if(isset($sqlData[1])){
            foreach($sqlData as $val){
                if(isset($val['n'])&&$val['n'] === 'sql'&&isset($val['a'])&&isset($val['a']['sql'])){
                    $_time_tmp = (isset($val['b'][0])&&isset($val['e'][0]))?($val['e'][0]-$val['b'][0]):0;
                    if(!empty($val['a']['sql'])){
                        $sql[] = array(
                            'time' => $_time_tmp,
                            'sql' => $val['a']['sql']
                        );
                    }
                }
            }
        }
        $this->sql = $sql;
    }
    protected function setMeta()
    {
        $uri = $this->getUri();
        $requestTs = $this->getRequestTs();
        $requestTsMicro = $this->getRequestTsMicro();
        $requestDate = $this->getRequestDate();

        $this->meta = array(
            'url' => $uri,
            'SERVER' => $_SERVER,
            'get' => $_GET,
            'env' => $_ENV,
            'simple_url' => "",
            'request_ts' => $requestTs,
            'request_ts_micro' => $requestTsMicro,
            'request_date' => $requestDate,
        );
    }

    protected function save()
    {
        $meta = $this->meta;
        $profile = $this->profile;
        $sql = $this->sql;
        $data = compact("meta","profile","sql");
        $this->profiles->insert($data);
    }
    /**
     * @return string
     */
    protected function getUri()
    {
        $uri = array_key_exists('REQUEST_URI', $_SERVER)
            ? $_SERVER['REQUEST_URI']
            : null;
        if (empty($uri) && isset($_SERVER['argv'])) {
            $cmd = basename($_SERVER['argv'][0]);
            $uri = $cmd . ' ' . implode(' ', array_slice($_SERVER['argv'], 1));
        }
        return $uri;
    }

    protected function getRequestTs()
    {
        $time = array_key_exists('REQUEST_TIME', $_SERVER)
            ? $_SERVER['REQUEST_TIME']
            : time();
        $this->time = $time;
        return new \MongoDate($time);
    }

    protected function getRequestTsMicro()
    {
        $requestTimeFloat = explode('.', $_SERVER['REQUEST_TIME_FLOAT'])
        ;
        if (!isset($requestTimeFloat[1])) {
            $requestTimeFloat[1] = 0;
        }
        return new \MongoDate($requestTimeFloat[0], $requestTimeFloat[1]);
    }

    protected function getRequestDate()
    {
        return date('Y-m-d', $this->time);
    }


}