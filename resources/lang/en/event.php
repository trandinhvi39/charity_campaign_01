<?php

return [
    'create' => 'Create Event',
    'title' => 'Title',
    'description' => 'Description',
    'start_date' => 'Start date',
    'end_date' => 'End date',
    'image' => 'Image',
    'add_image' => 'Add image',
    'schedules' => 'Schedules',
    'schedule_name' => 'Schedule name',
    'schedule_description' => 'Schedule description',
    'add_schedule' => 'Add schedule',
    'validate' => [
        'start_date' => [
            'start_date' => 'Start date',
            'required' => 'Start date is required',
        ],
        'end_date' => [
            'end_date' => 'End date',
            'required' => 'End date is required',
        ],
        'description' => [
            'required' => 'Description is required'
        ]
    ],
];
