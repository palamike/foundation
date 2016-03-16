<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/24/2016 AD
 * Time: 5:24 PM
 */

namespace Palamike\Foundation\Services\Media;


use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Palamike\Foundation\Models\Media\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaService
{
    public function __construct(){
        $dirName = $this->getTodayAbsolutePath().DIRECTORY_SEPARATOR.'thumb';
        if(!File::exists($dirName)) {
            File::makeDirectory($dirName);
        }//if
    }//public function __construct

    /**
     * @param $file UploadedFile
     * @param $category string
     * @param $meta array
     */
    public function upload($file,$category = 'uploads',$meta = null){
        $path = $this->getTodayAbsolutePath();

        try{

            $fileName = $file->getClientOriginalName();
            $originalFile = $path.DIRECTORY_SEPARATOR.$fileName;

            if(file_exists($originalFile)){
                $extension = $file->getClientOriginalExtension();
                $dot_pos = strrpos($fileName,'.');
                $fileName = substr($fileName,0,$dot_pos).'_'.rand(1000,9999).'.'.$extension;
            }//if

            $originalFile = $path.DIRECTORY_SEPARATOR.$fileName;
            $thumbFile = $path.DIRECTORY_SEPARATOR.'thumb'.DIRECTORY_SEPARATOR.$fileName;
            $clientExtension = $file->guessClientExtension();
            $mime = $file->getMimeType();

            $file->move($path,$fileName);

            $img = Image::make($originalFile);
            $width = setting('media.thumb.width');
            $height = setting('media.thumb.height');
            $img->fit($width,$height);
            $img->save($thumbFile);

            $media = Media::create([
                'category' => $category,
                'file_name' => $fileName,
                'extension' => $clientExtension,
                'mime' => $mime,
                'path' => $path.DIRECTORY_SEPARATOR.$fileName,
                'web_path' => $this->getTodayWebPath()."/$fileName",
                'thumb_path' => $this->getTodayThumbPath()."/$fileName",
                'meta_data' => $meta,
                'created_by' => user()->id
            ]);

            return $media;
        }
        catch(\Exception $e){
            Log::error('Can not upload the media file');
            Log::error($e);
            throw $e;
        }//catch

    }//public function upload

    public function getTodayRelativePath(){
        $dt = Carbon::now()->toDateString();
        return 'media'.DIRECTORY_SEPARATOR.$dt;
    }

    public function getTodayAbsolutePath(){
        return public_path('uploads'.DIRECTORY_SEPARATOR.$this->getTodayRelativePath());
    }

    public function getTodayWebPath(){
        $dt = Carbon::now()->toDateString();
        return 'uploads'.'/'.'media'.'/'.$dt;
    }

    public function getTodayThumbPath(){
        $dt = Carbon::now()->toDateString();
        return 'uploads'.'/'.'media'.'/'.$dt.'/thumb';
    }

    public function delete(Media $media){

        if($media->category == 'system'){
            return $media;
        }//if

        $path = $media->path;
        $thumbPath = str_replace($media->file_name,'thumb'.DIRECTORY_SEPARATOR.$media->file_name,$media->path);

        try{
            File::delete([$path,$thumbPath]);
            $media->delete();

            return $media;
        }
        catch(\Exception $e){
            Log::error('Can not delete the media file');
            Log::error($e);
            throw $e;
        }//catch
    }//public function delete
}