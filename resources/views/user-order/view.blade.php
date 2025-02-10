@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">User Order Details</h1>

        <div>
            <h2 class="text-xl font-semibold">User: {{ $user->name }}</h2>
            <p>Email: {{ $user->email }}</p>

            <h3 class="mt-4 text-lg font-semibold">Order Products</h3>

            <table class="min-w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left border-b">Order Date</th>
                        <th class="px-4 py-2 text-left border-b">Product Name</th>
                        <th class="px-4 py-2 text-left border-b">Start Date</th>
                        <th class="px-4 py-2 text-left border-b">End Date</th>
                        <th class="px-4 py-2 text-left border-b">TXN ID</th>
                        <th class="px-4 py-2 text-left border-b">Duration</th>
                        <th class="px-4 py-2 text-left border-b">Create Time</th>
                        <th class="px-4 py-2 text-left border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $date => $products)
                        @php
                            $dateRowspan = 0;
                            foreach ($products as $productName => $product) {
                                foreach ($product as $productKey => $cycles) {
                                    $dateRowspan += count($cycles->productCycles);
                                }
                            }
                        @endphp

                        @foreach ($products as $productName => $product)
                            @php
                                $productRowspan = 0;
                                foreach ($product as $productKey => $cycles) {
                                    $productRowspan += count($cycles->productCycles);
                                }
                            @endphp

                            @foreach ($product as $productKey => $cycles)
                                @php
                                    $activeCycle = $cycles->activeCycle; // Get the active cycle for this order product
                                @endphp

                                @foreach ($cycles->productCycles as $cycleKey => $cycle)
                                    <tr>
                                        @if ($loop->parent->parent->first && $loop->parent->first && $loop->first)
                                            <td class="px-4 py-2 border-b" rowspan="{{ $dateRowspan }}">{{ $date }}
                                            </td>
                                        @endif
                                        @if ($loop->parent->first && $loop->first)
                                            <td class="px-4 py-2 border-b" rowspan="{{ $productRowspan }}">
                                                {{ $productName }}</td>
                                        @endif
                                        <td class="px-4 py-2 border-b">{{ $cycle->start_date->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2 border-b">{{ $cycle->end_date->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2 border-b">{{ $cycles->txn_id }}</td>
                                        <td class="px-4 py-2 border-b">
                                            {{ $cycle->duration }}
                                            {{ $cycle->duration == 1 ? 'month' : 'months' }}
                                        </td>
                                        <td class="px-4 py-2 border-b">{{ $cycle->created_at->format('M j, Y h:i A') }}
                                        </td>

                                        <td class="px-4 py-2 border-b">
                                            @if ($cycle->canceled)
                                                <span class="text-gray-500 font-semibold">Canceled</span>
                                            @elseif ($cycle->status)
                                                <span class="text-green-600 font-semibold">Active</span>
                                            @else
                                                <span class="text-red-600 font-semibold">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border-b">
                                            @if ($cycle->canceled)
                                                <span class="text-gray-500 font-semibold">Canceled</span>
                                            @else
                                                <button onclick="cancelOrder('{{ $cycle->id }}')"
                                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                    Cancel Order
                                                </button>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<script>
    function cancelOrder(cycleId) {
        if (confirm('Are you sure you want to cancel this order?')) {
            fetch(`/cancel-order/${cycleId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while canceling the order.');
                });
        }
    }
</script>
