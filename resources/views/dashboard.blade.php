<x-app-layout>
    <div class="flex-grow ml-64 mt-24 main_layout">
        <div class="max-w-7xl ml-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card for Inventory Management -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Inventory Management</h3>
                        <p class="text-gray-600 mb-4">Manage all Inventory Management here.</p>
                        <button onclick="window.location.href='/perfume-service/projects'" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Inventory Management
                        </button>
                    </div>

                    <!-- Card for Perfume Order -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Generate Order</h3>
                        <p class="text-gray-600 mb-4">Manage perfume orders here.</p>
                        <button onclick="window.location.href='/perfume-order/order_details'" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Generate Orders
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
