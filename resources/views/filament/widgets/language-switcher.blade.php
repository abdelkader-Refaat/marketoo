<x-filament::dropdown placement="bottom-end">
    <x-slot name="trigger">
        <button class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700">
            🌍
        </button>
    </x-slot>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item wire:click="switchLanguage('en')">
            🇬🇧 English
        </x-filament::dropdown.list.item>
        <x-filament::dropdown.list.item wire:click="switchLanguage('ar')">
            🇸🇦 العربية
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>
