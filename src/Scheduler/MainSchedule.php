<?php

namespace App\Scheduler;

use App\Message\MatchInfoMessage;
use App\Message\TeamInfoMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
class MainSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private string $league,
        private CacheInterface $cache
    )
    {
    }

    public function getSchedule(): Schedule
    {
        return (new Schedule())->add(
            RecurringMessage::every('1 days', new TeamInfoMessage($this->league)),
            RecurringMessage::every('1 days', new MatchInfoMessage($this->league))
        )->stateful($this->cache);
    }
}