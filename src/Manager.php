<?php
namespace thinkTideways\tideways;
use thinkTideways\tideways\constract\ManagerInterface;
use thinkTideways\tideways\exception\ExtensionNotFoundException;
use think\App;
use think\Config;

class Manager implements ManagerInterface
{
    /**
     * 配置参数
     * @var array
     */
    protected $config = [];

    /**
     * @var App
     */
    protected $app;
    /**
     * @var bool
     */
    protected $enable;
    /**
     * @var Handler
     */
    protected $handler;
    /**
     * @param App $app
     * @param Config $config
     */
    public function __construct(App $app, Config $config)
    {
        $this->app    = $app;
        $this->config = $config->get('tideways');
        $this->handler = $this->app->make('tidewaysHandler',[$app,$config]);
        $this->setEnable($this->config['enable']);
    }
    public function enable()
    {
        if(!$this->isEnable()){
            return false;
        }
        $this->checkExtension();
        if (!isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
        }
        tideways_enable(TIDEWAYS_FLAGS_CPU | TIDEWAYS_FLAGS_MEMORY);
        tideways_span_create('sql');
        return true;
    }

    public function disable()
    {
        if(!$this->isEnable()){
            return false;
        }
        $this->checkExtension();
        $this->handler->exec();
    }

    /**
     * 开启监控
     * @access public
     * @param bool $enable 是否已开启
     * @return $this
     */
    public function setEnable(bool $enable = true)
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * 是否已开启监控
     * @access public
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }
    protected function checkExtension()
    {
        $this->checkXhprof();
        $this->checkTideways();
        $this->checkMongodb();
    }
    protected function checkXhprof()
    {
        if(!extension_loaded("xhprof")){
            throw new ExtensionNotFoundException("extension xhprof must be loaded","xhprof");
        }
    }

    protected function checkTideways()
    {
        if(!extension_loaded("tideways")){
            throw new ExtensionNotFoundException("extension tideways must be loaded","xhprof");
        }
    }

    protected function checkMongodb()
    {
        if(!extension_loaded("mongodb")){
            throw new ExtensionNotFoundException("extension mongodb must be loaded","mongodb");
        }
    }
}