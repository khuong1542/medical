<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Cache;
use File;

class CacheHelper
{
    /**
     * Kiểm tra phần mở rộng của file
     */
    public static function getCacheData($name, $data)
    {
        if(!Cache::get($name)){
            $path_folder = storage_path('app/listCacheName.json');
            if(file_exists($path_folder)){
                $content = json_decode(file_get_contents($path_folder), true);
                array_push($content, $name);
            }else{
                $content = array($name);
            }
            File::put($path_folder, json_encode((object)$content));
            Cache::forever($name, $data);
            return $data;
        }else{
            return Cache::get($name);
        }
    }
    public static function getViewCacheData($name, $data)
    {
        if(!Cache::get($name)){
            Cache::forever($name, $data);
            $path_folder = storage_path('app/listCacheName.json');
            if(file_exists($path_folder)){
                $content = json_decode(file_get_contents($path_folder), true);
                array_push($content, $name);
            }else{
                $content = array($name);
            }
            File::put($path_folder, json_encode($content));
            return $data;
        }else{
            return Cache::get($name);
        }
    }
}
