<?php

namespace App\Http\Controllers\Setting;

use App\Models\System\SettingGroup;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Support\Facades\SettingService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{

    protected $rules = [];

    public function __construct(){
        $this->checkPermission();
    }

    public function edit($name){

        $group = SettingGroup::byName($name)->firstOrFail();

        $settings = $group->settings;
        $settings = $this->transforms($settings);

        return response()->json($settings);
    }

    public function index($name){
        $viewName = 'setting.'.$name;
        
        if(!view()->exists($viewName)){
            $viewName = 'foundation::'.$viewName;
        }
        
        return view($viewName,['permissions' => $this->mapPermissions()]);
    }

    public function update($name){
        $data = Request::all();
        $group = SettingGroup::with('settings')->byName($name)->firstOrFail();
        $settings = $group->settings;
        $rules = $this->extractValidationRules($settings);

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            return response($validator->errors(),422);
        }

        try{
            $this->beginTransactions();

            foreach($settings as $setting){

                $settingName = dot_dash($setting->name);

                if(array_key_exists($settingName,$data)){
                    if($this->castType($setting->type,$data[$settingName]) != $this->castType($setting->type,$setting->value)){
                        $setting->value = (string) $data[$settingName];
                        $setting->save();
                    }
                }
            }//foreach

            $this->commit();

            SettingService::clearCache();

            $response = [];
            $response['message'] = trans('setting.group.edit.success',['object' => trans('setting.group.'.$name) ]);
            $response['info'] = $data;

            return response()->json($response);

        }//try
        catch(\Exception $e){
            $this->rollBack();
            $message = trans('setting.group.edit.error',['object' => trans('setting.group.'.$name) ]);
            Log::error($message);
            Log::error($e);
            return response($message,409);
        }//catch

    }

    public function extractValidationRules($settings){
        $rules = [];
        foreach($settings as $setting){
            $rules[dot_dash($setting->name)] = $setting->validation;
        }//foreach

        return $rules;
    }

    public function transforms($settings){
        $transforms = [];

        foreach($settings as $setting){
            $setting['name'] = dot_dash($setting['name']);
            $setting['label'] = trans($setting['label']);
            $setting['description'] = trans($setting['description']);
            $setting['value'] = $this->castType($setting['type'],$setting['value']);
            $setting['choices'] = $this->extractChoice($setting['choices'],$setting['config']);

            $transforms[$setting['name']] = $setting;
        }

        return $transforms;
    }

    public function castType($type,$value){
        switch($type){
            case 'boolean' : return (boolean) $value;
            case 'integer' : return (integer) $value;
            case 'string' :
            default : return $value;
        }
    }//public

    public function extractChoice($choiceText,$config = null){

        if(empty($choiceText)){
            return null;
        }//if

        $result = [];

        if($choiceText == 'fetch'){
            if(empty($config)){
                throw new \Exception('invalid argument supply for extract choice');
            }//if

            $className = $config['class'];
            $keyField = $config['key_field'];
            $labelField = $config['label_field'];
            $rm = new \ReflectionMethod($className,'all');
            $collection = $rm->invoke(null);
            $result = $collection->pluck($labelField,$keyField);
        }//if
        else{
            $choices = explode(',',$choiceText);
            foreach($choices as $choice){
                list($key,$value) = explode(':',$choice);
                $result[$key] = $this->choiceTransform($value);
            }//foreach
        }//else

        return $result;
    }

    /**
     * if anyone want to transform the choice value such as translate override this function
     * @param $value
     * @return mixed
     */
    public function choiceTransform($value){
        return $value;
    }
}
