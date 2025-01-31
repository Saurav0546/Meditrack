<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicineUpdateRequest;
// use App\Services\MedicineFilterService;
use App\Models\Medicine;
use App\Traits\ApiResponseTrait;
// use App\Middleware\LocaleMiddleware;
use App\Http\Requests\StoreMedicineRequest;
// use App\Http\Requests\ShowMedicineRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Exception;

class MedicineController extends Controller
{
    use ApiResponseTrait;

    // Get medicines
    public function index(Request $request)
    {

        $perPage = 7;
        $medicines = Medicine::query();

        // Apply filters
        if ($request->has('ordered_by')) {
            $medicines->searchByCustomerName($request->input('ordered_by'));
        }
        if ($request->has('price_min') || $request->has('price_max')) {
            $medicines->priceRange($request->input('price_min'), $request->input('price_max'));
        }

        // Apply sorting
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $medicines->sortBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $medicines->sortBy('name', 'asc');  // Default sorting
        }

        // Pagination logic
        $medicines = $request->page ? $medicines->simplePaginate($perPage) : $medicines->get();

        return response()->json([
            'count' => $medicines->count(),
            'data' => $medicines,
            'message' => __('messages.medicines.fetched'),
            'status' => '1'
        ]);
    }
    // Create a medicine
    public function store(StoreMedicineRequest $request, Medicine $medicine)
    {
        // Authorization
        // $this->authorize('create', $medicine);

        // Creating a single medicine
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

        // // $this->authorize('create', Medicine::class);

        // // Creating multiple medicines
        // $medicinesData = $request->input('data');
        // $medicines = [];

        // foreach ($medicinesData as $medicineData) {
        //     $medicine = Medicine::create([
        //         'name' => $medicineData['name'],
        //         'description' => $medicineData['description'],
        //         'stock' => $medicineData['stock'],
        //         'price' => $medicineData['price']
        //     ]);

        //     $medicines[] = $medicine;

        //     // Append the medicine data to a file
        //     Storage::append('medicines.txt', json_encode($medicineData));
        // }

        // return response()->json([
        //     'data' => $medicines,
        //     'message' => __('messages.medicines.created')
        // ]);

        $files = [];

        // single medicine creation
        if (!$request->has('data')) {

            $medicine = Medicine::create([
                'name' => $request->name,
                'description' => $request->description,
                'stock' => $request->stock,
                'price' => $request->price
            ]);

            // Checking for files and store them
            if ($request->hasFile('files')) {
                $files = [];

                foreach ($request->file('files') as $file) {
                    $filePath = $file->store('medicines/files', 'public');

                    $fileUrl = Storage::url($filePath);
                    $files[] = $fileUrl;
                }

                // Save the file URLs/paths as JSON in the database
                $medicine->files = json_encode($files);
                $medicine->save();
            }

            return response()->json([
                'data' => $medicine,
                'message' => __('messages.medicines.created')
            ], 201);
        }

        // Multiple medicines creation
        $medicinesData = $request->input('data');
        $medicines = [];

        foreach ($medicinesData as $medicineData) {
            // Create the medicine record
            $medicine = Medicine::create([
                'name' => $medicineData['name'],
                'description' => $medicineData['description'],
                'stock' => $medicineData['stock'],
                'price' => $medicineData['price']
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePath = $file->store(
                        'medicines/files',
                        'public'
                    );
                    $files[] = $filePath;
                }
                $medicine->files = json_encode($files);
                $medicine->save();
            }
            $medicines[] = $medicine;

            Storage::append(
                'medicines.txt',
                json_encode($medicineData)
            );
        }

        return response()->json([
            'data' => $medicines,
            'message' => __('messages.medicines.created')
        ], 201);
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