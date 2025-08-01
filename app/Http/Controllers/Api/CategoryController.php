<?php
// use Log;
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::where('status', 1)->get()->map(function($category){
                 return $this->field($category);                    
            });
            return $this->recordRes($categories);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function field($record){

        return [
            'id' => $record->id??0,
            'name' => $record->name??'',
            'image' => $record->img??'',
            'description' => $record->description??'',
            'slug' => $record->slug??'',
            'product_section' => $record->pro_section??'',
            'status' => $record->status,
            'created_at' => $record->created_at??'',
            'updated_at' => $record->updated_at??'',
        ];
    }

    public function show($id){
        try{
            $category = Category::where('id', $id)->where('status', 1)->first();
            if(!$category){
                return $this->failRes('Category not found');
            }
            return $this->recordRes($this->field($category));
        }catch(Exception $e){
            return $this->failRes($e->getMessage());
        }
    }
}
