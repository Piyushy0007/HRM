<?php


namespace App\Http\Controllers;

use App\Models\CreateRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CreateRequestController extends Controller
{
    public function index()
{
    $createRequests = CreateRequest::all(); 
        return response()->json($createRequests);
}






    public function store(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'selected_employee' => 'required|integer',
            'request_type' => 'required|string|max:255',
            'reason' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',         ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Prepare data to store
        $requestData = [
            'selected_employee' => $request->selected_employee,
            'request_type' => $request->request_type,
            'reason' => $request->reason,
        ];

        // Handle file upload if present
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                // Ensure image is stored in public storage
                $imagePath = $image->store('uploads', 'public');
                $requestData['image_path'] = $imagePath;

                // Log the image path to verify the file is being stored
                Log::info('Image stored at: ' . $imagePath);
            } catch (\Exception $e) {
                // Log error and respond with an error message if image upload fails
                Log::error('Error uploading image: ' . $e->getMessage());
                return response()->json(['error' => 'Error uploading image'], 500);
            }
        }

        try {
            $newRequest = CreateRequest::create($requestData);
            return response()->json($newRequest, 201);  // Respond with the newly created request data
        } catch (\Exception $e) {
            Log::error('Error creating request: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create request.'], 500);
        }
    }
}
   



