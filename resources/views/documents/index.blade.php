<x-app-layout>
    @section('title', 'Document Management')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Document Management</h1>
                <p class="text-muted-foreground mt-1">Securely store and manage employee contracts, tax forms, and company policies.</p>
            </div>
            <a href="{{ route('documents.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                Upload Document
            </a>
        </div>

        <!-- Search and Filter -->
        <form method="GET" action="{{ route('documents.index') }}" class="flex flex-col md:flex-row gap-4 items-center bg-white p-4 rounded-lg shadow-sm border">
            <div class="relative w-full md:w-96">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files..." class="w-full pl-9 pr-4 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
            </div>
            <select name="category" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-white border border-border rounded-md hover:bg-muted transition-colors">Filter</button>
        </form>

        <!-- Documents Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="font-serif font-semibold">Recent Files</h3>
                <p class="text-sm text-muted-foreground">Manage your organization's digital assets</p>
            </div>
            <table class="w-full">
                <thead class="bg-muted/30">
                    <tr>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Name</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Category</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Size</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Uploaded</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-muted-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($documents as $document)
                    <tr class="hover:bg-muted/5 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded bg-muted/20 flex items-center justify-center text-primary">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-foreground group-hover:text-primary transition-colors cursor-pointer">{{ $document->name }}</div>
                                    <div class="text-xs text-muted-foreground uppercase">{{ $document->file_type_upper }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-normal rounded-full {{ $document->category_badge_class }}">
                                {{ $document->category ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-muted-foreground">{{ $document->file_size }}</td>
                        <td class="px-6 py-4 text-sm text-muted-foreground">{{ $document->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('documents.download', $document) }}" class="p-2 text-muted-foreground hover:text-primary">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </a>
                                <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('Delete this document?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-muted-foreground hover:text-red-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-muted-foreground">
                            No documents found. <a href="{{ route('documents.create') }}" class="text-primary hover:underline">Upload your first document</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $documents->links() }}
    </div>
</x-app-layout>
