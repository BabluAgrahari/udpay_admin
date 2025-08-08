<?php

namespace App\Traits;


trait Response
{
    public function recordsRes($record)
    {
        if ($record->isEmpty())
            return $this->default();

        return response()->json([
            'status' => true,
            'count' => $record->count() ?? 0,
            'records' => $record
        ], 200);
    }

    public function recordRes($record)
    {
        if (empty($record))
            return $this->default();

        return response()->json([
            'status' => true,
            'record' => $record
        ], 200);
    }

    public function successRes($msg = false, $array = array())
    {
        if (!$msg)
            return $this->default();

        $response = [
            'status' => true,
            'msg' => $msg,
        ];

        if (!empty($array))
            $response['record'] = $array;

        return response()->json($response, 200);
    }


    public function failRes($msg = false)
    {
        if (!$msg)
            return $this->default();

        return response()->json([
            'status' => false,
            'msg' => $msg
        ], 200);
    }


    public function validationRes($error)
    {

        if (empty($error))
            return $this->default();

        $msg = [];
        foreach ($error->getMessages() as $field => $messages) {
            foreach ($messages as $message) {
                $msg[] =  [$field => $message];
            }
        }

        return response()->json([
            'status' => false,
            'validation' => $msg
        ], 200);
    }


    public function unauthorizedRes($msg = false)
    {

        if (!$msg)
            return $this->default();

        return response()->json([
            'status' => false,
            'msg' => $msg
        ], 200);
    }

    public function notFoundRes()
    {

        return response()->json([
            'status' => false,
            'msg' => 'Not Found Any Records.'
        ], 200);
    }

    public function default()
    {

        return response()->json([
            'status' => false,
            'msg' => 'Not Found Any Records.'
        ], 200);
    }
}
