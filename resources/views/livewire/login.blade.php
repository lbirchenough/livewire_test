
<div>
    
    <x-form wire:submit="login">
        <x-input label="Email" wire:model="email" />
        <x-input label="Password" wire:model="password" type="password" />
    
        <x-slot:actions>
            <x-button label="Login" class="btn-primary btn-lg mx-auto" type="submit" spinner="login" />
        </x-slot:actions>
    </x-form>
</div>