<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExampleRequest;
use App\Models\Example;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class ExampleController extends Controller
{
    private $module = "example.";

    /**
     * Constructor to share the 'module' variable with all views in this controller.
     */
    public function __construct()
    {
        // Share the 'module' variable with all views in this controller
        View::share('module', $this->module);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view($this->module . 'index');
    }

    /**
     * Display the specified resource with filters and pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        // Set the number of items per page for pagination
        $perPage = 10;

        // Retrieve all request data
        $requestData = $request->all();

        try {
            $obj = new Example();
            // Fetch filtered and paginated data using the model's method
            $result = $obj->getListWithFilter([
                'select' => ['*'], // Specify the columns to select (default is all)
                'paginate' => $perPage, // Pagination setting
                'requestData' => $requestData, // Any additional filters or parameters from the request
                'path' => route($this->module . "list") // Define the base URL for pagination links
            ]);

            // Return the partial view with the paginated and filtered data
            return view($this->module . 'partials.list', compact('result', 'perPage', 'requestData'));
        } catch (\Exception $e) {
            // Log the error in case of failure (useful for debugging)
            Log::error('Failed to fetch the list: ' . $e->getMessage());

            // Optionally, return an error response or fallback view
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the list.'
            ], 500); // Internal Server Error status
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Return the 'create' view with proper module context
        return view($this->module . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ExampleRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExampleRequest $request)
    {
        // Validate and retrieve validated data from the request
        $data = $request->validated();

        // Handle the 'image' file upload if it exists
        if ($request->hasFile('image')) {
            // Store the image and update the data array
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        // Handle the 'file' file upload if it exists
        if ($request->hasFile('file')) {
            // Store the file and update the data array
            $data['file'] = $request->file('file')->store('files', 'public');
        }

        try {
            // Create a new Example record in the database
            $row = Example::create($data);

            // Return a success response with the newly created data
            return response()->json([
                'status' => 'success',
                'message' => 'Example created successfully',
                'data' => $row->only(['id', 'name', 'email', 'phone', 'gender', 'image', 'file']),
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Example creation failed: ' . $e->getMessage());

            // Return a generic error response
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500); // Internal Server Error status code
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Example  $example
     * @return \Illuminate\Http\Response
     */
    public function edit(Example $example)
    {
        // Check if the example exists
        if (!$example) {
            // Return an error response if not found
            return response()->json([
                'status' => 'error',
                'message' => 'Example not found.'
            ], 404); // 404 for resource not found
        }

        // Return the view with the example data
        return view($this->module . 'edit', compact('example'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ExampleRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExampleRequest $request, $id)
    {
        // Validate the incoming request data
        $data = $request->validated();

        try {
            // Find the Example model by ID, or fail with a 404 if not found
            $obj = Example::findOrFail($id);

            // Handle the 'image' file upload if it exists
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($obj->image) {
                    Storage::disk('public')->delete($obj->image);
                }

                // Store the new image
                $data['image'] = $request->file('image')->store('images', 'public');
            }

            // Handle the 'file' file upload if it exists
            if ($request->hasFile('file')) {
                // Delete the old file if it exists
                if ($obj->file) {
                    Storage::disk('public')->delete($obj->file);
                }

                // Store the new file
                $data['file'] = $request->file('file')->store('files', 'public');
            }

            // Update the model with the validated data
            $obj->update($data);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Example updated successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle case where the model is not found (404)
            return response()->json([
                'status' => 'error',
                'message' => 'Example not found'
            ], 404);
        } catch (\Exception $e) {
            // Log any unexpected exceptions and return an error response
            Log::error('Example update failed: ' . $e->getMessage());

            // Return a generic error response
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $example = Example::findOrFail($id);
            $example->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Example deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Example not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Example deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
