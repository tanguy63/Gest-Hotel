<?php

namespace App\Actions\Fortify;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'hotel_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {

            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);

            $hotel = Hotel::create([
                'name' => $input['hotel_name'],
                'slug' => Str::slug($input['hotel_name'])
                    . '-' . Str::lower(Str::random(6)),
                'email' => $input['email'],
                'phone' => $input['phone'] ?? null,
            ]);

            $hotel->users()->attach($user->id, [
                'role' => 'owner',
                'is_active' => true,
            ]);

            return $user;
        });
    }
}