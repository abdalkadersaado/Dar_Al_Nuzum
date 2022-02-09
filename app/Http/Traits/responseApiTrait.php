<?php

namespace App\Http\Traits;

trait responseApiTrait{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function responseSuccess($msg =""){

        return response()->json([
            'message'=>$msg,
            'status'=>200
        ]);

    }

    public function responseError($msg , $status = 404 ){

        return response()->json([
            'message'=>$msg,
            'status'=>$status
        ]);

    }

    public function responseData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => 200,
            'msg' => __('Data Selected Successfully'),
            $key => $value
        ]);
    }

}
