<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use APP\Slope;

class SlopeController extends Controller
{
    public function postSlope(Request $request) {
        if($request->session()->has('student_id')) {
            $input = $request->all();
            $record = new Slope;
            $record->student_id = $request->session->get('student_id');
            $record->mass = $input['mass'];
            $record->angle = $input['angle'];
            $record->length = $input['length'];

            $record.svae();

            return Response::json([
                'ok' => $message
            ], 200);

        }
        return Response::json([
            'the student session is not find' => $message
        ], 404);
    }
}
