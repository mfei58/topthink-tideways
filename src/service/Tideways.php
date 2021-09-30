<?php
namespace ThinkTideways\tideways\service;
use app\tideways\Handler;
use app\tideways\Manager;
use app\tideways\Profiles;
use think\event\HttpRun;
use think\helper\Arr;
use think\Service;
class Tideways extends  Service
{
    public function boot()
    {
        $this->app->event->listen('HttpRun', 'app\tideways\listener\TidewaysEnable');
        $this->app->event->listen('HttpEnd', 'app\tideways\listener\TidewaysDisable');
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
        $this->app->bind('tideways','app\tideways\Manager');
    }
}