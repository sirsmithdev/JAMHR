<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('employee')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->when($request->category, function ($q) use ($request) {
                $q->where('category', $request->category);
            });

        $documents = $query->orderBy('created_at', 'desc')->paginate(15);

        $categories = Document::distinct()->pluck('category')->filter();

        return view('documents.index', compact('documents', 'categories'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        $categories = ['Contracts', 'Tax Forms', 'Policy', 'Payroll', 'Other'];
        return view('documents.create', compact('employees', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'employee_id' => 'nullable|exists:employees,id',
            'category' => 'nullable|string|max:50',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'name' => $validated['name'],
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $this->formatFileSize($file->getSize()),
            'employee_id' => $validated['employee_id'],
            'category' => $validated['category'],
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    public function download(Document $document)
    {
        return Storage::disk('public')->download($document->file_path, $document->name);
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted.');
    }

    private function formatFileSize($bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        return number_format($bytes / 1024, 0) . ' KB';
    }
}
