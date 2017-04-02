<?php
namespace App\Repositories\Group;

use DB;
use App\Models\Group;
use App\Repositories\BaseRepository;
use App\Repositories\Group\GroupRepositoryInterface;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    function model()
    {
        return Group::class;
    }

    public function getGroupIdByCampaignId($campaignId)
    {
        $group = $this->model->where('campaign_id', $campaignId)->first();

        if ($group) {
            return $group->id;
        }
    }
}
