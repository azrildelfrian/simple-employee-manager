@extends('layouts.main')

@section('content')
<section class="employee-create">
    <h1>Add New Employee</h1>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('employee.store') }}" method="POST" class="dropzone" id="employeeForm">
                @csrf
                <div class="regular-form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="position">Position</label>
                        <select name="position" id="position" class="form-control position2" required>
                            <option value="">Select Position</option>
                            <option value="System Architect">System Architect</option>
                            <option value="Developer">Developer</option>
                            <option value="Designer">Designer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" class="form-control gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="text" name="start_date" id="start_date" class="form-control datepicker" value="{{ old('start_date') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="salary">Salary</label>
                        <input type="number" name="salary" id="salary" class="form-control" value="{{ old('salary') }}" required>
                    </div>

                    <div class="form-group mt-4">
                        <label>Profile Picture</label>
                        <div id="dropzoneArea" class="dropzone-area">Drag n drop your profile pict here.<br></div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Employee</button>
                        <a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script>
    Dropzone.autoDiscover = false;
    $(document).ready(function() {
    // Select2
    $('.position2').select2({
        placeholder: "Select a position",
        allowClear: true
    });

    $('.gender').select2({
        placeholder: "Select a gender",
        allowClear: true
    });

    // Datepicker
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    // Dropzone
    var myDropzone = new Dropzone("#dropzoneArea", {
        url: "{{ route('employee.store') }}",
        autoProcessQueue: false,
        uploadMultiple: false,
        maxFilesize: 2,
        maxFiles: 1,
        acceptedFiles: '.jpeg,.jpg,.png,.gif,.webp',
        addRemoveLinks: true,
        paramName: "profile_pict"
    });

    // Validasi form
    $("#employeeForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            position: {
                required: true
            },
            gender: {
                required: true
            },
            start_date: {
                required: true,
                date: true
            },
            salary: {
                required: true,
                number: true,
                min: 0
            }
        },
        messages: {
            name: {
                required: "Please enter the employee's name",
                minlength: "Name should be at least 3 characters"
            },
            position: {
                required: "Please select a position"
            },
            gender: {
                required: "Please select a gender"
            },
            start_date: {
                required: "Please provide a start date",
                date: "Please enter a valid date"
            },
            salary: {
                required: "Please enter a salary amount",
                number: "Please enter a valid number",
                min: "Salary cannot be negative"
            }
        },
        submitHandler: function(form) {
            if (myDropzone.getQueuedFiles().length > 0) {
                myDropzone.processQueue();
            } else {
                form.submit();
            }
        }
    });

    $('button[type="submit"]').click(function(e) {
        e.preventDefault();
        e.stopPropagation();

        if ($("#employeeForm")[0].checkValidity()) {
            $("#employeeForm").submit();
        } else {
            alert('Please fill out all required fields correctly.');
        }
    });

    myDropzone.on("sending", function(file, xhr, formData) {
        var formElements = $('#employeeForm').serializeArray();
        $.each(formElements, function(i, element) {
            formData.append(element.name, element.value);
        });
    });

    myDropzone.on("success", function(file, response) {
        window.location.href = "{{ route('employee.index') }}";
    });

    myDropzone.on("error", function(file, response) {
        console.error(response);
        alert('Error uploading file: ' + response.message);
    });
});
</script>
@endsection