@extends('layouts.main')

@section('content')
<section class="employee">
    <h1>Welcome to the Employee Page</h1>
    <p>This is the content of the employee page.</p>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">List of Employees</h5>
            <div class="ml-auto">
                <a href="{{ route('employee.create') }}" class="btn btn-primary">Add Employee</a>
                <button id="batch-delete" class="btn btn-danger" onclick="confirmBatchDelete()">Delete Selected</button>
            </div>
        </div>        
        <div class="card-body">
            <form id="batch-delete-form" action="{{ route('employee.batchDelete') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table id="employee" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Profile Picture</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Gender</th>
                                <th>Start date</th>
                                <th>Salary</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $employee->id }}"></td>
                                <td>
                                    @if($employee->profile_pict)
                                        <img src="{{ asset('assets/profile_pict/' . $employee->profile_pict) }}" alt="Profile Picture" class="profile_pict">
                                    @else
                                        <img src="https://placehold.co/500x500" alt="Default Profile Picture" class="profile_pict">
                                    @endif
                                </td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->gender }}</td>
                                <td>{{ $employee->start_date }}</td>
                                <td>Rp. {{ number_format($employee->salary, 2) }}</td>
                                <td>
                                    <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning btn-sm"><img src="{{ asset('assets/img/edit.svg') }}" height="20" width="20"></a>
                                    <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><img src="{{ asset('assets/img/delete.svg') }}" height="20" width="20"></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Select</th>
                                <th>Profile Picture</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Gender</th>
                                <th>Start date</th>
                                <th>Salary</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    new DataTable('#employee');

    function confirmBatchDelete() {
        const checkboxes = document.querySelectorAll('input[name="ids[]"]:checked');
        if (checkboxes.length === 0) {
            alert('Please select at least one employee to delete.');
            return;
        }
        if (confirm('Are you sure you want to delete the selected employees?')) {
            document.getElementById('batch-delete-form').submit();
        }
    }
</script>
@endsection
