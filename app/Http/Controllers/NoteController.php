<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Models\Note;
use App\Repositories\Note\NoteRepositoryInterface;

class NoteController extends Controller
{
    protected $noteRepository;

    public function __construct(NoteRepositoryInterface $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function store(NoteRequest $request)
    {
        $inputs = $request->only('content', 'campaign_id');

        if (auth()->check()) {
             $dataNote = [
                'content' => $inputs['content'],
                'campaign_id' => $inputs['campaign_id'],
                'creator_user_id' => auth()->user()->id,
            ];

            $this->noteRepository->create($dataNote);
        }

        return redirect()->action('CampaignController@show', $inputs['campaign_id']);
    }

    public function update(NoteRequest $request, $id)
    {
        $inputs = $request->only('content', 'campaign_id');
        $note = $this->noteRepository->find($id);

        if ($note && auth()->check()) {
            $dataNote = [
                'content' => $inputs['content'],
                'campaign_id' => $inputs['campaign_id'],
                'edit_user_id' => auth()->user()->id,
            ];

            $note->update($dataNote);
        }

        return redirect()->action('CampaignController@show', $inputs['campaign_id']);
    }
}
