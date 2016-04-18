<?php

namespace Palamike\Foundation\Http\Controllers\Auth;

use Palamike\Foundation\Models\Auth\PermissionGroup;
use Palamike\Foundation\Models\Auth\Role;
use Palamike\Foundation\Models\Auth\User;
use Illuminate\Http\Request;
use Palamike\Foundation\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public $rules = [
        'name' => 'required|max:255|unique:roles,name',
        'label' => 'required|max:255',
        'redirect' => 'required|max:255'
    ];

    protected $permissions = [
        'query' => 'role_view',
        'index' => 'role_view',
        'store' => 'role_add',
        'update' => 'role_edit',
        'destroy' => 'role_delete',
    ];

    public function __construct()
    {
        $this->checkPermission();
    }


    public function query(Request $request){

        $query = Role::with('permissions');
        $req = $request->all();

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
            $field = 'name';
            $sorting = 'asc';
            $query->orderBy($field,$sorting);
        }

        return $query->paginate(setting('pagination.limit'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.role.index',['permissions' => $this->mapPermissions()]);
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
                'groups' => PermissionGroup::with('permissions')->get()
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
    public function store(Request $request,Role $role = null)
    {

        if($request->has('id')){
            $id = $request->get('id');
            $this->rules['name'] .= ','.$id;
        }//if

        $data = $request->all();

        $validator = Validator::make($data, $this->rules);

        $validator->after(function($validator) use ($request) {
            if(!$request->has('permissions')){
                $validator->errors()->add('permissions', trans('user.role.field.validation.permissions.required'));
            }
        });

        if ($validator->fails()) {
            return response($validator->errors(),422);
        }


        $response = [];

        try{
            $this->beginTransactions();

            $this->stamp($data);

            if($request->has('id')){
                $role->update($data);
                $response['message'] = trans('common.success.edit',['object' => trans('user.role'), 'id' => $role->id]);
            }//if edit
            else{
                $role = Role::create($data);
                $response['message'] = trans('common.success.create',['object' => trans('user.user'), 'id' => $role->id]);
            }//create

            //later need to validate permission check use after validation hook and sync permissions
            if($request->has('permissions')){
                $role->permissions()->sync($data['permissions']);
            }//if

            $this->commit();

            $response['info'] = $role->load('permissions');
            $response['list'] = $this->query($request);

            return response()->json($response);
        }//try
        catch(\Exception $e){

            $this->rollBack();

            if($request->has('id')){
                $message = trans('common.error.can.not.edit',['object' => trans('user.role'),'id' => $role->id ]);
            }//if edit
            else{
                $message = trans('common.error.can.not.create',['object' => trans('user.role'),'id' => $role->id ]);
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
    public function edit(Role $role)
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
    public function update(Request $request, Role $role)
    {
        return $this->store($request,$role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Role $role)
    {
        $response = [];
        $response['info'] = $role;

        try{
            $role->delete();
            $response['message'] = trans('common.success.delete',['object' => trans('user.role'),'id' => $role->id ]);
            $response['list'] = $this->query($request);
            return response()->json($response);
        }//try
        catch(\Exception $e){
            $message = trans('common.error.can.not.delete',['object' => trans('user.role'),'id' => $role->id ]);
            Log::error($message);
            Log::error($e);
            return response($message,409);
        }//catch
    }
}
