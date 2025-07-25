<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\WebResponse;

class UnitController extends Controller
{
    use WebResponse;

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
            DB::beginTransaction();
            
            $unit = new Unit();
            $unit->unit = trim($request->unit);
            $unit->status = $request->has('status') ? '1' : '0';
            
            if ($unit->save()) {
                DB::commit();
                return $this->successMsg('Unit created successfully');
            }
            
            DB::rollBack();
            return $this->failMsg('Unit not created');
        } catch (Exception $e) {
            DB::rollBack();
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
            DB::beginTransaction();
            
            $unit = Unit::findOrFail($id);
            $unit->unit = trim($request->unit);
            $unit->status = $request->has('status') ? '1' : '0';

            if ($unit->save()) {
                DB::commit();
                return $this->successMsg('Unit updated successfully');
            }
            
            DB::rollBack();
            return $this->failMsg('Unit not updated');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:uni_unit,id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            DB::beginTransaction();
            
            $unit = Unit::findOrFail($request->id);
            $unit->status = $request->status ? '1' : '0';

            if ($unit->save()) {
                DB::commit();
                return $this->successMsg('Status updated successfully');
            }
            
            DB::rollBack();
            return $this->failMsg('Status not updated');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $unit = Unit::findOrFail($id);

            if ($unit->delete()) {
                DB::commit();
                return $this->successMsg('Unit deleted successfully');
            }
            
            DB::rollBack();
            return $this->failMsg('Unit not deleted');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        try {
            $query = Unit::query();

            // Filtering
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where('unit', 'like', '%' . $search . '%');
            }
            
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            $total = $query->count();

            // Ordering
            $columns = $request->columns ?? [];
            if ($request->order && count($request->order)) {
                foreach ($request->order as $order) {
                    $colIdx = $order['column'] ?? 0;
                    if (isset($columns[$colIdx]['data'])) {
                        $colName = $columns[$colIdx]['data'];
                        $dir = $order['dir'] ?? 'asc';
                        
                        // Only allow ordering on safe columns
                        $allowedColumns = ['unit', 'status', 'created_at'];
                        if (in_array($colName, $allowedColumns)) {
                            $query->orderBy($colName, $dir);
                        }
                    }
                }
            }

            // Pagination
            $start = (int)($request->start ?? 0);
            $length = (int)($request->length ?? 10);
            $length = min($length, 100); // Limit max records per page
            
            $units = $query->skip($start)->take($length)->get();

            $data = $units->map(function ($unit, $key) use ($start) {
                $statusSwitch = '<div class="form-check form-switch">'
                    . '<input type="checkbox" class="form-check-input status-switch" data-id="' . htmlspecialchars($unit->id) . '" ' . ($unit->status ? 'checked' : '') . '>'
                    . '<label class="form-check-label" for="status' . htmlspecialchars($unit->id) . '"></label>'
                    . '</div>';
                    
                return [
                    'index' => $start + $key + 1,
                    'id' => $unit->id,
                    'unit' => htmlspecialchars($unit->unit),
                    'status' => $statusSwitch,
                    'created_at' => $unit->dFormat($unit->created_at),
                ];
            });

            return response()->json([
                'draw' => (int)($request->draw ?? 1),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'draw' => (int)($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
} 