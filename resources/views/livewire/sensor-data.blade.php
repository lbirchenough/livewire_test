<div class="container mx-auto px-4 py-8 max-w-7xl" wire:poll.5s="loadData">
    <h1 class="text-3xl font-bold mb-8">Sensor Data</h1>
    
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
