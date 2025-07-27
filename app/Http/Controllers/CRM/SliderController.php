<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        try {
            $sliders = Slider::all()->groupBy('type');
            return view('CRM.slider.index', compact('sliders'));
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
       
    }

    public function create()
    {   
        try {
            return view('CRM.slider.create');
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
         $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'status' => 'required|in:0,1',
            'url' => 'nullable|url',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        $images = $request->file('image');
        $responses = [];
        if ($images && is_array($images)) {
            foreach ($images as $img) {
                $imagePath = singleFile($img, 'slider');
                $slider = Slider::create([
                    'title' => $request->title,
                    'type' => $request->type,
                    'status' => (int)$request->status,
                    'url' => $request->url,
                    'image' => $imagePath,
                ]);
                $responses[] = $slider;
            }
            }
            if(!empty($responses)){
                return $this->successMsg('Slider created successfully', $responses);
            }
            return $this->failMsg('Slider not created');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $slider = Slider::findOrFail($id);
            return view('CRM.slider.edit', compact('slider'));
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'status' => 'required|in:0,1',
            'url' => 'nullable|url',
            'image' => 'nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        $slider = Slider::findOrFail($id);
        $slider->title = $request->title;
        $slider->type = $request->type;
        $slider->status = (int)$request->status;
        $slider->url = $request->url;
        if ($request->hasFile('image')) {
            $img = $request->file('image')[0];
            $imagePath = singleFile($img, 'slider');
            $slider->image = $imagePath;
        }
        if($slider->save()){
            return $this->successMsg('Slider updated successfully');
        }
        return $this->failMsg('Slider not updated');
        } catch (Exception $e) {
           return $this->failMsg($e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $slider = Slider::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:0,1',
            ]);
            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }
            $slider->status = (int)$request->status;
            if ($slider->save()) {
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
            $slider = Slider::findOrFail($id);
            $slider->delete();
            return $this->successMsg('Slider deleted successfully');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }





    
} 