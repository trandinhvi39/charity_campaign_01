<?php
namespace App\Repositories\Contribution;

use App\Models\Action;
use App\Models\Category;
use App\Models\CategoryContribution;
use App\Models\Contribution;
use App\Repositories\BaseRepository;
use App\Repositories\Contribution\ContributionRepositoryInterface;
use Auth;
use DB;
use Illuminate\Container\Container;

class ContributionRepository extends BaseRepository implements ContributionRepositoryInterface
{

    protected $container;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function model()
    {
        return Contribution::class;
    }

    public function createContribution($params = [])
    {
        if (empty($params)) {
            return false;
        }

        $inputs = [];
        foreach ($params['amount'] as $key => $amount) {
            $inputs[] = [
                'category_id' => $key,
                'amount' => (int) $amount != 0 ? $amount : 0,
            ];
        }

        DB::beginTransaction();
        try {
            $this->model->create([
                'campaign_id' => $params['campaign_id'],
                'user_id' => auth()->id() ?: null,
                'name' => isset($params['name']) ? $params['name'] : null,
                'email' => isset($params['email']) ? $params['email'] : null,
                'description' => $params['description'],
            ])->categoryContributions()->createMany($inputs);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function getValueContribution($id)
    {
        if (!$id) {
            return false;
        }

        $categoryContributions = CategoryContribution::whereHas('contribution', function ($query) use ($id) {
            $query->where('campaign_id', $id)
                ->where('status', config('constants.ACTIVATED'));
        })
            ->with('category')
            ->selectRaw('category_id, sum(amount) as amount')
            ->groupBy('category_id')
            ->get();

        $categories = Category::where('campaign_id', $id)->get();

        $data = [];
        if (!count($categoryContributions)) {
            foreach ($categories as $category) {
                $data[] = [
                    'name' => $category->name,
                    'value' => config('constants.EMPTY_DATA'),
                    'progress' => config('constants.EMPTY_DATA'),
                    'goal' => $category->goal,
                    'unit' => $category->unit,
                ];
            }

            return $data;
        }

        foreach ($categoryContributions as $categoryContribution) {
            $data[] = [
                'name' => $categoryContribution->category->name,
                'value' => $categoryContribution->amount,
                'progress' => round(
                    $categoryContribution->amount / $categoryContribution->category->goal * 100,
                    config('constants.ROUND_CHART')
                ),
                'goal' => $categoryContribution->category->goal,
                'unit' => $categoryContribution->category->unit,
            ];
        }

        return $data;
    }

    public function getAllCampaignContributions($campaignId)
    {
        if (!$campaignId) {
            return false;
        }

        return $this->model->where('campaign_id', $campaignId)
            ->with(['user', 'categoryContributions.category'])
            ->orderBy('id', 'desc');
    }

    public function confirmContribution($id)
    {
        if (!$id) {
            return false;
        }

        $contribution = $this->model->find($id);
        if (!($contribution)) {
            return false;
        }

        DB::beginTransaction();
        try {
            $contribution->status = config('constants.ACTIVATED') - $contribution->status;
            $contribution->save();

            if ($contribution->status && $contribution->user_id) {
                $contribution->actions()->create([
                    'user_id' => $contribution->user_id,
                    'action_type' => config('constants.ACTION.CONTRIBUTE'),
                    'time' => time(),
                ]);
                DB::commit();

                return $contribution;
            }

            $action = Action::where('user_id', $contribution->user_id)
                ->where('action_type', config('constants.ACTION.CONTRIBUTE'))
                ->where('actionable_id', $contribution->id)
                ->first();

            if ($action) {
                $action->delete();
            }

            DB::commit();

            return $contribution;
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function getUserContributionConfirmed($campaignId)
    {
        if (empty($campaignId)) {
            return false;
        }

        return $this->model->where('campaign_id', $campaignId)
            ->where('status', config('constants.ACTIVATED'))
            ->with('user', 'categoryContributions')
            ->latest()
            ->get();
    }

    public function getUserContributionUnConfirmed($campaignId)
    {
        if (empty($campaignId)) {
            return false;
        }

        return $this->model->where('campaign_id', $campaignId)
            ->where('status', config('constants.NOT_ACTIVE'))
            ->with('user', 'categoryContributions')
            ->latest()
            ->get();
    }

    public function getInteractive()
    {
        return $this->model->groupBy('user_id')->count();
    }
}
