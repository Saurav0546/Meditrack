<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderUpdateRequest;
use App\Models\Order;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Reward;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Get orders
    public function index(Request $request)
    {
        $orders = Order::all();

        $orders = Order::with('medicines');

        // Searching - Filter orders by customer name
        if ($request->has('search')) {
            $search = $request->input('search');
            $orders->where('customer_name', 'like', '%' . $search . '%');
        }
        $orders = $orders->get();

        // Return JSON response
        return response()->json([
            'data' => $orders,
            'message' => __('messages.orders.fetched')
        ], 200);
    }

    // Get single order
    public function show($id)
    {
        return Order::with('medicines')->findOrFail($id);
        return response()->json([
            'data' => $order,
            'message' => __('messages.orders.fetched')
        ], 200);
    }

    // Create order
    public function store(StoreOrderRequest $request)
    {
        // Create the order
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'order_date' => $request->order_date,
        ]);
        // // Calculating total price based an order quantity
        $medicinesData = [];
        $totalPrice = 0;

        if ($request->has('medicines')) {
            foreach ($request->medicines as $medicine) {
                if (isset($medicine['id']) && isset($medicine['quantity'])) {
                    $medicineRecord = Medicine::find($medicine['id']);
                    if ($medicineRecord) {
                        $price = $medicineRecord->price;
                        $quantity = $medicine['quantity'];
                        $totalPrice += $price * $quantity;
                        $medicinesData[$medicine['id']] = ['quantity' => $quantity];
                    }
                }
            }
        }
        $useRewardPoints = $request->input('use_reward_points', false); 
        $rewardPointsUsed = 0;
        $finalPrice = $totalPrice;

        if ($useRewardPoints) {
            $user = auth()->user();
            $maxDiscountableAmount = floor($totalPrice / 10);  
            $rewardPointsAvailable = $user->reward_points;

            if ($rewardPointsAvailable > 0
            ) {
                $rewardPointsUsed = min($rewardPointsAvailable, $maxDiscountableAmount);
                $finalPrice = $totalPrice - $rewardPointsUsed;

                $user->reward_points -= $rewardPointsUsed;
                $user->save();
            }
        }
        
        $order->medicines()->attach($medicinesData);
        $order->total_price = $finalPrice;
        $order->save();

        $rewardPoints = floor($totalPrice / 10);

        return response()->json([
            'data' => $order->load('medicines'),  
            'original_price' => $totalPrice,      
            'discounted_price' => $finalPrice,   
            'reward_points_used' => $rewardPointsUsed, 
            'reward_points_remaining' => $user->reward_points, 
            'message' => __('messages.orders.created') 
        ], 201);
    }

    // Update order
    public function update(OrderUpdateRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->only(['customer_name', 'order_date']));

        if ($request->has('medicines')) {
            $order->medicines()->detach();
            foreach ($request->medicines as $medicine) { {
                    $order->medicines()->attach($medicine['id'], ['quantity' => $medicine['quantity']]);
                }
            }
        }
        return response()->json([
            'data' => $order->load('medicines'),
            'message' => __('messages.orders.updated')
        ], 200);
    }

    // Delete order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json([
            'message' => __('messages.orders.deleted')
        ], 204);
    }
}