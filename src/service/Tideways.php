<?php
namespace thinkTideways\tideways\service;
use thinkTideways\tideways\Handler;
use thinkTideways\tideways\Manager;
use thinkTideways\tideways\Profiles;
use think\event\HttpRun;
use think\helper\Arr;
use think\Service;
class Tideways extends  Service
{
    public function boot()
    {
        $this->app->event->listen('HttpRun', 'thinkTideways\tideways\listener\TidewaysEnable');
        $this->app->event->listen('HttpEnd', 'thinkTideways\tideways\listener\TidewaysDisable');
        $this->app->bind('tidewaysHandler',function($app,$config){
            $config = $config->get('tideways.connection.mongodb');
            $host = $config['host'];
            $db = $config['db'];
            $options = array_filter($config['options']);
            $mongoClient = new \MongoClient($host,$options);
            $mongoClient->$db->results->findOne();
            $profiles = new Profiles($mongoClient->$db);
            return new Handler($profiles);
        });
        $this->app->bind('tideways','thinkTideways\tideways\Manager');
    }
}