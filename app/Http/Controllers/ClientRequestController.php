<?php


namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientRequestController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:tblm_employee,id',
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);

        $data['created_date'] = now();
        $data['status'] = 0; // Default status: Pending

        $clientRequest = ClientRequest::create($data);

        return response()->json(['success' => true, 'data' => $clientRequest], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
            'approval_employee_id' => 'required_if:status,1|exists:tblm_employee,id',
        ]);

        $clientRequest = ClientRequest::findOrFail($id);

        $clientRequest->status = $request->status;

        if ($request->status == 1) {
            $clientRequest->bill_date = now();
            $clientRequest->approval_employee_id = $request->approval_employee_id;
        } elseif ($request->status == 2) {
            $clientRequest->approval_employee_id = null;
        }

        $clientRequest->save();

        return response()->json(['success' => true, 'data' => $clientRequest]);
    }


    public function uploadFile(Request $request, $id)
{
    // Validate the uploaded file
    $request->validate([
        'file' => 'required|mimes:jpg,jpeg,png,gif,pdf|max:2048', // Allow images and PDF files (max size 2MB)
    ]);

    // Find the ClientRequest by ID
    $clientRequest = ClientRequest::find($id);

    if (!$clientRequest) {
        return response()->json(['message' => 'Client Request not found'], 404);
    }

    // Count how many files are already uploaded for this ClientRequest
    $fileCount = Image::where('client_request_id', $id)->count();
    $maxFilesAllowed = 5; // Example: max 5 files per ClientRequest

    if ($fileCount >= $maxFilesAllowed) {
        return response()->json(['message' => 'Maximum file limit reached'], 400);
    }

    // Store the file in public storage
    $file = $request->file('file');
    $filePath = $file->store('client_requests/files', 'public'); // Store in 'public' disk

    // Save the file record to the database
    $newFile = new Image(); // Assuming 'Image' model is used for files too
    $newFile->client_request_id = $id;
    $newFile->image_path = $filePath; // You can rename 'image_path' to 'file_path' for better understanding
    $newFile->save();

    return response()->json([
        'message' => 'File uploaded successfully',
        'file_path' => Storage::url($filePath), // Return the URL of the uploaded file
        'client_request_id' => $id
    ]);
}
}

