<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CarController extends Controller

{
    public function showall()
    {
        $car = Car::with('car')->get();
        return response()->json($car, 200);
    }
    //fillter car
    public function index(Request $request)
    {
        $query = Car::query();

        // فحص الفلاتر
        if ($request->has('model')) {
            $query->where('model', 'like', '%'.$request->input('model').'%');
        }

        if ($request->has('year')) {
            $query->where('year', $request->input('year'));
        }

        if ($request->has('color')) {
            $query->where('color', 'like', '%'.$request->input('color').'%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('location')) {
            $query->where('location', 'like', '%'.$request->input('location').'%');
        }

        if ($request->has('seats')) {
            $query->where('seats', $request->input('seats'));
        }

        if ($request->has('features')) {
            $query->where('features', 'like', '%'.$request->input('features').'%');
        }

        // تنفيذ الاستعلام
        $cars = $query->get();

        // التحقق من وجود سيارات
        if ($cars->isEmpty()) {
            return response()->json([
                'message' => 'لم يتم العثور على سيارات مطابقة للبحث.',
            ], 404);
        }

        // إرجاع البيانات إذا تم العثور على سيارات
        return response()->json([
            'message' => 'تم العثور على السيارات.',
            'data' => $cars
        ], 200);
    }




    // show cars by id
    public function show($id)
    {
        return Car::findOrFail($id);
    }

    // add cars and store it
    public function store(Request $request)
    {
        //verify the input
        $validator = Validator::make($request->all(), [
            'model' => 'required|string|max:255',
            'year' => 'required|integer',
            'color' => 'required|string|max:50',
            'features' => 'nullable|string',
            'seats' => 'required|integer',
            'status' => 'required|string',
            'location' => 'required|string|max:255',
            'daily_price' => 'required|numeric',
            'image' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'البيانات المدخلة غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $car = Car::create($request->all());

        return response()->json([
            'message' => 'تم إضافة السيارة بنجاح',
            'car' => $car
        ], 201);
    }


    // update information car
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $car->update($request->all());

        return response()->json([
            'message' => 'تم تحديث السيارة بنجاح',
            'car' => $car
        ]);
    }


// delete car
public function destroy($id)
{
    $car = Car::findOrFail($id);

    $car->delete();

    return response()->json([
        'message' => 'تم حذف السيارة بنجاح'
    ]);
}
}
