<?php
namespace ThinkTideways\tideways\listener;

use ThinkTideways\tideways\facade\Tideways;

class TidewaysDisable
{
    public function handle($event)
    {
        Tideways::disable();
    }
}