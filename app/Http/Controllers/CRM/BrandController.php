<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Brand::query();

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            $brands = $query->paginate(10);

            return view('CRM.brand.index', compact('brands'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('CRM.brand.create');
    }

    public function store(BrandRequest $request)
    {
        try {
            $data = $request->validated();

            $save = new Brand();
            $save->name = $request->name;
            $save->slug_url = generateSlug($request->name, 'brands', 'slug_url');
            $save->description = $request->description;
            $save->status = (int)$request->status;
            
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

    public function edit($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            return view('CRM.brand.edit', compact('brand'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(BrandRequest $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->name = $request->name;
            $save->slug_url = generateSlug($request->name, 'brands', 'slug_url');
            $brand->description = $request->description;
            $brand->status = (int)$request->status;

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
            $brand->status = (int)$request->status;
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
} 