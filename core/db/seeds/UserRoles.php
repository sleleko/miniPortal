<?php

use App\Model\UserRole;
use Phinx\Seed\AbstractSeed;

class UserRoles extends AbstractSeed
{
    public function run()
    {
        $roles = [
            'Administrator' => [
                'scope' => ['profile', 'users', 'debug'],
            ],
            'User' => [
                'scope' => ['profile'],
            ],
        ];

        foreach ($roles as $group => $data) {
            if (!$record = UserRole::query()->where('title', '=', $group)->first()) {
                $record = new UserRole(['title' => $group]);
            }
            $record->fill($data);
            $record->save();
        }
    }
}
