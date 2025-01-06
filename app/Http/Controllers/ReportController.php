<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Generating stock report
    public function generateStockReport()
    {
        $medicines = Medicine::leftJoin('medicine_order', 'medicines.id', '=', 'medicine_order.medicine_id')
        ->leftJoin('orders', 'orders.id', '=', 'medicine_order.order_id')
        ->select('medicines.id', 'medicines.name', 'medicines.stock', DB::raw('IFNULL(SUM(medicine_order.quantity), 0) as total_ordered'))
        ->groupBy('medicines.id', 'medicines.name', 'medicines.stock')
        ->get();

        $stockReport = $medicines->map(function ($medicine) {
            $currentStock = max(0, $medicine->stock - $medicine->total_ordered);

            return [
                'medicine_name' => $medicine->name,
                'stock' => $medicine->stock,
                'total_ordered' => $medicine->total_ordered,
                'current_stock' => $currentStock,
            ];
        });
        return response()->json(['stock_report' => $stockReport], 200);
    }
}