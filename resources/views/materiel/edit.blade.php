<x-guest-layout>

    <div class="flex items-center justify-end mt-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif
    </div>

    <form method="post" action="{{ route('materiels.update', $materiel->id) }}">

        <!-- Désignation -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="designation" :value="__('Désignation')" />
            <x-text-input id="designation" class="block mt-1 w-full" type="text" name="designation"
                value="{{ $materiel->designation }}" />
            <x-input-error :messages="$errors->get('designation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
