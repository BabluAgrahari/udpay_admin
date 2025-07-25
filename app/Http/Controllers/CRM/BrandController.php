<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        try {
            return view('CRM.Brand.index');
        } catch (Exception $e) {
           abort(500, $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('CRM.Brand.create');
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function store(BrandRequest $request)
    {
        try {
            $save = new Brand();
            $save->name = $request->name;
            $save->slug = generateSlug($request->name, 'brands', 'slug');
            $save->description = $request->description;
            $save->status = $request->status;
            $save->meta_title = $request->meta_title;
            $save->meta_keyword = $request->meta_keyword;
            $save->meta_description = $request->meta_description;
            if ($request->hasFile('icon')) {
                $save->icon = singleFile($request->file('icon'), 'brands');
            }

            if($save->save())
            return $this->successMsg('Brand created successfully');

            return $this->failMsg('Brand not Created.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            return $this->successMsg('Brand retrieved successfully', $brand);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            return view('CRM.Brand.edit', compact('brand'));
        } catch (Exception $e) {
            return abort(500, $e->getMessage());
        }
    }

    public function update(BrandRequest $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->name = $request->name;
            $brand->slug = generateSlug($request->name, 'brands', 'slug');
            $brand->description = $request->description;
            $brand->status = $request->status;
            $brand->meta_title = $request->meta_title;
            $brand->meta_keyword = $request->meta_keyword;
            $brand->meta_description = $request->meta_description;

            if ($request->hasFile('icon')) {
                $brand->icon = singleFile($request->file('icon'), 'brands');
            }

           if($brand->save())
            return $this->successMsg('Brand updated successfully');

            return $this->failMsg('Something went wrong, Brand not updated.');
        } catch (Exception $e) {
            return $this->errorRes($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $brand = Brand::findOrFail($request->id);
            $brand->status = $request->status;
            $brand->save();

            return $this->successMsg('Status updated successfully');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();

            return $this->successMsg('Brand deleted successfully');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $query = Brand::query();

        // Filtering
        if ($request->search && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $total = $query->count();

        // Ordering
        $columns = $request->columns;
        if ($request->order && count($request->order)) {
            foreach ($request->order as $order) {
                $colIdx = $order['column'];
                $colName = $columns[$colIdx]['data'];
                $dir = $order['dir'];
                $query->orderBy($colName, $dir);
            }
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $brands = $query->skip($start)->take($length)->get();

        $data = $brands->map(function ($brand) {
            return [
                'id' => $brand->id,
                'icon' => $brand->icon ? asset($brand->icon) : null,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'description' => $brand->description,
                'status' => $brand->status,
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