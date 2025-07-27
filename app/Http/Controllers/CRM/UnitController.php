<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Unit::query();

            // Apply filters
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('unit', 'like', "%{$search}%");
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $data['units'] = $query->paginate(10);

            return view('CRM.Unit.index', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function store(UnitRequest $request)
    {
        try {
            $unit = new Unit();
            $unit->unit = $request->unit;
            $unit->status = $request->status ? 1 : 0;
            $unit->user_id = Auth::user()->_id;
            if ($unit->save()) {
                return $this->successMsg('Unit created successfully');
            }
            return $this->failMsg('Unit not created');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $record = Unit::findOrFail($id);
            return $this->successMsg('Unit fetched successfully', $record);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function update(UnitRequest $request, $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->unit = $request->unit;
            $unit->status = $request->status ? 1 : 0;

            if ($unit->save()) {
                return $this->successMsg('Unit updated successfully');
            }
            return $this->failMsg('Unit not updated');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:units,_id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }

            $unit = Unit::findOrFail($request->id);
            $unit->status = (int) $request->status;

            if ($unit->save()) {
                return $this->successMsg('Status updated successfully');
            }
            return $this->failMsg('Status not updated');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);

            if ($unit->delete()) {
                return $this->successMsg('Unit deleted successfully');
            }
            return $this->failMsg('Unit not deleted');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
} 