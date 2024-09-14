<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h4 class="font-semibold text-lg text-gray-900 my-5 dark:text-gray-100">
                        Keywords
                    </h4>
                    <div class="text-gray-900 dark:text-gray-100 my-6">
                        <form action="{{ route('keywords.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex items-center ">

                                <div class="flex-1">
                                    <x-text-input id="url" class="block  w-full" type="text" name="name" placeholder="Enter Keyword..." value="{{ old('name') }}" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div class="">
                                    <x-primary-button class="ms-3">
                                        {{ __('Create') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($keywords->count() <= 0)
                    <h4 class="font-semibold text-base text-gray-900 my-5 dark:text-gray-100">
                        No data found...
                    </h4>
                    @else
                        
                        <div class="overflow-x-auto mt-10">
                            
                            <table class="table-auto text-center  min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 dark:bg-gray-600">
                                    <tr>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">S/N</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Keyword</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 ">
                                    @foreach ($keywords as $key => $record)
                                    <tr >
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $key + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100 flex justify-center items-center gap-4">
                                            <a  href="{{ route('keywords.status',$record->id) }}" class="text-white px-3 py-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                @if ($record->is_active)
                                                    {{ __('Deactivate') }}
                                                    @else
                                                    {{ __('Activate') }}
                                                @endif
                                            </a>
                                            @if (!$record->is_active)
                                                <form method="POST" action="{{ route('keywords.destroy',$record->id) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="bg-red-600 text-white px-3 py-2 border-transparent rounded" type="submit">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Display pagination links -->
                        <div class="mt-4 text-gray-900 my-5 dark:text-gray-100">
                            {{ $keywords->links() }}
                        </div>
                        
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
