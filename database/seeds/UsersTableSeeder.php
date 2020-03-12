<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * List of users that will be inserted
     * @var array
     */
    private $items = [];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->prepare("Admin", "admin@mail.com", "12345678");

        User::truncate();
        User::insert($this->items);
    }

    /**
     * Prepare the mass assign object
     * @param string $name
     * @param string $email
     * @param string $password
     *
     * @return void
     */
    private function prepare(string $name, string $email, string $password)
    {
        $now = Carbon::now(new DateTimeZone('America/Sao_Paulo'));
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'created_at' => $now,
            'updated_at' => $now
        ];
        array_push($this->items, $data);
    }
}
