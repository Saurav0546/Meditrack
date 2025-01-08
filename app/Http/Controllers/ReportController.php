<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Generating stock report
    public function generateStockReport()
    {
        $medicines = Medicine::leftJoin('medicine_order', 'medicines.id', '=', 'medicine_order.medicine_id')
        ->leftJoin('orders', 'orders.id', '=', 'medicine_order.order_id')
        ->select(
            'medicines.name',
            'medicines.stock',
            DB::raw('IFNULL(SUM(medicine_order.quantity), 0) as total_ordered'),
            DB::raw('GREATEST(medicines.stock - IFNULL(SUM(medicine_order.quantity), 0), 0) as current_stock')
        )
        ->groupBy('medicines.id', 'medicines.name', 'medicines.stock')
        ->get();

        // Map the results to the desired format
        $stockReport = $medicines->map(function ($medicine) {
            return [
                'medicine_name' => $medicine->name,
                'stock' => $medicine->stock,
                'total_ordered' => $medicine->total_ordered,
                'current_stock' => $medicine->current_stock,
            ];
        });

        return response()->json(['stock_report' => $stockReport], 200);
        
    }
}