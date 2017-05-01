<?php
namespace App\Repositories\Campaign;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Message;
use Auth;
use Input;
use App\Models\Campaign;
use App\Models\Image;
use App\Models\UserCampaign;
use App\Repositories\BaseRepository;
use App\Repositories\Campaign\CampaignRepositoryInterface;
use DB;
use Illuminate\Container\Container;
use \Carbon\Carbon;
use App\Services\GoogleMap;
use App\Models\Tag;

class CampaignRepository extends BaseRepository implements CampaignRepositoryInterface
{

    protected $container;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    function model()
    {
        return Campaign::class;
    }

    public function getPopularTags()
    {
        $tags = Tag::pluck('name');
        $campaignTags = Campaign::pluck('tags');
        $tagsCount = [];
        $countChoisedTags = 0;

        foreach ($tags as $tag) {
            $count = 0;

            if ($countChoisedTags == config('settings.max_tags')) {
                break;
            }

            foreach ($campaignTags as $campaignTag) {
                if ($campaignTag && strpos($campaignTag, $tag)) {
                    $count++;
                }
            }

            if ($count) {
                $countChoisedTags++;
                $tagsCount[] = [
                    'tag' => $tag,
                    'count' => $count,
                ];
            }
        }

        $tagsCount = array_values(array_sort($tagsCount, function ($value) {
            return $value['count'];
        }));

        $tagsCount = array_reverse($tagsCount);

        return $tagsCount;
    }

    public function countCampaignsByTag($tag)
    {
        return $this->model->where('tags', 'like', "%$tag%")->count();
    }

    public function getCampaignByTagsName(string $tags)
    {
        return $this->model->with('image')
            ->with(['owner.user', 'owner' => function ($query) {
                $query->where('is_owner', config('constants.OWNER'));
            }])
            ->where('tags', 'like', "%$tags%")
            ->orderBy('id', 'desc');
    }

    public function filterCampaign($type)
    {
        switch ($type) {
                case trans('campaign.newest'):
                    return $this->model->with('image')
                        ->with(['owner.user', 'owner' => function ($query) {
                            $query->where('is_owner', config('constants.OWNER'));
                        }])
                        ->where('status', config('constants.ACTIVATED'))
                        ->orderBy('created_at', 'desc')
                        ->paginate(config('constants.PAGINATE'));
                case trans('campaign.oldest'):
                    return $this->model->with('image')
                            ->with(['owner.user', 'owner' => function ($query) {
                                $query->where('is_owner', config('constants.OWNER'));
                            }])
                            ->where('status', config('constants.ACTIVATED'))
                            ->orderBy('created_at')
                            ->paginate(config('constants.PAGINATE'));
                case trans('campaign.hotest'):
                    $campaigns = $this->model->get();
                    $hotCampaigns = [];

                    foreach ($campaigns as $campaign) {
                        $hotCampaigns[] = [
                            'countMember' => count($this->getMembers($campaign->id)),
                            'campaign' => $campaign,
                        ];
                    }

                    return paginateCollection(
                        collect($hotCampaigns)->sortByDesc('countMember')->pluck('campaign'),
                        config('constants.PAGINATE')
                        );
                case trans('campaign.open'):
                    return $this->model->with('image')
                        ->with(['owner.user', 'owner' => function ($query) {
                            $query->where('is_owner', config('constants.OWNER'));
                        }])
                        ->where('status', config('constants.ACTIVATED'))
                        ->orderBy('id', 'desc')
                        ->paginate(config('constants.PAGINATE'));
                case trans('campaign.closed'):
                    return $this->model->with('image')
                        ->with(['owner.user', 'owner' => function ($query) {
                            $query->where('is_owner', config('constants.OWNER'));
                        }])
                        ->where('status', config('constants.NOT_ACTIVE'))
                        ->orderBy('id', 'desc')
                        ->paginate(config('constants.PAGINATE'));
                default:
                    return null;
            }
    }

    public function getRelatedCampaign($currentCampaign)
    {
        $relatedCampaigns = $this->model
            ->where('id', '<>', $currentCampaign->id)
            ->orderBy('id', 'DESC')
            ->get();

        $arrayRelated = [];
        $countCurrentTags = count($currentCampaign->getTags());

        foreach ($relatedCampaigns as $relatedCampaign) {
            if (count(array_diff($currentCampaign->getTags(), $relatedCampaign->getTags())) < $countCurrentTags
                ) {
                $arrayRelated[] = $relatedCampaign;
            }
        }

        if (count($arrayRelated) >= config('settings.number_of_suggested_campaigns')) {
            return collect($arrayRelated)
                ->take(config('settings.number_of_suggested_campaigns'));
        }

        return $arrayRelated;
    }

    public function getSuggestNearestCampaigns($currentCampaign)
    {
        $campaigns = $this->model
            ->where('status', config('constants.ACTIVATED'))
            ->where('id', '<>', $currentCampaign->id)
            ->get();
        $distances = [];

        if ($currentCampaign->lat && $currentCampaign->lng) {
            if ($campaigns) {
                foreach ($campaigns as $campaign) {
                    if ($campaign->lat && $campaign->lng) {
                        $distances[] = [
                            'distance' => distanceGeoPoints($currentCampaign->lat, $currentCampaign->lng, $campaign->lat, $campaign->lng),
                            'campaign' => $campaign,
                        ];
                    }
                }
            }

            if (count($distances) >= config('settings.number_of_suggested_campaigns')) {
                return collect($distances)
                    ->sortBy('distance')
                    ->pluck('campaign')
                    ->take(config('settings.number_of_suggested_campaigns'));
            }

            return collect($distances)->sortBy('distance')->pluck('campaign');
        }
    }

    public function getSuggestHotestCampaigns($currentCampaign)
    {
        $campaigns = $this->model->where('id', '<>' ,$currentCampaign->id)->get();
        $hotCampaigns = [];

        foreach ($campaigns as $campaign) {
            $hotCampaigns[] = [
                'countMember' => count($this->getMembers($campaign->id)),
                'campaign' => $campaign,
            ];
        }

        if (count($hotCampaigns) >= config('settings.number_of_suggested_campaigns')) {
            return collect($hotCampaigns)
                ->sortByDesc('countMember')
                ->pluck('campaign')
                ->take(config('settings.number_of_suggested_campaigns'));
        }

        return collect($hotCampaigns)->sortByDesc('countMember')->pluck('campaign');
    }

    public function getAll()
    {
        return $this->model->with('image')
            ->with(['owner.user', 'owner' => function ($query) {
                $query->where('is_owner', config('constants.OWNER'));
            }])
            ->orderBy('id', 'desc');
    }

    public function createCampaign($params = [])
    {
        if (empty($params)) {

            return false;
        }

        DB::beginTransaction();
        try {
            $image = $this->uploadImage($params['image'], config('path.campaign'));

            $googleMap = new GoogleMap();
            $location = $googleMap->getAddress(['address' => $params['address']]);

            if (!$location) {
                $location = [
                    'latitude' => '',
                    'longitude' => '',
                ];
            }

            $campaign = $this->model->create([
                'name' => $params['name'],
                'description' => $params['description'],
                'start_time' => $params['start_date'],
                'end_time' => $params['end_date'],
                'address' => $params['address'],
                'lat' => $location['latitude'],
                'lng' => $location['longitude'],
                'status' => config('constants.NOT_ACTIVE'),
                'tags' => $params['tags'],
            ]);

            $goals = $params['goal'];
            $contributions = $params['contribution_type'];
            $units = $params['unit'];

            $inputs = [];
            foreach ($goals as $key => $goal) {
                foreach ($contributions as $k => $contribution)  {
                    if ($key == $k && $contribution && $goal && $units[$k]) {
                        $inputs[] = [
                            'name' => $contribution,
                            'goal' => (int) $goal,
                            'unit' => $units[$key],
                        ];
                    }
                }
            }

            $campaign->categories()->createMany($inputs);

            $campaign->image()->create([
                'image' => $image,
            ]);

            $campaign->owner()->create([
                'user_id' => Auth::user()->id,
                'is_owner' => config('constants.OWNER'),
                'status' => config('constants.ACTIVATED'),
            ]);

            $group = $campaign->group()->create([
                'name' => $params['name'],
            ]);

            GroupMember::create([
                'user_id' => auth()->id(),
                'group_id' => $group->id,
                'latest' => Carbon::now(),
            ]);

            $campaign->save();

            DB::commit();

            return $campaign;
        } catch (Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function getDetail($id)
    {
        if (!$id) {
            return false;
        }

        return $this->model->with(['image', 'owner.user', 'comments.user'])
            ->with(['contributions.user', 'contributions' => function ($query) {
                $query->where('status', config('constants.ACTIVATED'));
            }])
            ->with('categories')
            ->find($id);
    }

    public function joinOrLeaveCampaign($params = [])
    {
        if (empty($params)) {
            return false;
        }

        // get group chat
        $group = Group::where('campaign_id', $params['campaign_id'])->first();

        DB::beginTransaction();
        try {
            if ($userCampaign = $this->checkUserCampaign($params)) {

                // remove group chat
                $member = GroupMember::where([
                    'user_id' => $userCampaign->user_id,
                    'group_id' => $group->id,
                ])->first();

                if ($member) {
                    $member->delete();
                }

                $userCampaign->delete();
                DB::commit();

                return true;
            }

            $userCampaign = UserCampaign::create($params);

            DB::commit();

            return $userCampaign;
        } catch (Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function checkUserCampaign($params = [])
    {
        if (empty($params)) {
            return false;
        }

        return UserCampaign::where($params)->first();
    }

    public function listCampaignOfUser($userId)
    {
        if (!$userId) {
            return false;
        }

        return $this->model->whereHas('owner', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('is_owner', config('constants.OWNER'));
            })
            ->orderBy('id', 'desc');
    }

    public function approveOrRemove($params = [])
    {
        if (empty($params)) {
            return false;
        }

        $userCampaign = $this->checkUserCampaign($params);

        if (empty($userCampaign)) {
            return false;
        }

        DB::beginTransaction();
        try {
            if (!$userCampaign->status) {
                // approve
                $userCampaign->status = config('constants.ACTIVATED');
                $userCampaign->save();

                // get group chat
                $group = Group::where('campaign_id', $userCampaign->campaign_id)->first();

                // add group chat
                GroupMember::create([
                    'user_id' => $userCampaign->user_id,
                    'group_id' => $group->id,
                    'latest' => Carbon::now(),
                ]);

                DB::commit();

                return $userCampaign;
            }

            // get group chat
            $group = Group::where('campaign_id', $userCampaign->campaign_id)->first();

            // remove group chat
            $member = GroupMember::where([
                'user_id' => $userCampaign->user_id,
                'group_id' => $group->id,
            ])->first();

            if ($member) {
                $member->delete();
            }

            $userCampaign->status = config('constants.NOT_ACTIVE');
            $userCampaign->save();
            DB::commit();

            return $userCampaign;
        } catch (Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function activeOrCloseCampaign($params = [])
    {
        if (empty($params)) {
            return false;
        }

        $campaign = $this->model->find($params['campaign_id']);

        if (!$campaign) {
            return false;
        }

        DB::beginTransaction();
        try {
            $campaign->status = config('constants.ACTIVATED') - $campaign->status;
            $campaign->save();

            if ($campaign->status) {
                $campaign->actions()->create([
                    'user_id' => auth()->id(),
                    'action_type' => config('constants.ACTION.ACTIVE_CAMPAIGN'),
                    'time' => time(),
                ]);
            } else {
                $campaign->actions()->create([
                    'user_id' => auth()->id(),
                    'action_type' => config('constants.ACTION.CLOSE_CAMPAIGN'),
                    'time' => time(),
                ]);
            }
            DB::commit();

            return $campaign;
        } catch (Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function uploadImageCampaign($image)
    {
        $imageName = $this->uploadImage($image, config('path.description'));

        return config('path.description') . $imageName;
    }

    public function countCampaign($userId)
    {
        if (!$userId) {
            return false;
        }

        return UserCampaign::where('user_id', $userId)
            ->where('is_owner', config('constants.OWNER'))
            ->whereHas('campaign', function ($query) {
                $query->where('status', config('constants.ACTIVATED'));
            })
            ->count();
    }

    public function searchCampaign($keyWords)
    {
        $campaigns = $this->model->search($keyWords)
            ->with('image')
            ->get();

        $result = [];
        foreach ($campaigns as $campaign) {
            $result[] = [
                'html' => view('campaign.search_result', ['campaign' => $campaign])->render(),
                'success' => true,
            ];
        }

        return $result;
    }

    public function getUserCampaigns($userId)
    {
        if (!$userId) {
            return false;
        }

        return $this->model->with(['owner' => function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('is_owner', config('constants.OWNER'));
            }])
            ->where('status', config('constants.ACTIVATED'))
            ->orderBy('id', 'desc');
    }

    public function getMembers($id)
    {
        if (!$id) {

            return [];
        }

        return UserCampaign::where('campaign_id', $id)
            ->where('status', config('constants.ACTIVATED'))
            ->where('is_owner', config('constants.NOT_OWNER'))
            ->with('user')
            ->get();
    }

    public function getAllTags()
    {
        $tags = Tag::pluck('name');
        $campaignTags = Campaign::pluck('tags');
        $tagsCount = [];

        foreach ($tags as $tag) {
            $count = 0;

            foreach ($campaignTags as $campaignTag) {
                if ($campaignTag && strpos($campaignTag, $tag)) {
                    $count++;
                }
            }

            if ($count) {
                $tagsCount[] = [
                    'tag' => $tag,
                    'count' => $count,
                ];
            }
        }

        $tagsCount = array_values(array_sort($tagsCount, function ($value) {
            return $value['count'];
        }));

        $tagsCount = array_reverse($tagsCount);

        if ($tagsCount) {
            if (count($tagsCount) <= config('settings.items_per_tag')) {
                return paginateCollection(collect($tagsCount), count($tagsCount));
            }

            return paginateCollection(collect($tagsCount), config('settings.items_per_tag'));
        }
    }
}
