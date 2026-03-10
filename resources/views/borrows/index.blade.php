<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Borrow Records') }}
            </h2>
            <a href="{{ route('borrows.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                New Borrowing
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                                    Book</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                                    Member</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                                    Issue Date</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                                    Return Date</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($borrowRecords as $borrow)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $borrow->book->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $borrow->member->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $borrow->issue_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $borrow->return_date ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $borrow->status === 'borrowed' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($borrow->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('borrows.edit', $borrow) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                        <form action="{{ route('borrows.destroy', $borrow) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $borrowRecords->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>