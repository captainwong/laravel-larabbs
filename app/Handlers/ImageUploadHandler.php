<?php

namespace App\Handlers;
Use Image;

class ImageUploadHandler 
{
    protected $allowed_ext = ['jpeg', 'jpg', 'bmp', 'png', 'gif'];

    public function save($file, $folder, $file_prefix, $max_width = false){
        // uploads/images/avatars/201809/26
        $folder_name = "uploads/images/$folder/" . date('Ym/d', time());

        // /home/vagrant/code/larabbs/public/uploads/images/avatars/201809/26
        $upload_path = public_path() . '/' . $folder_name;

        // assgin png ext if file has no ext
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        if(!in_array($extension, $this->allowed_ext)){
            return false;
        }

        $filename = $file_prefix . '_' .time() . '_' . str_random(10) . '.' . $extension;

        $file->move($upload_path, $filename);

        if($max_width && $extension != 'gif'){
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width){
        $image = Image::make($file_path);
        $image->resize($max_width, null, function($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $image->save();
    }
}