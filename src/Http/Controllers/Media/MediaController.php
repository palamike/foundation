<?php

namespace Palamike\Foundation\Http\Controllers\Media;

use Palamike\Foundation\Models\Media\Media;
use Palamike\Foundation\Support\Facades\MediaService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public $rules = [
        'upload_file' => 'required|image|max:{max_file_size}'
    ];

    protected $permissions = [];

    public function upload(Request $request){
        $this->rules['upload_file'] = str_replace('{max_file_size}',setting('media.max.size')*1024,$this->rules['upload_file']);
        $req = $request->all();

        $validator = Validator::make($req,$this->rules,$this->getValidationMessages('media.file'));

        $validator->after(function($validator) use ($request) {
            if(!$request->hasFile('upload_file')){
                $validator->errors()->add('upload_file', trans('media.file.field.validation.upload_file.required'));
            }//if
            else if(!$request->file('upload_file')->isValid()){
                $validator->errors()->add('upload_file', trans('media.file.field.validation.upload_file.valid'));
            }
        });

        if ($validator->fails()) {
            return response($validator->errors(),422);
        }

        $file = $request->file('upload_file');

        try{
            $media = MediaService::upload($file,$req['category']);
            $message = trans('media.file.upload.success',['file' => $media->file_name]);
            $response = [
                'info' => $media,
                'message' => $message
            ];

            return response()->json($response);
        }//try
        catch(\Exception $e){
            $message = trans('media.file.upload.error');
            return response($message,409);
        }//catch


    }//public function upload


    public function delete(Media $media){
        try{
            $response = [];
            $response['info'] = $media;

            MediaService::delete($media);

            $response['message'] = trans('media.file.delete.success',['file' => $media->file_name]);
            return response()->json($response);
        }
        catch(\Exception $e){
            $message = trans('media.file.delete.error');
            return response($message,409);
        }
    }//public function delete
}
