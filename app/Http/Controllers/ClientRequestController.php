<?php


namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientRequestController extends Controller
{
    public function createWithFiles(Request $request)
    {
        // Ensure the 'files' field exists and contains at least one file
        if (!$request->hasFile('files')) {
            return response()->json([
                'message' => 'The files field is required.',
                'errors' => [
                    'files' => ['The files field is required.']
                ]
            ], 422);
        }
    
        // Validate the request data
        $data = $request->validate([
            'employee_id' => 'required|exists:tblm_employee,id',
            'subject' => 'required|string',
            'description' => 'required|string',
            'files.*' => 'mimes:jpg,jpeg,png,gif,pdf|max:2048', // Validate individual files
        ]);
    
        $data['created_date'] = now();
        $data['status'] = 0; // Default status: Pending
    
        // Create the ClientRequest
        $clientRequest = ClientRequest::create($data);
    
        // Handle file uploads
        $files = $request->file('files');
        $maxFilesAllowed = 5; // Maximum number of files allowed
    
        if (count($files) > $maxFilesAllowed) {
            return response()->json(['message' => 'Maximum file limit reached'], 400);
        }
    
        foreach ($files as $file) {
            // Store the file in public storage
            $filePath = $file->store('client_requests/files', 'public');
    
            // Save the file record to the database
            $newFile = new Image(); // Assuming 'Image' model is used for files
            $newFile->client_request_id = $clientRequest->id;
            $newFile->image_path = $filePath; // You can rename 'image_path' to 'file_path' for better understanding
            $newFile->save();
        }
    
        return response()->json([
            'success' => true,
            'data' => $clientRequest,
            'message' => 'Client request created successfully with files'
        ], 201);
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


  
}

