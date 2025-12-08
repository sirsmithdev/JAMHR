<x-app-layout>
    @section('title', 'Upload Document')

    <div class="space-y-8">
        <div>
            <a href="{{ route('documents.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Documents
            </a>
            <h1 class="text-3xl font-serif text-foreground">Upload Document</h1>
            <p class="text-muted-foreground mt-1">Add a new document to the system.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-foreground mb-1">Document Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('name') border-red-500 @enderror" placeholder="e.g., Employee Handbook 2025">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-foreground mb-1">Category</label>
                    <select name="category" id="category" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('category') border-red-500 @enderror">
                        <option value="">Select category...</option>
                        <option value="Contracts" {{ old('category') == 'Contracts' ? 'selected' : '' }}>Contracts</option>
                        <option value="Tax Forms" {{ old('category') == 'Tax Forms' ? 'selected' : '' }}>Tax Forms</option>
                        <option value="Policies" {{ old('category') == 'Policies' ? 'selected' : '' }}>Policies</option>
                        <option value="Templates" {{ old('category') == 'Templates' ? 'selected' : '' }}>Templates</option>
                        <option value="Reports" {{ old('category') == 'Reports' ? 'selected' : '' }}>Reports</option>
                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-foreground mb-1">Associated Employee (Optional)</label>
                    <select name="employee_id" id="employee_id" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('employee_id') border-red-500 @enderror">
                        <option value="">Company-wide document</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-muted-foreground mt-1">Leave blank for company-wide documents like policies.</p>
                    @error('employee_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-foreground mb-1">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Brief description of the document...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Upload File</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-border border-dashed rounded-lg hover:border-primary/50 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <div class="flex text-sm text-muted-foreground">
                                <label for="file" class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary/80 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="file" name="file" type="file" class="sr-only" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-muted-foreground">PDF, DOC, DOCX, XLS, XLSX up to 10MB</p>
                        </div>
                    </div>
                    <div id="file-name" class="mt-2 text-sm text-muted-foreground"></div>
                    @error('file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-800 text-sm">Document Security</h4>
                            <p class="text-xs text-blue-700 mt-1">All uploaded documents are stored securely and access is restricted based on user permissions.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('documents.index') }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('file-name').textContent = 'Selected: ' + fileName;
            }
        });
    </script>
</x-app-layout>
