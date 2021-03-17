<?php
function ImageUpload($img)
{
    $image_path  = "app/public/images/covers";
    $img_height = 600;
    $img_width = 600;
    $img_name=time().'-'.$img->getClientOriginalName();
    Image::make($img)->resize($img_width, $img_height)->save(storage_path($image_path.'/'.$img_name));
    return "images/covers/" . $img_name;
    }

