<?php
namespace thinkTideways\tideways\listener;

use thinkTideways\tideways\facade\Tideways;

class TidewaysDisable
{
    public function handle($event)
    {
        Tideways::disable();
    }
}