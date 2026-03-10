<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($book) ? 'Edit Book' : 'Add Book' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST"
                        action="{{ isset($book) ? route('books.update', $book) : route('books.store') }}">
                        @csrf
                        @if(isset($book))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                    :value="old('title', $book->title ?? '')" required autofocus />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="author" :value="__('Author')" />
                                <x-text-input id="author" class="block mt-1 w-full" type="text" name="author"
                                    :value="old('author', $book->author ?? '')" required />
                                <x-input-error :messages="$errors->get('author')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="ISBN" :value="__('ISBN')" />
                                    <x-text-input id="ISBN" class="block mt-1 w-full" type="text" name="ISBN"
                                        :value="old('ISBN', $book->ISBN ?? '')" required />
                                    <x-input-error :messages="$errors->get('ISBN')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="category" :value="__('Category')" />
                                    <x-text-input id="category" class="block mt-1 w-full" type="text" name="category"
                                        :value="old('category', $book->category ?? '')" required />
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="published_year" :value="__('Published Year')" />
                                    <x-text-input id="published_year" class="block mt-1 w-full" type="number"
                                        name="published_year" :value="old('published_year', $book->published_year ?? '')" required />
                                    <x-input-error :messages="$errors->get('published_year')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="available_copies" :value="__('Available Copies')" />
                                    <x-text-input id="available_copies" class="block mt-1 w-full" type="number"
                                        name="available_copies" :value="old('available_copies', $book->available_copies ?? '')" required />
                                    <x-input-error :messages="$errors->get('available_copies')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ isset($book) ? __('Update Book') : __('Save Book') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>