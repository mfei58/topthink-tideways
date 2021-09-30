<?php
namespace ThinkTideways\tideways\listener;

use ThinkTideways\tideways\facade\Tideways;

class TidewaysEnable
{
    public function handle($event)
    {
        Tideways::enable();
    }
}