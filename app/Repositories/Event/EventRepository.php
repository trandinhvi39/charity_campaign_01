<?php
namespace App\Repositories\Event;

use App\Models\Event;
use App\Repositories\BaseRepository;
use App\Repositories\Event\EventRepositoryInterface;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{

    public function model()
    {
        return Event::class;
    }
}
