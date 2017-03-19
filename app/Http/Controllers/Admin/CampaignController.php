<?php

namespace App\Http\Controllers\Admin;

use App\Filter\CampaignsFilter;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Repositories\Campaign\CampaignRepositoryInterface;
use Illuminate\Http\Request;
use Validator;
use DB;
use Mail;

class CampaignController extends Controller
{
    protected $campaignRepository;

    protected $campaign;

    public function __construct(Campaign $campaign, CampaignRepositoryInterface $campaignRepository)
    {
        $this->campaign = $campaign;
        $this->campaignRepository = $campaignRepository;
    }

    public function index(CampaignsFilter $filters)
    {
        $campaigns =  $this->campaign->filter($filters)->paginate(config('settings.number_of_record_campaign'));
        $input = $filters->input();

        $linkFilter = $campaigns->appends($input)->links();

        return view('admin.campaign.index', compact('campaigns', 'input', 'linkFilter'));
    }

    public function create()
    {
        return redirect()->action('CampaignController@create');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $campaign = $this->campaign->findOrFail($id);

            $email = $campaign->owner->user->email;

            $campaignInfo = [
                'name' => $campaign->name,
                'description' => $campaign->description,
                'address' => $campaign->address,
            ];

            if ($campaign->image) {
                $campaign->image()->delete();
            }

            if ($campaign->contributions) {
                $campaign->contributions()->delete();
            }

            if ($campaign->owner) {
                $campaign->owner()->delete();
            }

            if ($campaign->userCampaigns) {
                $campaign->userCampaigns()->delete();
            }

            if ($campaign->categoryCampaign) {
                $campaign->categoryCampaign()->delete();
            }

            if ($campaign->categories) {
                $campaign->categories()->delete();
            }

            if ($campaign->group) {
                $campaign->group()->delete();
            }

            if ($campaign->events) {
                $campaign->events()->delete();
            }

            $campaign->delete();

            if ($email) {
                Mail::queue('email.delete_campaign', [
                'campaignInfo' => $campaignInfo,
                ], function ($message) use ($email) {
                    $message->to($email)->subject(trans('email.delete_campaign.subject'));
                });
            }

            DB::commit();

            return redirect()->action('Admin\CampaignController@index')->with(['message'=> trans('campaign.message.delete_success')]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->action('Admin\CampaignController@index')->with(['message'=> trans('campaign.message.delete_fail')]);
        }
    }
}
