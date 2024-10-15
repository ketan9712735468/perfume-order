<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card for Perfume Service -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Perfume Service</h3>
                        <p class="text-gray-600 mb-4">Manage all perfume services here.</p>
                        <button onclick="window.location.href='/perfume-service'" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Go to Perfume Service
                        </button>
                    </div>

                    <!-- Card for Perfume Order -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Perfume Order</h3>
                        <p class="text-gray-600 mb-4">Manage perfume orders here.</p>
                        <button onclick="window.location.href='/perfume-order/order_details'" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Go to Perfume Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
