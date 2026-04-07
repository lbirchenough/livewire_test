<div class="container mx-auto px-4 py-8 max-w-7xl" wire:poll.{{ $pollInterval }}s="loadData">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Sensor Data</h1>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium">Refresh Period:</span>
            <div class="join">
                <button wire:click="$set('pollInterval', 10)" @class(['join-item btn btn-sm', 'border-2 border-white' => $pollInterval === 10])>10s</button>
                <button wire:click="$set('pollInterval', 30)" @class(['join-item btn btn-sm', 'border-2 border-white' => $pollInterval === 30])>30s</button>
                <button wire:click="$set('pollInterval', 60)" @class(['join-item btn btn-sm', 'border-2 border-white' => $pollInterval === 60])>60s</button>
            </div>
            <x-button label="Logout" wire:click="logout" class="btn-outline btn-error" />
        </div>
    </div>
    
    {{-- Distance Chart --}}
    <div class="bg-base-100 rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Distance Last Hour All Sensors</h2>
        <x-chart wire:model="sensorChart" />
    </div>

    {{-- Switch Chart --}}
    <div class="bg-base-100 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Switch Last Hour All Sensors</h2>
        <div style="height: 280px">
            <x-chart wire:model="switchChart" />
        </div>
    </div>

</div>
