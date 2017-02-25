<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Repositories\Event\EventRepositoryInterface;
use App\Repositories\Campaign\CampaignRepositoryInterface;
use App\Repositories\Schedule\ScheduleRepositoryInterface;

class EventController extends Controller
{
    protected $eventRepository;

    protected $campaignRepository;

    protected $scheduleRepository;

    public function __construct(
        EventRepositoryInterface $eventRepository,
        CampaignRepositoryInterface $campaignRepository,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->eventRepository = $eventRepository;
        $this->campaignRepository = $campaignRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function createEvent($campaignId)
    {
        if (!auth()->id()) {
            return abort(404);
        }

        try {
            $currentCampaign = $this->campaignRepository->find($campaignId);

            if ($currentCampaign->checkOwnerOfCampaignByUserId(auth()->id())) {
                $this->dataJson['validateMessage'] = json_encode(trans('campaign.validate'));
                $this->dataJson['campaign_id'] = $campaignId;
                $this->dataJson['add_image_layouts'] = view('layouts.images_append')->render();
                $this->dataJson['add_schedule_layouts'] = view('layouts.schedule')->render();

                return view('event.create', $this->dataJson);
            }
        } catch (\Exception $e) {
            dd($e);
            return abort(404);
        }
    }

    public function show($id)
    {
        try {
            $event = $this->eventRepository->find($id);
            $this->dataView['event'] = $event;

            return view('event.index', $this->dataView);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(EventRequest $request)
    {
        $inputs = $request->only(
            'title',
            'description',
            'campaign_id',
            'image',
            'start_date',
            'end_date',
            'schedule_name',
            'schedule_description'
        );

        $event = $this->eventRepository->create(array_except($inputs, [
            'image',
            'start_date',
            'end_date',
            'schedule_name',
            'schedule_description',
        ]));

        $schedule = [
            'name' => $inputs['schedule_name'],
            'description' => $inputs['schedule_description'],
            'start_time' => $inputs['start_date'],
            'end_time' => $inputs['end_date'],
            'event_id' => $event->id,
        ];

        $result = $this->scheduleRepository->insert([$schedule]);
    }
}
