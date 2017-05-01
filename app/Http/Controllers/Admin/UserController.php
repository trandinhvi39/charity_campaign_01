<?php

namespace App\Http\Controllers\Admin;

use App\Filter\UsersFilter;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Validator;
use DB;

class UserController extends Controller
{
    protected $userRepository;

    protected $user;

    public function __construct(User $user, UserRepositoryInterface $userRepository)
    {
        $this->user = $user;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersFilter $filters)
    {
        $users =  User::filter($filters)->where('role', config('settings.role.user'))->paginate(config('settings.number_of_record_user'));
        $input = $filters->input();

        $linkFilter = $users->appends($input)->links();

        return view('admin.user.index', compact('users', 'input', 'linkFilter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->user->rules);

        $user = $this->userRepository->register($request->all(), 1);

        if (!$user) {
            return redirect()->action('Admin\UserController@create')->with(['message'=> trans('user.message.create_fail')]);
        }

        return redirect()->action('Admin\UserController@index')
            ->with(['message' => trans('user.message.create_success')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $user = $this->user->findOrFail($id);

            return view('admin.user.edit', compact('user'));
        } catch (\Exception $e) {
            return redirect()->action('Admin\UserController@index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserEditRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $this->user->findOrFail($id);
        } catch (\Exception $e) {
            return abort(404);
        }

        $this->validate($request, $this->user->updateRules($id));
        $data = $request->all();

        $params = array_only($data, $this->user->getFillable());
        $params['password'] = bcrypt($data['password']);

        $user = $this->userRepository->updateProfile($params, $id);

        if (!$user) {
            return redirect()->action('Admin\UserController@index')->with(['message'=> trans('user.message.update_error')]);
        }

        return redirect()->action('Admin\UserController@index')->with(['message'=> trans('user.message.update_success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->is_block = true;
            $user->save();

            return redirect()->action('Admin\UserController@index')->with(['message'=> trans('user.message.block_success')]);
        } catch (\Exception $e) {
            return redirect()->action('Admin\UserController@index')->with(['message'=> trans('user.message.block_fail')]);
        }
    }
}
