<x-guest-layout>
    <form method="POST" action="{{ route('materiels.update', $materiel->designation) }}">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <x-input-label for="designation" :value="__('Designation')" />
            <x-text-input id="designation" class="block mt-1 w-full" type="text" name="designation" value="{{ $materiel->designation }}" />
            <x-input-error :messages="$errors->get('designation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">


            <x-primary-button class="ml-4">
                {{ __('Enregistrer') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
