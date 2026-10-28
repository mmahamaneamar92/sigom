<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Application De Gestion Des Materiels Informatiques') }}
        </h2>
    </x-slot>
    <x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-dark dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bienvenue Sur Votre Application de Gestion de Matériel Informatique") }}
                </div>
            </div>
        </div>
    </div>
    </x-guest-layout>
</x-app-layout>
