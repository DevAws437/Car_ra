<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // signup for new user
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'license_number' => 'required|string|max:50',
            'license_image' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'البيانات المدخلة غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'license_number' => $request->license_number,
                'license_image' => $request->license_image,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء تسجيل المستخدم.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'تم تسجيل المستخدم بنجاح',
            'user' => $user,
        ], 201);
    }




    // login for user
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'البيانات المدخلة غير صحيحة',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    // Specify destination based on user role
    $redirectTo = $user->role === 'admin' ? 'dashboard' : 'home';

    return response()->json([
        'message' => 'تم تسجيل الدخول بنجاح',
        'token' => $token,
        'user' => $user,
        'redirect_to' => $redirectTo,
    ], 200);
}



public function logout(Request $request)
{
    try {
        // chek tokens
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'لم يتم العثور على المستخدم'
            ], 401); // Unauthorized
        }

        // delete tokens
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'حدث خطأ أثناء محاولة تسجيل الخروج',
            'error' => $e->getMessage(),
        ], 500);
    }
}


//update informtion user personal
    public function updateUser(Request $request, $user_id)
{
    //verify the input
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user_id,
        'phone' => 'required|string|max:15',
        'address' => 'required|string|max:255',
        'license_number' => 'required|string|max:50',
        'license_image' => 'nullable|url',
    ]);

    // If the input is invalid, return errors.
    if ($validator->fails()) {
        return response()->json([
            'message' => 'البيانات المدخلة غير صحيحة',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = User::find($user_id);

    // To verify user presence
    if (!$user) {
        return response()->json([
            'message' => 'المستخدم غير موجود',
        ], 404); // Status code 404: Not Found
    }

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'license_number' => $request->license_number,
        'license_image' => $request->license_image,
    ]);

    return response()->json([
        'message' => 'تم تعديل بيانات المستخدم بنجاح',
        'user' => $user,
    ], 200); // Status code 200: OK
}

//delet account user
public function deleteUser($user_id)
{
    // Search for user
    $user = User::find($user_id);

    //verify the user's presence
    if (!$user) {
        return response()->json([
            'message' => 'المستخدم غير موجود',
        ], 404); // Status code 404: Not Found
    }


    $user->delete();

    return response()->json([
        'message' => 'تم حذف المستخدم بنجاح',
    ], 200); // Status code 200: OK
}

////////////////// اضافة موظف //////////////////

public function storeEmployee(Request $request)
{
    if (auth()->user()->roles->contains('name', 'Admin')) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'license_number' => 'required|string|max:50',
            'license_image' => 'nullable|url',
            'role' => 'required|string|in:user,Employee', // حدد الأدوار المسموح فيها فقط
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'البيانات المدخلة غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $employee = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'license_number' => $request->license_number,
                'license_image' => $request->license_image,
            ]);

            // تعيين الدور حسب الطلب
            $employee->assignRole($request->role);

            return response()->json([
                'message' => 'تم إنشاء الموظف بنجاح',
                'user' => $employee,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إنشاء الموظف.',
                'error' => $e->getMessage(),
            ], 500);
        }
    } else {
        return response()->json([
            'message' => 'أنت غير مخول لتنفيذ هذا الإجراء.',
        ], 403);
    }

}

}
