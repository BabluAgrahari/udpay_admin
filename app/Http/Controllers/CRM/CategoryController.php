<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $parentCategories = Category::whereNull('parent_id')->get();
            return view('CRM.Category.index', compact('parentCategories'));
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function create()
    {
        try {
            $parentCategories = Category::whereNull('parent_id')->get();
            return view('CRM.Category.create', compact('parentCategories'));
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $exist = Category::where('name', $request->name)->first();
            if ($exist) {
                return $this->failMsg('Category already exists');
            }

            $labels = explode(',',$request->labels);
            $save = new Category();
            $save->name = $request->name;
            $save->slug_url = generateSlug($request->name, 'categories', 'slug_url');
            $save->description = $request->description;
            $save->status = (int)$request->status;
            $save->parent_id = $request->parent_id;
            $save->labels = $labels;
            $save->meta_title = $request->meta_title;
            $save->meta_keyword = $request->meta_keyword;
            $save->meta_description = $request->meta_description;
            $save->user_id = Auth::user()->_id;

            if ($request->hasFile('icon')) {
                $save->icon = singleFile($request->icon, 'category');
            }
            if ($save->save()) {
                return $this->successMsg('Category created successfully');
            }
            return $this->failMsg('Category not created');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            $parentCategories = Category::whereNull('parent_id')
                ->where('_id', '!=', $category->_id)
                ->get();

            return view('CRM.Category.edit', compact('category', 'parentCategories'));
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        try {

            $category = Category::findOrFail($id);

            // Check if name is being changed and if it already exists
            if ($category->name !== $request->name) {
                $exist = Category::where('name', $request->name)
                    ->where('_id', '!=', $id)
                    ->first();
                if ($exist) {
                    return $this->failMsg('Category name already exists');
                }
            }

            $labels = explode(',',$request->labels);
            $category->name = $request->name;
            $category->slug_url = generateSlug($request->name, 'categories', 'slug_url');
            $category->description = $request->description;
            $category->status = (int)$request->status;
            $category->parent_id = $request->parent_id;
            $category->labels = $labels;
            $category->meta_title = $request->meta_title;
            $category->meta_keyword = $request->meta_keyword;
            $category->meta_description = $request->meta_description;

            if ($request->hasFile('icon')) {
                $category->icon = singleFile($request->icon, 'category');
            }

            if ($category->save()) {
                return $this->successMsg('Category updated successfully');
            }
            return $this->failMsg('Category not updated');

        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:categories,_id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }

            $category = Category::findOrFail($request->id);
            $category->status = (int)$request->status;

            if ($category->save()) {
                return $this->successMsg('Status updated successfully');
            }
            return $this->failMsg('Status not updated');

        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateShortCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:categories,_id',
                'short' => 'required|string|max:50'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }

            $category = Category::findOrFail($request->id);
            $category->short = (int) $request->short;

            if ($category->save()) {
                return $this->successMsg('Short code updated successfully');
            }
            return $this->failMsg('Short code not updated');

        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check if category has children
            if ($category->children()->count() > 0) {
                return $this->failMsg('Cannot delete category with subcategories');
            }

            if ($category->delete()) {
                return $this->successMsg('Category deleted successfully');
            }
            return $this->failMsg('Category not deleted');

        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $query = Category::query();

        // Filtering
        if ($request->search && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', (int)$request->status);
        }
        if ($request->parent_id) {
            $query->where('parent_id', $request->parent_id);
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
        $categories = $query->with('parent')->skip($start)->take($length)->get();

        $data = $categories->map(function ($cat) {
            return [
                '_id' => $cat->_id,
                'icon' => $cat->icon,
                'name' => $cat->name,
                'short' => $cat->short,
                'parent_name' => $cat->parent ? $cat->parent->name : 'None',
                'status' => $cat->status,
                'labels' => $cat->labels,
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