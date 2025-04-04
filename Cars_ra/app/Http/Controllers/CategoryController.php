<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // عرض جميع الفئات
    public function index()
    {
        return response()->json(Category::all(), 200);
    }

    public function show($id)
{
    $category = Category::find($id);

    if (!$category) {
        return response()->json(['message' => 'الفئة غير موجودة'], 404);
    }

    return response()->json($category, 200);
}


    // إضافة فئة جديدة
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create($request->all());

        return response()->json(['message' => 'تمت إضافة الفئة بنجاح', 'category' => $category], 201);
    }

    // تعديل فئة
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'الفئة غير موجودة'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($request->all());

        return response()->json(['message' => 'تم تعديل الفئة بنجاح', 'category' => $category], 200);
    }

    // حذف فئة
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'الفئة غير موجودة'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'تم حذف الفئة بنجاح'], 200);
    }
}

