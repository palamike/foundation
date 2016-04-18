<?php

namespace Palamike\Foundation\Http\Controllers\Auth;

use Palamike\Foundation\Models\Auth\Role;
use Palamike\Foundation\Models\Auth\User;
use Palamike\Foundation\Models\Media\Media;
use Illuminate\Http\Request;
use Palamike\Foundation\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public $rules = [
        'name' => 'required|max:255',
        'username' => 'required|max:255|unique:users,username',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'sometimes|min:8|max:255|confirmed',
        'status' => 'required'
    ];

    protected $permissions = [
        'query' => 'user_view',
        'index' => 'user_view',
        'store' => 'user_add',
        'update' => 'user_edit',
        'destroy' => 'user_delete',
    ];

    public function __construct()
    {
        $this->checkPermission();
    }


    public function query(Request $request){

        $query = User::query()->getQuery();
        $req = $request->all();

        $query->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','role_user.role_id','=','roles.id')
            ->select('users.*','roles.id as role_id', 'roles.label as roles__label');

        if(!empty($req) && !empty($req['filter'])){
            $field = query_replace_dash($req['filter']['field']);
            $filter = '%'.$req['filter']['value'].'%';
            $query->where($field,'like',$filter);
        }//if

        if(!empty($req) && !empty($req['sort'])){
            $field = query_replace_dash($req['sort']['field']);
            $sorting = $req['sort']['value'] > 0 ? 'asc' : 'desc';
            $query->orderBy($field,$sorting);
        }//if
        else {
            $field = 'username';
            $sorting = 'asc';
            $query->orderBy($field,$sorting);
        }

        $paginate = $query->paginate(setting('pagination.limit'));
        $items = $paginate->items();

        $collection = collect($items);
        $ids = $collection->pluck('avatar_id');

        $medias = Media::whereIn('id',$ids)->get();
        $mediaMap = [];
        foreach($medias as $media){
            $mediaMap['_'.$media->id] = $media;
        }//foreach

        $newItems = [];
        foreach($items as $item){
            if(!empty($item->avatar_id)){
                if(array_key_exists('_'.$item->avatar_id,$mediaMap)){
                    $item->avatar = $mediaMap['_'.$item->avatar_id];
                }//if
            }//if

            array_push($newItems,$item);
        }//foreach

        $response = [
            'total' => $paginate->total(),
            'per_page' => $paginate->perPage(),
            'current_page' => $paginate->currentPage(),
            'last_page' => $paginate->lastPage(),
            'next_page_url' => $paginate->nextPageUrl(),
            'prev_page_url' => $paginate->previousPageUrl(),
            'data' => $newItems
        ];

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('auth.user.index',['permissions' => $this->mapPermissions()]);
    }

    /**
     * Return the FormInfo for any information relate to create the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = [
            'choices' => [
                'status' => [
                    'active' => trans('user.user.field.status.active'),
                    'inactive' => trans('user.user.field.status.inactive')
                ],
                'roles' => Role::all()->pluck('label','id')
            ],
            'defaults' => [
                'status' => 'inactive'
            ]
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user = null)
    {

        if($request->has('id')){
            $id = $request->get('id');
            $this->rules['username'] .= ','.$id;
            $this->rules['email'] .= ','.$id;
        }//if

        $this->validate($request,$this->rules,$this->getValidationMessages('user.user'));
        $response = [];

        try{
            $this->beginTransactions();

            $data = $request->all();

            if($request->has('password')){
                $data['password'] = Hash::make($data['password']);
            }//if
            else{
                unset($data['password']);
            }

            $this->stamp($data);

            if($request->has('id')){
                $user->update($data);
                $response['message'] = trans('common.success.edit',['object' => trans('user.user'), 'id' => $user->id]);
            }//if edit
            else{
                $user = User::create($data);
                $response['message'] = trans('common.success.create',['object' => trans('user.user'), 'id' => $user->id]);
            }//create

            if($request->has('role_id')){
                $user->roles()->sync([$data['role_id']]);
            }//if

            $this->commit();

            $response['info'] = $user;
            if($request->has('role_id')){
                $response['info']['role_id'] = $data['role_id'];
            }//if
            $response['list'] = $this->query($request);

            return response()->json($response);
        }//try
        catch(\Exception $e){

            $this->rollBack();

            if($request->has('id')){
                $message = trans('common.error.can.not.edit',['object' => trans('user.user'),'id' => $user->id ]);
            }//if edit
            else{
                $message = trans('common.error.can.not.create',['object' => trans('user.user'),'id' => $user->id ]);
            }//create
            Log::error($message);
            Log::error($e);
            return response($message,409);
        }//catch
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        return $this->store($request,$user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $response = [];
        $response['info'] = $user;

        try{
            $user->delete();
            $response['message'] = trans('common.success.delete',['object' => trans('user.user'),'id' => $user->id ]);
            $response['list'] = $this->query($request);
            return response()->json($response);
        }//try
        catch(\Exception $e){
            $message = trans('common.error.can.not.delete',['object' => trans('user.user'),'id' => $user->id ]);
            Log::error($message);
            Log::error($e);
            return response($message,409);
        }//catch
    }
}
