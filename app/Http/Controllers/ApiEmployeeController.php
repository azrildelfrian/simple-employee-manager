<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class ApiEmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return response()->json([
            'success' => true,
            'data' => $employees
        ], 200);
    }

    public function show($id)
    {
        $employee = Employee::find($id);

        if ($employee) {
            return response()->json([
                'success' => true,
                'data' => $employee
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'start_date' => 'required|date',
            'salary' => 'required|numeric',
            'profile_pict' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();

        if ($request->hasFile('profile_pict')) {
            $filename = time() . '_' . $request->file('profile_pict')->getClientOriginalName();
            $request->file('profile_pict')->move(public_path('assets/profile_pict'), $filename);
            $data['profile_pict'] = $filename;
        }

        $employee = Employee::create($data);

        return response()->json([
            'success' => true,
            'data' => $employee
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'start_date' => 'required|date',
            'salary' => 'required|numeric',
            'profile_pict' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $data = $request->all();

        if ($request->hasFile('profile_pict')) {
            if ($employee->profile_pict) {
                $oldFilePath = public_path('assets/profile_pict/' . $employee->profile_pict);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $filename = time() . '_' . $request->file('profile_pict')->getClientOriginalName();
            $request->file('profile_pict')->move(public_path('assets/profile_pict'), $filename);
            $data['profile_pict'] = $filename;
        }

        $employee->update($data);

        return response()->json([
            'success' => true,
            'data' => $employee
        ], 200);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        if ($employee->profile_pict) {
            $filePath = public_path('assets/profile_pict/' . $employee->profile_pict);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully'
        ], 200);
    }

    // public function batchDelete(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'ids' => 'required|array',
    //         'ids.*' => 'exists:employees,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 400);
    //     }

    //     $employees = Employee::whereIn('id', $request->ids)->get();

    //     foreach ($employees as $employee) {
    //         if ($employee->profile_pict) {
    //             $oldFilePath = public_path('assets/profile_pict/' . $employee->profile_pict);
    //             if (file_exists($oldFilePath)) {
    //                 unlink($oldFilePath);
    //             }
    //         }
    //         $employee->delete();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Selected employees deleted successfully'
    //     ], 200);
    // }
}
