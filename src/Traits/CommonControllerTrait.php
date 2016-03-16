<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 3/16/2016 AD
 * Time: 7:25 PM
 */

namespace Palamike\Foundation;


use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

trait CommonControllerTrait {

    public $permisssions;

    public $default_sort_order = 'asc';

    public function stamp(&$data){

        $user = user();

        if(array_key_exists('id',$data) && !empty($data['id'])){
            $data['updated_at'] = Carbon::now();
            $data['updated_by'] = $user->id;
            $data['updated_by_name'] = $user->name;

        }//if
        else{
            $data['created_at'] = Carbon::now();
            $data['created_by'] = $user->id;
            $data['created_by_name'] = $user->name;
        }//else

    }

    public function mapPermissions(){

        $map = [];

        foreach($this->permissions as $action => $permission){
            $map[$action] = Gate::allows($permission);
        }

        return $map;
    }

    public function checkPermission(){

        if(!App::runningInConsole()){
            $actionName = Route::getCurrentRoute()->getActionName();
            $method = substr($actionName,strpos($actionName,'@') + 1);
            if(array_key_exists($method,$this->permissions)){
                if(Gate::denies($this->permissions[$method])){
                    abort(403,trans('common.error.403'));
                }//if
            }//if mapping exists require check permissions
        }//for console do not check
    }

    public function beginTransactions(){
        DB::beginTransaction();
    }

    public function rollBack(){
        DB::rollBack();
    }

    public function commit(){
        DB::commit();
    }

    public function getValidationMessages($prefix){
        $rules = $this->rules;

        $messages = [];

        foreach($rules as $field => $allRules){
            $validations = explode('|',$allRules);
            foreach($validations as $validation){
                if(strpos($validation,':') !== false){
                    $validation = substr($validation,0,strpos($validation,':'));
                }//if

                $messages[$field.'.'.$validation] = trans($prefix.'.field.validation.'.$field.'.'.$validation);
            }
        }//foreach

        return $messages;

    }

    /**
     *
     * Build the standard reusable query
     * @param $req
     * @param $query
     * @return mixed
     */
    public function buildQuery($req,&$query){
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
            $field = $this->default_sort_field;
            $sorting = $this->default_sort_order;
            $query->orderBy($field,$sorting);
        }

        return $query;
    }//protected function buildQuery


}