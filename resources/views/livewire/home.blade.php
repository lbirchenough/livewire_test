<div class="container mx-auto px-4 py-8 max-w-7xl">
    {{-- Hero Section --}}
    <div class="text-center py-8">
        <h1 class="text-5xl font-bold mb-6">Welcome</h1>
        <p class="text-xl mb-1 text-base-content/70">Monitor and analyze your sensor data</p>
    </div>

    @auth
        {{-- Logged In: Show Dashboard Button --}}
        <div class="text-center mb-8">
            <a href="/sensor-data" wire:navigate class="btn btn-primary btn-lg">Dashboard</a>
        </div>
    @else
        {{-- Not Logged In: Show Login Form --}}
        <div class="max-w-md mx-auto">
            <livewire:login />
        </div>
    @endauth

    {{-- Features Section --}}
    <div class="grid md:grid-cols-3 gap-6 mt-16">
        <div class="bg-base-100 rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-32">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold mb-4">Real-Time Data</h2>
            <p class="text-base-content/70">Monitor your sensors with live updates and real-time data visualization.</p>
        </div>
        <div class="bg-base-100 rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-32">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold mb-4">Data Analytics</h2>
            <p class="text-base-content/70">View comprehensive charts and tables to analyze your sensor readings.</p>
        </div>
        <div class="bg-base-100 rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-32">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold mb-4">Secure Access</h2>
            <p class="text-base-content/70">Protected dashboard with secure authentication for your data.</p>
        </div>
    </div>
</div>