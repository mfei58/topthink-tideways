<?php
namespace thinkTideways\tideways\listener;

use thinkTideways\tideways\facade\Tideways;

class TidewaysEnable
{
    public function handle($event)
    {
        Tideways::enable();
    }
}