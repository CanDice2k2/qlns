<?php

namespace Database\Factories;

use App\Models\NhanVien;
use Illuminate\Database\Eloquent\Factories\Factory;

class NhanVienFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NhanVien::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'hovaten' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'sdt' => $this->faker->tollFreePhoneNumber,
            'diachi' => $this->faker->streetAddress,
            'quequan' => $this->faker->city,
            'ngaysinh' => $this->faker->dateTimeBetween('1985-01-01', '1995-12-31'),
            'gioitinh' => boolval(rand(0, 1)),
            'cmnd' => rand(100000000,999999999),
            'bacluong'  => rand(1, 10),
            'hesoluong' => 1.00,
            'trangthai' => true
        ];
    }
}
