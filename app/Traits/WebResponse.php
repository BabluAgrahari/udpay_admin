<?php

namespace App\Traits;

trait WebResponse
{
    public function successMsg($msg = false, $array = array())
    {
        if (!$msg)
            return $this->defaultResponse();

        $response = [
            'status' => true,
            'msg' => $msg,
        ];

        if (!empty($array))
            $response['record'] = $array;

        return response()->json($response, 200);
    }


    public function failMsg($msg = false)
    {
        if (!$msg)
            return $this->defaultResponse();

        return response()->json([
            'status' => false,
            'msg' => $msg
        ], 400);
    }


    public function validationMsg($error)
    {
        if (empty($error))
            return $this->defaultResponse();

        return response()->json([
            'status' => false,
            'validation' => $error
        ], 422);
    }


    public function unauthorizedMsg($msg = false)
    {
        if (!$msg)
            return $this->defaultResponse();

        return response()->json([
            'status' => false,
            'msg' => $msg
        ], 400);
    }


    private function defaultResponse()
    {
        return response()->json([
            'status' => false,
            'msg' => 'Parameter is missing.'
        ], 200);
    }
}
