<?php
namespace App\Repositories\Schedule;

use App\Models\Schedule;
use App\Repositories\BaseRepository;
use App\Repositories\Schedule\ScheduleRepositoryInterface;

class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{
    function model()
    {
        return Schedule::class;
    }
}
