<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicineUpdateRequest;
use App\Services\MedicineFilterService;
use App\Models\Medicine;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Gate;
use App\Middleware\LocaleMiddleware;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\ShowMedicineRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Exception;

class MedicineController extends Controller
{
    use ApiResponseTrait;

    // Get medicines
    public function index(Request $request)
    {
        // Pagination
        $perPage = 7;

        // Query the medicines
        $medicines = Medicine::query();

        // Apply filters
        $this->applyFilters($medicines, $request);

        // Apply sorting
        $this->applySorting($medicines, $request);

        // Pagination logic
        $medicines = $request->page ? $medicines->simplePaginate($perPage) : $medicines->get();

        return response()->json([
            'count' => $medicines->count(),
            'data' => $medicines,
            'message' => __('messages.medicines.fetched'),
            'status' => '1'
        ]);
    }

    // Filters
    private function applyFilters($query, Request $request)
    {
        // Filtering by customer
        if ($request->has('ordered_by')) {
            $search = $request->input('ordered_by');
            $query->searchByCustomerName($search);  // Using the search scope
        }

        // Filtering by price
        if ($request->has('price_min') || $request->has('price_max')) {
            $minPrice = $request->input('price_min');
            $maxPrice = $request->input('price_max');
            $query->priceRange($minPrice, $maxPrice);  // Using the price range scope
        }
    }

    // Sorting
    private function applySorting($query, Request $request)
    {
        // Sorting
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order');
            $query->sortBy($sortBy, $sortOrder);  // Using sort scope
        }
        // Default sorting
        $query->sortBy('name', 'asc');
    }
    // Create a medicine
    public function store(StoreMedicineRequest $request, Medicine $medicine)
    {
        // Authorization
        // $this->authorize('create', $medicine);

        // $medicine = Medicine::create(
        //     [
        //         'name' => $request->name,
        //         'description' => $request->description,
        //         'stock' => $request->stock,
        //         'price' => $request->price
        //     ]
        // );

        // return response()->json([
        //     'data' => $medicine,
        //     'message' => __('messages.medicines.created')
        // ]);
        // $this->authorize('create', Medicine::class);
        $medicinesData = $request->input('data'); // Assuming the data is sent as 'data' in the request
        $medicines = [];

        foreach ($medicinesData as $medicineData) {
            $medicine = Medicine::create([
                'name' => $medicineData['name'],
                'description' => $medicineData['description'],
                'stock' => $medicineData['stock'],
                'price' => $medicineData['price']
            ]);

            // Store the created medicine in the array
            $medicines[] = $medicine;

            // Append the medicine data to a file
            Storage::append('medicines.txt', json_encode($medicineData));
        }

        return response()->json([
            'data' => $medicines,
            'message' => __('messages.medicines.created')
        ]);
    } 
        
    // Show single medicine
    public function show(Medicine $medicine)
    {
        // Authorization
        $this->authorize('view', $medicine);
        
        return response()->json([
            'data' => $medicine,
            'message' => __('messages.medicines.found')
        ]);
    }

    // Update medicine
    public function update(MedicineUpdateRequest $request, Medicine $medicine)
    {
        
        if ($request->name) {
            $medicine->name = $request->name;
        }

        if ($request->has('description')) {
            $medicine->description = $request->input('description');
        }

        if ($request->has('stock')) {
            $medicine->stock = $request->input('stock');
        }

        if ($request->has('price')) {
            $medicine->price = $request->input('price');
        }

        // Save the updated medicine
        $medicine->save();

        return response()->json([
            'data' => $medicine,
            'message' => __('messages.medicines.updated')
        ]);
    }

    // Delete medicine
    public function destroy(Medicine $medicine)
    {
        $this->authorize('delete', $medicine);
        
        $medicine->delete();
        return response()->json([
            'data' => $medicine,
            'message' => __('messages.medicines.deleted')
        ]);
    }
}