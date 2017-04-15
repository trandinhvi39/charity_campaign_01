<?php
namespace App\Repositories\Note;

use App\Repositories\BaseRepository;
use App\Repositories\Note\NoteRepositoryInterface;
use Illuminate\Container\Container;
use App\Models\Note;

class NoteRepository extends BaseRepository implements NoteRepositoryInterface
{

    protected $container;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    function model()
    {
        return Note::class;
    }
}
