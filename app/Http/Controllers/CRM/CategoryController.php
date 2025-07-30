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
            $parentCategories = Category::where('parent_id', 0)->get();
            return view('CRM.Category.index', compact('parentCategories'));
        } catch (Exception $e) {
            return abort(500, $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $parentCategories = Category::where('parent_id', 0)->get();
            return view('CRM.Category.create', compact('parentCategories'));
        } catch (Exception $e) {
            return abort(500, $e->getMessage());
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $exist = Category::where('name', $request->name)->first();
            if ($exist) {
                return $this->failMsg('Category already exists');
            }

            $save = new Category();
            $save->name = $request->name;
            $save->slug = generateSlug($request->name,'uni_category');
            $save->status = $request->status;
            $save->parent_id = $request->parent_id ?? 0;
            $save->cat_role = $request->parent_id == 0 ? 'super' : 'sub';
            $save->pro_section = $request->pro_section;
            $save->meta_title = $request->meta_title;
            $save->meta_keyword = $request->meta_keyword;
            $save->meta_description = $request->meta_description;
            $save->description = $request->description;
            if ($request->hasFile('img')) {
                $save->img = singleFile($request->img, 'category');
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
            $parentCategories = Category::where('parent_id', 0)
                ->where('id', '!=', $category->id)
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
                    ->where('id', '!=', $id)
                    ->first();
                if ($exist) {
                    return $this->failMsg('Category name already exists');
                }
            }

            $category->name = $request->name;
            $category->slug = generateSlug($request->name,'uni_category');
            $category->status = $request->status;
            $category->parent_id = $request->parent_id ?? 0;
            $category->cat_role = $request->parent_id == 0 ? 'super' : 'sub';
            $category->pro_section = $request->pro_section;
            $category->meta_title = $request->meta_title;
            $category->meta_keyword = $request->meta_keyword;
            $category->meta_description = $request->meta_description;
            $category->description = $request->description;
            if ($request->hasFile('img')) {
                $category->img = singleFile($request->img, 'category');
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
                'id' => 'required|exists:uni_category,id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }

            $category = Category::findOrFail($request->id);
            $category->status = $request->status;

            if ($category->save()) {
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
                $q->where('name', 'like', "%{$search}%");
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
                '_id' => $cat->id,
                'img' => $cat->img,
                'name' => $cat->name,
                'parent_name' => $cat->parent ? $cat->parent->name : 'None',
                'status' => $cat->status,
                'pro_section' => $cat->pro_section,
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