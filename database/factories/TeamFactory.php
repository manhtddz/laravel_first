<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'ins_id' => 1,
            'upd_id' => null,
            'del_flag' => IS_NOT_DELETED,
        ];
    }
}
