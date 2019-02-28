<?php

namespace App\Resources;


use Illuminate\Database\Eloquent\Collection;

abstract class Resource
{
    public $resource;
    public $includes = [];

    abstract public function respond($resource);

    public function respondWithInstance($resource)
    {
        $resArr = $resource->toArray();
        $includesData = collect(array_keys($this->includes))->flatMap(function ($key) use ($resArr){
            return [$key => array_key_exists($key, $resArr)];
        })->filter()->flatMap(function ($i, $key) use ($resArr, $resource){
            $includesRes = $this->includes[$key];
            $includesCollection = app()->makeWith($includesRes,[
                $key => $resource[$key]
            ]);
            return [$key => $includesCollection->toArray()];
        })->toArray();
        return array_merge($this->respond($resource), $includesData);
    }

    public function respondWithCollection($resource)
    {
        return $resource->map(function ($instance){
            return $this->respondWithInstance($instance);
        })->toArray();
    }

    public function toArray() : array
    {
        if ($this->resource instanceof Collection){
            return $this->respondWithCollection($this->resource);
        }
        return $this->respondWithInstance($this->resource);
    }
}