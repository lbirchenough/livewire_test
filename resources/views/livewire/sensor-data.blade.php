<div class="container mx-auto px-4 py-8 max-w-7xl" wire:poll.60s="loadData">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Sensor Data</h1>
        <x-button label="Logout" wire:click="logout" class="btn-outline btn-error" />
    </div>
    
    {{-- Chart --}}
    <div class="mb-12 bg-base-100 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Sensor Readings Over Time</h2>
        <x-chart wire:model="sensorChart" />
    </div>
    
    {{-- Table --}}
    <div class="bg-base-100 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Data Table</h2>
        @php
            $headers = [
                // ['key' => 'id', 'label' => '#'],
                ['key' => 'sensor_name', 'label' => 'Sensor Name'],
                ['key' => 'value', 'label' => 'Value'],
                ['key' => 'unit', 'label' => 'Unit'],
                ['key' => 'type', 'label' => 'Type'],
                ['key' => 'time', 'label' => 'Time'],
            ];
        @endphp

        <x-table :headers="$headers" :rows="$sensorData" striped />
    </div>
</div>
