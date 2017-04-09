<?php
namespace App\Repositories\Comment;

use Auth;
use App\Models\Comment;
use App\Repositories\BaseRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use DB;
use Illuminate\Container\Container;
use App\Models\Campaign;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{

    protected $container;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    function model()
    {
        return Comment::class;
    }

    public function createComment($params = [])
    {
        if (empty($params)) {
            return false;
        }

        DB::beginTransaction();
        try {
            $currentCampaign = Campaign::find($params['campaign_id']);

            if (!$currentCampaign) {
                return false;
            }

            $currentCampaign->actions()->create([
                'user_id' => auth()->id(),
                'action_type' => config('constants.ACTION.COMMENT_CAMPAIGN'),
                'time' => time(),
            ]);

            $comment = $this->model->create([
                'name' => isset($params['name']) ? $params['name'] : null,
                'email' => isset($params['email']) ? $params['email'] : null,
                'user_id' => auth()->id(),
                'campaign_id' => $params['campaign_id'],
                'text' => $params['text'],
            ]);

            DB::commit();

            return $comment;
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

        return $this->model->with('user')->find($id);
    }
}
