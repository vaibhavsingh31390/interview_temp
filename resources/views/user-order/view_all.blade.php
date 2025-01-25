@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">All Users Order Details</h1>

        @foreach ($users as $user)
            <div class="mb-6 mt-4">
                <h2 class="text-xl font-semibold">User: {{ $user->name }}</h2>
                <p>Email: {{ $user->email }}</p>

                <h3 class="mt-4 text-lg font-semibold">Order Products</h3>

                <table class="min-w-full table-auto border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left border-b">User Name / ID</th>
                            <th class="px-4 py-2 text-left border-b">Product Name</th>
                            <th class="px-4 py-2 text-left border-b">Duration</th>
                            <th class="px-4 py-2 text-left border-b">Start Date</th>
                            <th class="px-4 py-2 text-left border-b">End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->orderProducts as $i => $orderProduct)
                            @php
                                $cycleCount = $orderProduct->count();
                            @endphp
                            @if ($cycleCount > 0)
                                @foreach ($orderProduct->productCycles as $index => $productCycle)
                                    <tr>
                                        @if ($i == 0)
                                            <td class="px-4 py-2 border-b" rowspan="{{ $cycleCount }}">
                                                {{ $user->name }} (ID: {{ $user->id }})
                                            </td>
                                        @endif
                                        <td class="px-4 py-2 border-b">{{ $orderProduct->name }}</td>
                                        <td class="px-4 py-2 border-b">
                                            {{ $productCycle->duration }}
                                            {{ $productCycle->duration == '1' ? 'month' : 'months' }}
                                        </td>
                                        <td class="px-4 py-2 border-b">{{ $productCycle->start_date->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2 border-b">{{ $productCycle->end_date->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
@endsection
