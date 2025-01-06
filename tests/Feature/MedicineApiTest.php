<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Medicine;
use Tests\TestCase;

class MedicineApiTest extends TestCase
{
    use RefreshDatabase;

    // Tests for the MedicineController

    public function it_can_create_a_medicine()
    {
        Medicine::factory()->count(5)->create();

        $response = $this->getJson('/api/medicines');

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data']);
    }
    public function it_can_get_all_medicines()
    {
        Medicine::factory()->count(5)->create();

        $response = $this->getJson('/api/medicines');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }
    public function it_can_get_a_single_medicine()
    {
        $medicine = Medicine::factory()->create();

        $response = $this->getJson('/api/medicines/' . $medicine->id);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Success']);
    }

    public function it_can_update_a_medicine()
    {
        $medicine = Medicine::factory()->create();
        $data = ['stock' => 120];

        $response = $this->putJson('/api/medicines/' . $medicine->id, $data);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Medicine updated successfully']);
    }
    public function it_can_delete_a_medicine()
    {
        $medicine = Medicine::factory()->create();

        $response = $this->deleteJson('/api/medicines/' . $medicine->id);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Medicine deleted successfully']);
    }   
    
}
