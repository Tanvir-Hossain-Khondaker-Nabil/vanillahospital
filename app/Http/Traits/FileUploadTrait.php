<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/18/2019
 * Time: 1:08 PM
 */


namespace App\Http\Traits;

use Faker\Provider\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


trait FileUploadTrait
{

    /**
     * @param $getPhoto | Photo File
     * @param $path | Storage Path Name
     * @param null $options | Resizing Size Width or Height
     * @return bool|string  | Store File Name Or Null
     */
    public function fileUpload($getPhoto, $path, $options = null, $fileSupport = ['jpg', 'jpeg'], $reSize = true )
    {
        $image_name = str_random(10).date('Ymdhis');

        if (!is_null($options) && is_array($options)) {
            if (isset($options['name'])) $image_name = $options['name'];
        }

        $photo = Image::make($getPhoto);

        List($width, $height) = getimagesize($getPhoto);
        $size['width'] =  $width;
        $size['height'] = $height;

        if($reSize)
            $photo = $this->imageReSize($photo, $options, $size);

        $photo =  $photo->stream()->__toString();


        $ext = strtolower($getPhoto->getClientOriginalExtension());


        if(in_array($ext, $fileSupport)) {

            $image_full_name = $image_name . '.' . $ext;

            $success = Storage::disk('public')->put($path."/".$image_full_name, $photo);

            if(!$success) $image_full_name = '';
        } else {
            return '';
        }

        return $image_full_name;
    }

    /**
     * @param $photo
     * @param $options
     * @return mixed
     */
    private function imageReSize($photo, $options, $size)
    {
        $ratio = $size['width']/$size['height'];

        if( $ratio < 1 ){
            if( $size['width'] < 300 ) {
                $options['height'] = $size['height'];
                $options['width'] = $size['height'] * $ratio;
            }
        } elseif( $ratio == 1 ) {
            if( $size['width'] < 300 ){
                $options['width'] = $size['width'];
                $options['height'] = $size['height'];
            }
        } else {
            if( $size['width'] < 300 ) {
                $options['height'] = $size['width'] * $ratio;
                $options['width'] = $size['width'];
            }
        }

        if(is_null($options) || !is_array($options)){
            $options['width'] = 300;
            $options['height'] = 300*$ratio;
        } else {
            $options['width'] = isset($options['width']) ? $options['width'] : null;
            $options['height'] = isset($options['height']) ? $options['height'] : null;
        }

        return $photo->resize($options['width'], $options['height'], function ($constraint) {
            $constraint->aspectRatio();
        });
    }

    /**
     * @param $getPhoto | Photo/File
     * @param $path | Storage Path Name
     * @param null $options | Resizing Size Width or Height
     * @return Array of File Details
     */
    public function fileUploadWithDetails($getfile, $path, $options = null)
    {
        $name = str_random(10).date('Ymdhis');

        $data['extension'] = $ext = strtolower($getfile->getClientOriginalExtension());


        if(in_array($ext, ['jpg', 'png', 'jpeg', 'gif']))
        {
            if (!is_null($options) && is_array($options)) {
                if (isset($options['name'])) $name = $options['name'];
            }

            $photo = Image::make($getfile);

            List($width, $height) = getimagesize($getfile);
            $size['width'] =  $width;
            $size['height'] = $height;

            $file = $this->imageReSize($photo, $options, $size);

            $file =  $file->stream()->__toString();
            $data['file_type'] = 'Image';

        }else{
            $data['file_type'] = $ext;
            $file = $getfile;
        }

        if(in_array($ext, ['jpg', 'png', 'jpeg', 'gif'])) {
            $data['file_name'] = $full_name = $name . '.' . $ext;
            $data['file_store_path'] = $path."/Images";

            $success = Storage::disk('public')->put($data['file_store_path']."/".$full_name, $file);

            if(!$success) $data['file_name'] = $full_name = '';
        } else {

            $destination_path = storage_path("app/public/".$path);
            $data['file_name'] = $full_name = $name . '.' . $ext;
            $data['file_store_path'] = $path;

            $success = $file->move($destination_path, $full_name);

            if(!$success) $data['file_name'] = $full_name = '';
        }

        return $data;
    }

    /**
     * @param $fileName
     * @param $path
     * @return bool
     */
    public function fileDestroy($fileName, $path)
    {
        if (is_null($fileName)) return true;

        return Storage::disk('public')->delete($path . $fileName);
    }
}
