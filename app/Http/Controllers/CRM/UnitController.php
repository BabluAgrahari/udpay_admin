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
           

        $data['title'] = 'Unit';    
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

    public function datatable(Request $request)
    {
        $query = Unit::query();

        // Filtering
        if ($request->search) {
            $search = $request->search;
            $query->where('unit', 'like', "%{$search}%");
        }
        if ($request->status || $request->status == 0 && $request->status !== null) {
            $query->where('status', (int)$request->status);
        }

        $total = $query->count();

        // Ordering
        $columns = $request->columns;
        if ($request->order && count($request->order)) {
            foreach ($request->order as $order) {
                $colIdx = $order['column'];
                $colName = $columns[$colIdx]['data'];
                $dir = $order['dir'];
                if ($colName !== 'index' && $colName !== 'actions') {
                    $query->orderBy($colName, $dir);
                }
            }
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $units = $query->skip($start)->take($length)->get();

        $data = $units->map(function ($unit, $key) use ($start) {
            $statusSwitch = '<div class="form-check form-switch">'
                . '<input type="checkbox" class="form-check-input status-switch" data-id="' . $unit->_id . '" ' . ($unit->status ? 'checked' : '') . '>'
                . '<label class="form-check-label" for="status' . $unit->_id . '"></label>'
                . '</div>';
            return [
                'index' => $start + $key + 1,
                '_id' => $unit->_id,
                'unit' => $unit->unit,
                'status' => $statusSwitch,
                'created' => $unit->dFormat($unit->created),
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }
} 