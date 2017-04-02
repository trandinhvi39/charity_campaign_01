<?php
namespace App\Repositories\Group;

interface GroupRepositoryInterface
{
    public function getGroupIdByCampaignId($campaignId);
}
