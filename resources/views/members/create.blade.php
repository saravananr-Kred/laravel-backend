<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($member) ? 'Edit Member' : 'Add Member' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST"
                        action="{{ isset($member) ? route('members.update', $member) : route('members.store') }}">
                        @csrf
                        @if(isset($member))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name', $member->name ?? '')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email', $member->email ?? '')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                                    :value="old('phone', $member->phone ?? '')" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="membership_date" :value="__('Membership Date')" />
                                <x-text-input id="membership_date" class="block mt-1 w-full" type="date"
                                    name="membership_date" :value="old('membership_date', $member->membership_date ?? date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('membership_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ isset($member) ? __('Update Member') : __('Save Member') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>