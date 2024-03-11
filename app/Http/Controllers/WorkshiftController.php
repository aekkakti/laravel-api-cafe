<?php

namespace App\Http\Controllers;

use App\Models\Workshift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkshiftController extends Controller
{

    public function createWorkshift(Request $request){
        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required'
        ]);

        if ($validator -> fails()){
            return response()->json(['code' => 422, 'errors' => $validator->errors()]);
        }

        $workshift = Workshift::create([
            'start' => $request->start,
            'end' => $request->end
        ]);
        $workshift_id = $workshift->id;
        $workshift_start = $workshift->start;
        $workshift_end = $workshift->end;
        if ($workshift_start < now() && $workshift_start < $workshift_end){
            return response()->json(['id' => $workshift_id, $workshift]);
        }
        else {
            return response()->json(['code' => 422, 'error' => 'Дата указана некорректно']);
        }
    }

    public function openWorkshift(Request $request, Workshift $workshift){
        $active = $request->active;
        if ($active === true) {
            $workshift->update([
                'active' => $request->active,
            ]);
            return response()->json([$workshift]);
        }
        else {
            return response()->json(['code' => 403, 'error' => 'Forbidden. There are open shifts!']);
        }
    }
}
