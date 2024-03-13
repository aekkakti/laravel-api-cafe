<?php

namespace App\Http\Controllers;

use App\Models\ShiftWorker;
use App\Models\Users;
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
            'id' => $request->id,
            'start' => $request->start,
            'end' => $request->end
        ]);
        $workshift_id = $workshift->id;
        $workshift_start = $workshift->start;
        $workshift_end = $workshift->end;
        if ($workshift_start < now() && $workshift_start < $workshift_end){
            $workshift->makeHidden(['created_at', 'updated_at']);
            return response()->json(['id' => $workshift_id, $workshift]);
        }
        else {
            return response()->json(['code' => 422, 'error' => 'Дата указана некорректно']);
        }
    }

    public function openWorkshift(Workshift $workshift){
        $openedWorkshiftsCount = Workshift::where('active', 1)->count();
        if ($openedWorkshiftsCount === 0) {
            $workshift->active = 1;
            $workshift->save();
            return Workshift::select('id','start', 'end', 'active')->where('id', $workshift->id)->get();
        }
        else {
            return response()->json(['code' => 403, 'error' => 'Forbidden. There are open shifts!']);
        }
    }

    public function closeWorkshift(Workshift $workshift){
        if ($workshift->active === 1) {
            $workshift->active = 0;
            $workshift->save();
            return Workshift::select('id','start', 'end', 'active')->where('id', $workshift->id)->get();
        }
        else {
            return response()->json(['code' => 403, 'error' => 'Forbidden. The shift is already closed!']);
        }
    }

    public function addWorkerToWorkshift(Request $request, Workshift $workshift) {
        $shiftworker_id = Users::where('id', '=', $request->user_id)->first()->id;
        if (ShiftWorker::where('user_id', $shiftworker_id)) {
            return response()->json(['message' => '"Forbidden. The worker is already on shift!"']);
        }
        $on_shiftwork = ShiftWorker::create([
            'work_shift_id' => $workshift -> id,
            'user_id' => $shiftworker_id
        ]);

        return response()->json(['id_user' => $shiftworker_id, 'status' => 'added']);
    }
}
