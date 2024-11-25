<?php

use App\Models\StoredFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

if (!function_exists('uploadImgFile')) {
    function uploadImgFile($image, $path = [], $image_name = '', $resize = false, $need_thumb = false, mixed $user = false)
    {
        try {
            if (!empty($path)) {
                $imageExtension = $image->getClientOriginalExtension() ?? '';
                $image = Image::read($image);
                if ($resize) {
                    $image->resize(($resize['width'] ?? 400), ($resize['height'] ?? 400));
                }
                $imagePath = '';
                foreach ($path as $key => $value) {
                    $imagePath .= $value . '/';
                }
                if (!Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->makeDirectory($imagePath);
                }
                $imageFolderPath = $imagePath;
                if ($image_name == '') {
                    $image_name = uniqid('IMG_') . ($imageExtension == '' ? '.jpg' : '.' . $imageExtension);
                }
                $imagePath .= $image_name;
                $image->save('storage/' . $imagePath);
                if ($need_thumb) {
                    $thumbPath = $imageFolderPath . 'thumb';
                    if (!Storage::disk('public')->exists($thumbPath)) {
                        Storage::disk('public')->makeDirectory($thumbPath);
                    }
                    $thumbPath .= '/' . $image_name;
                    $image->resize(150, 150);
                    $image->save('storage/' . $thumbPath);
                }
                if ($user) {
                    delete_user_photo($user);
                    $user->photo = 'storage/' . $imagePath;
                    $user->save();
                }
                return [
                    'status' => 1,
                    'image_path' => 'storage/' . $imagePath ?? '',
                    'thumb_path' => 'storage/' . $thumbPath ?? '',
                ];
            }
        } catch (Exception $e) {
            return ['status' => 0, 'error' => $e];
        }
    }
}

if (!function_exists('delete_user_photo')) {
    function delete_user_photo($user)
    {
        if ($user->photo != '') {
            if (file_exists($user->photo)) {
                unlink($user->photo);
            }
            if (file_exists(explode(basename($user->photo), $user->photo)[0] . 'thumb/' . basename($user->photo))) {
                unlink(explode(basename($user->photo), $user->photo)[0] . 'thumb/' . basename($user->photo));
            }
            $user->photo = null;
            $user->save();
        }
        return true;
    }
}

if(!function_exists('upload_files')){
    function upload_files($files, $path = [], $dbStore = false){
        if(empty($files)){
            return false;
        }
        if(!is_array($files)){
            $files = [$files];
        }
        $uploadedFiles = [];
        if(!empty($path)){
            $filePath = '';
            foreach ($path as $key => $value) {
                $filePath .= $value.'/';
            }
            if(!Storage::disk('public')->exists($filePath)){
                Storage::disk('public')->makeDirectory($filePath);
            }
        }else{
            return false;
        }
        foreach ($files as $key => $value) {
            $filename = uniqid('File_'.date('Y_m_d_H_i_s')).'.'.$value->getClientOriginalExtension();
            if(Storage::disk('public')->put($filePath.$filename, file_get_contents($value))){
                $uploadedFiles[] = $filePath.$filename;
            }
        }
        if($dbStore){
            $data = [];
            foreach ($uploadedFiles as $key => $value) {
                $data[] = [
                    'path' => $value
                ];
            }
            StoredFile::insert($data);
        }
        return $uploadedFiles;
    }
}

function  get_app_key(){
    return '18a73d95bd0eb4dedf89';
}

function  get_app_secret(){
    return '9332708c29485da8dc90';
}

function  get_app_id(){
    return '1884211';
}

function  get_app_cluster(){
    return 'ap2';
}