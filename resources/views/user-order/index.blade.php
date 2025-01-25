@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Create Order</h1>
        <form id="orderForm" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block font-medium">Name</label>
                <input type="text" id="name" name="name" class="border p-2 w-full" required>
            </div>
            <div>
                <label for="email" class="block font-medium">Email</label>
                <input type="email" id="email" name="email" class="border p-2 w-full" required>
            </div>
            <div>
                <label for="email" class="block font-medium">Password</label>
                <input type="password" id="password" name="password" class="border p-2 w-full" required>
            </div>
            <div>
                <label for="product" class="block font-medium">Product Name</label>
                <input type="text" id="product" name="product" class="border p-2 w-full" required>
            </div>
            <div>
                <label for="duration" class="block font-medium">Duration (in months)</label>
                <select id="duration" name="duration" class="border p-2 w-full" required>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('duration') == $i ? 'selected' : '' }}>
                            {{ $i }} Month{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#orderForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: '/save',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('Data saved successfully for user: ' + response.user.name +
                            '. Message: ' + response.message);
                        $('#orderForm')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = 'There were validation errors: \n';
                            for (var field in errors) {
                                errorMessage += errors[field].join(', ') + '\n';
                            }
                            alert(errorMessage);
                        } else if (xhr.status === 500) {
                            var error = xhr.responseJSON.error;
                            if (error === 'The password does not match the existing user.') {
                                alert('The password does not match the existing user.');
                            } else {
                                alert('An error occurred: ' + error);
                            }
                        } else {
                            console.log(error);
                            alert(error.message || 'There was an error saving the data.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
