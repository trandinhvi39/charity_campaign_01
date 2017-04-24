<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Campaign\CampaignRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Models\Campaign;
use App\Repositories\Contribution\ContributionRepositoryInterface;
use App\Repositories\Rating\RatingRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Message\MessageRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use Validator;
use App\Models\User;
use App\Services\Purifier;
use App\Models\Tag;
use App\Models\Notification;

class CampaignController extends BaseController
{

    protected $campaignRepository;
    protected $campaign;
    protected $categoryRepository;
    protected $contributionRepository;
    protected $ratingRepository;
    protected $categoryCampaignRepository;
    protected $userRepository;
    protected $messageRepository;
    protected $groupRepository;

    public function __construct(
        CampaignRepositoryInterface $campaignRepository,
        Campaign $campaign,
        CategoryRepositoryInterface $categoryRepository,
        ContributionRepositoryInterface $contributionRepository,
        RatingRepositoryInterface $ratingRepository,
        UserRepositoryInterface $userRepository,
        MessageRepositoryInterface $messageRepository,
        GroupRepositoryInterface $groupRepository
    ) {
        $this->campaignRepository = $campaignRepository;
        $this->campaign = $campaign;
        $this->categoryRepository = $categoryRepository;
        $this->contributionRepository = $contributionRepository;
        $this->ratingRepository = $ratingRepository;
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->dataView['campaigns'] = $this->campaignRepository->getAll()->paginate(config('constants.PAGINATE'));
        $this->dataView['users'] = $this->userRepository->getUserByRating();
        $this->dataView['popularTags'] = $this->campaignRepository->getPopularTags();

        return view('campaign.index', $this->dataView);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->dataJson['validateMessage'] = json_encode(trans('campaign.validate'));
        $tags = json_encode(Tag::get(['name']));
        $this->dataJson['tags'] = $tags;

        return view('campaign.create', $this->dataJson);
    }

    /**
     * @param CampaignRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CampaignRequest $request)
    {
        $inputs = $request->only([
            'name',
            'image',
            'start_date',
            'end_date',
            'address',
            'lattitude',
            'longitude',
            'description',
            'contribution_type',
            'goal',
            'unit',
            'tags',
        ]);

        $tags = Tag::get(['name'])->pluck('name');
        $listTags = [];

        foreach (explode(",", $inputs['tags']) as $tag){
            if (!$tags->contains($tag)) {
                Tag::create([
                    'name' => $tag,
                ]);
            }
        }

        //$inputs['description'] = Purifier::clean($inputs['description']);
        $campaign = $this->campaignRepository->createCampaign($inputs);

        if (!$campaign) {
            return redirect(action('CampaignController@create'))
                ->withMessage(trans('campaign.create_error'));
        }

        return redirect(action('UserController@listUserCampaign', ['id' => auth()->id()]))
            ->with(['alert-success' => trans('campaign.create_success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->dataView['campaign'] = $this->campaignRepository->getDetail($id);

        if (!$this->dataView['campaign']) {
            return abort(404);
        }

        // get total contributions
        $this->dataView['results'] = $this->contributionRepository->getValueContribution($id);

        // check user had join campaign
        $this->dataView['userCampaign'] = $this->campaignRepository->checkUserCampaign([
            'user_id' => auth()->id(),
            'campaign_id' => $id,
        ]);

        //get list suggested campaigns
        $this->dataView['nearestCampaigns'] = $this->campaignRepository->getSuggestNearestCampaigns($this->dataView['campaign']);
        $this->dataView['hotestCampaigns'] = $this->campaignRepository->getSuggestHotestCampaigns($this->dataView['campaign']);
        $this->dataView['relatedCampaigns'] = $this->campaignRepository->getRelatedCampaign($this->dataView['campaign']);

        // get list members of campaign
        $this->dataView['campaignChat'] = $this->dataView['campaign'];
        $this->dataView['members'] = $this->campaignRepository->getMembers($id);
        $this->dataView['averageRanking'] = $this->ratingRepository->averageRatingCampaign($this->dataView['campaign']->id);
        $this->dataView['ratingChart'] = $this->ratingRepository->getRatingChart($id);
        $this->dataView['averageRankingUser'] = $this->ratingRepository->averageRatingUser($this->dataView['campaign']->owner->user_id);
        $this->dataView['contributionConfirmed'] = $this->contributionRepository->getUserContributionConfirmed($id);
        $this->dataView['contributionUnConfirmed'] = $this->contributionRepository->getUserContributionUnConfirmed($id);
        $this->dataView['userRatings'] = $this->ratingRepository->listUserRating($this->dataView['campaign']->owner->user_id);
        $groupId = $this->groupRepository->getGroupIdByCampaignId($id);

        if ($groupId) {
            $this->dataView['messages'] = $this->messageRepository->getMessagesByGroupId($groupId);
        }

        $this->dataView['groupName'] = $this->dataView['campaign']->name;

        return view('campaign.show', $this->dataView);
    }

    public function joinOrLeaveCampaign(Request $request)
    {
        if ($request->ajax()) {
            $inputs = $request->only([
                'campaign_id',
            ]);

            $inputs['user_id'] = auth()->id();

            $result = $this->campaignRepository->joinOrLeaveCampaign($inputs);

            return response()->json($result);
        }
    }

    public function approveOrRemove(Request $request)
    {
        if ($request->ajax()) {
            $inputs = $request->only([
                'campaign_id',
                'user_id',
            ]);

            $result = $this->campaignRepository->approveOrRemove($inputs);

            return response()->json($result);
        }
    }

    public function activeOrCloseCampaign(Request $request)
    {
        if ($request->ajax()) {
            $inputs = $request->only([
                'campaign_id',
            ]);

            $result = $this->campaignRepository->activeOrCloseCampaign($inputs);

            return response()->json($result);
        }
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), $this->campaign->ruleImage);

        if ($validator->fails()) {
            $message = implode(' ', $validator->errors()->all());

            return view('layouts.upload', [
                'CKEditorFuncNum' => $request->CKEditorFuncNum,
                'data' => [
                    'url' => '',
                    'message' => $message,
                ],
            ]);
        }

        try {
            $image = $this->campaignRepository->uploadImageCampaign($request->file('upload'));

            return view('layouts.upload', [
                'CKEditorFuncNum' => $request->CKEditorFuncNum,
                'data' => [
                    'url' => $image,
                    'message' => trans('campaign.upload_image_success'),
                ],
            ]);
        } catch (\Exception $ex) {
            return [
                'status' => false,
                'message' => trans('campaign.upload_image_error') . $ex->getMessage(),
            ];
        }
    }

    public function searchCampaign(Request $request)
    {
        $result = $this->campaignRepository->searchCampaign($request->get('q'));

        return response()->json($result);
    }

    public function edit($id)
    {
        $this->dataView['campaign'] = $this->campaignRepository->find($id);

        if (!$this->dataView['campaign']
            || $this->dataView['campaign']->owner->user_id != auth()->id()) {
            return abort(404);
        }

        $tags = json_encode(Tag::get(['name']));
        $this->dataView['tags'] = $tags;

        $validateMessage = trans('campaign.validate');
        unset($validateMessage['image']);
        $this->dataView['validateMessage'] = json_encode($validateMessage);

        return view('campaign.edit', $this->dataView);
    }

    public function filterCampaign(Request $request)
    {
         if ($request->ajax()){
            $inputs = $request->only([
                'filter_follow',
            ]);

            $campaigns = $this->campaignRepository->filterCampaign($inputs['filter_follow']);

            return response()->json([
                'success' => true,
                'html' => view('campaign.list_campaign_layouts', [
                    'campaigns' => $campaigns
                ])->render(),
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function campaignWithTags($tags)
    {
        $this->dataView['campaigns'] = $this->campaignRepository->getCampaignByTagsName($tags)->paginate(config('constants.PAGINATE'));

        if (!$this->dataView['campaigns']->count()) {
            return abort(404);
        }

        $this->dataView['users'] = $this->userRepository->getUserByRating();
        $this->dataView['tags'] = $tags;
        $this->dataView['countTags'] = $this->campaignRepository->countCampaignsByTag($tags);
        $this->dataView['popularTags'] = $this->campaignRepository->getPopularTags();

        return view('campaign.campaign_with_tags', $this->dataView);
    }

    public function allTags()
    {
        $this->dataView['tags'] = $this->campaignRepository->getAllTags();

        return view('campaign.tags', $this->dataView);
    }
}
