<?php
namespace thinkTideways\tideways\facade;
use think\Facade;

/**
 * @see \thinkTideways\tideways\Manager
 * @package think\facade
 * @method static string enable() 开启监控
 * @method static string disable() 结束监控
 */
class Tideways extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'tideways';
    }
}