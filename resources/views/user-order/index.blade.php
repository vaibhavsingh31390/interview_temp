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
                <label for="password" class="block font-medium">Password</label>
                <input type="password" id="password" name="password" class="border p-2 w-full" required>
            </div>
            <div>
                <label for="product" class="block font-medium">Product</label>
                <select id="product" name="product" class="border p-2 w-full select2" required>
                    <option value="">Select a Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>

                <button type="button" id="createProductBtn" class="bg-green-500 text-white px-4 py-2 mt-2 rounded">Create
                    New Product</button>
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

    <!-- Modal to create product -->
    <div id="createProductModal"
        class="hidden fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-xl mb-4">Create New Product</h2>
            <input type="text" id="newProductName" class="border p-2 w-full" placeholder="Enter product name" required>
            <button type="button" id="saveProductBtn" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Save</button>
            <button type="button" id="closeModalBtn" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Close</button>
        </div>
    </div>
@endsection

<script type="module">
    $(document).ready(function() {

        $('#createProductBtn').on('click', function() {
            $('#createProductModal').removeClass('hidden');
        });

        $('#closeModalBtn').on('click', function() {
            $('#createProductModal').addClass('hidden');
        });

        $('#saveProductBtn').on('click', function() {
            var newProductName = $('#newProductName').val();

            if (newProductName.trim() === "") {
                alert('Product name is required');
                return;
            }

            $.ajax({
                url: '/create-product',
                method: 'POST',
                data: {
                    name: newProductName,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    $('#product').append(new Option(response.data.name, response.data.id));
                    $('#createProductModal').addClass('hidden');
                    $('#newProductName').val('');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert('Error creating product: ' + error);
                }
            });
        });



        // Initialize Select2
        $('#product').select2();

        // Form submission with AJAX
        $('#orderForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '/save',
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response, 'yes');
                    alert(response.message);
                    $('#orderForm')[0].reset();
                    $('#product').val(null).trigger('change');
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 404) {
                        var response = JSON.parse(xhr.responseText);
                        alert('Error: ' + response.message || 'Product not found.');
                    } else {
                        alert('An error occurred: ' + xhr.status + ' - ' + xhr.statusText);
                    }
                }
            });
        });
    });
</script>


</script>
