<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($borrow) ? 'Edit Borrowing' : 'New Borrowing' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST"
                        action="{{ isset($borrow) ? route('borrows.update', $borrow) : route('borrows.store') }}">
                        @csrf
                        @if(isset($borrow))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="book_id" :value="__('Book')" />
                                <select id="book_id" name="book_id"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required {{ isset($borrow) ? 'disabled' : '' }}>
                                    <option value="">Select a Book</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" {{ old('book_id', $borrow->book_id ?? '') == $book->id ? 'selected' : '' }}>
                                            {{ $book->title }} ({{ $book->available_copies }} left)
                                        </option>
                                    @endforeach
                                </select>
                                @if(isset($borrow))
                                    <input type="hidden" name="book_id" value="{{ $borrow->book_id }}">
                                @endif
                                <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="member_id" :value="__('Member')" />
                                <select id="member_id" name="member_id"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required {{ isset($borrow) ? 'disabled' : '' }}>
                                    <option value="">Select a Member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id', $borrow->member_id ?? '') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @if(isset($borrow))
                                    <input type="hidden" name="member_id" value="{{ $borrow->member_id }}">
                                @endif
                                <x-input-error :messages="$errors->get('member_id')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="issue_date" :value="__('Issue Date')" />
                                    <x-text-input id="issue_date" class="block mt-1 w-full" type="date"
                                        name="issue_date" :value="old('issue_date', $borrow->issue_date ?? date('Y-m-d'))" required />
                                    <x-input-error :messages="$errors->get('issue_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="return_date" :value="__('Return Date')" />
                                    <x-text-input id="return_date" class="block mt-1 w-full" type="date"
                                        name="return_date" :value="old('return_date', $borrow->return_date ?? '')" />
                                    <x-input-error :messages="$errors->get('return_date')" class="mt-2" />
                                </div>
                            </div>

                            @if(isset($borrow))
                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required>
                                        <option value="borrowed" {{ old('status', $borrow->status) == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                        <option value="returned" {{ old('status', $borrow->status) == 'returned' ? 'selected' : '' }}>Returned</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ isset($borrow) ? __('Update Record') : __('Create Record') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>