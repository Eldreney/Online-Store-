<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TempImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $image = $request->file('image');

        $ext = $image->getClientOriginalExtension();
        $newName = time() . '-' . uniqid() . '.' . $ext;

        $tempImage = TempImage::create([
            'name' => $newName
        ]);

        $image->move(public_path('temp'), $newName);

        return response()->json([
            'status' => true,
            'image_id' => $tempImage->id,
            'image_name' => $newName
        ]);
    }
}
