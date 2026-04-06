<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $user = User::where('role', 'customer')->inRandomOrder()->first() ?? User::factory()->create();
        $quantity = $this->faker->numberBetween(1, 5);
        $totalPrice = $product->price * $quantity;

        return [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'reference' => 'ORD-' . date('YmdHis') . '-' . Str::random(4),
            'product_name' => $product->name,
            'quantity' => $quantity,
            'price_per_unit' => $product->price,
            'total_price' => $totalPrice,
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'ewallet', 'cod', 'qris']),
            'status' => $this->faker->randomElement(['pending', 'processing', 'delivering', 'completed', 'cancelled']),
            'sync_status' => 'synced',
            'customer_name' => $user->name,
            'customer_phone' => $user->phone,
            'customer_email' => $user->email,
            'delivery_address' => $this->faker->address(),
            'notes' => $this->faker->randomElement(['Tambah pedas', 'Jangan pedas', 'Cepat ya', '', 'Buat makan di tempat']),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelled',
        ]);
    }
}
