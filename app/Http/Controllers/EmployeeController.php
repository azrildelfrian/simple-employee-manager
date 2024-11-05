<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    //Load view utama
    public function index()
    {
        $employees = Employee::all();
        return view('employee.index', compact('employees'));
    }

    //Load view employee
    public function create()
    {
        return view('employee.create');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employee.edit', compact('employee'));
    }


    //Proses setor data ke db
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'start_date' => 'required|date',
            'salary' => 'required|numeric',
            'profile_pict' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('profile_pict')) {
            $filename = time() . '_' . $request->file('profile_pict')->getClientOriginalName();
            $request->file('profile_pict')->move(public_path('assets/profile_pict'), $filename);
            $data['profile_pict'] = $filename;
        }

        Employee::create($data);

        return redirect()->route('employee.index')->with('success', 'Employee successfully ADDED.');
    }

    //Proses ubah data 
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'start_date' => 'required|date',
            'salary' => 'required|numeric',
            'profile_pict' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $employee = Employee::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('profile_pict')) {
            if ($employee->profile_pict) {
                $oldFilePath = public_path($employee->profile_pict);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $filename = time() . '_' . $request->file('profile_pict')->getClientOriginalName();
            $request->file('profile_pict')->move(public_path('assets/profile_pict'), $filename);
            $data['profile_pict'] = $filename;
        }

        $employee->update($data);

        return redirect()->route('employee.index')->with('success', 'Employee successfully UPDATED.');
    }

    //Proses hapus data
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            
            if ($employee->profile_pict) {
                $filePath = public_path('assets/profile_pict/' . $employee->profile_pict);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $employee->delete();
            
            return redirect()->route('employee.index')->with('success', 'Employee DELETED successfully.');
        } catch (\Exception $e) {
            return redirect()->route('employee.index')->with('error', 'FAILED to delete employee.');
        }
    }


    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:employees,id',
        ]);

        $employees = Employee::whereIn('id', $request->ids)->get();

        foreach ($employees as $employee) {
            if ($employee->profile_pict) {
                $oldFilePath = public_path('assets/profile_pict/' . $employee->profile_pict);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $employee->delete();
        }

        return redirect()->route('employee.index')->with('success', 'Selected employees successfully deleted.');
    }


}
