<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(rand(7,15))->create();
        $chats = Chat::factory(rand(7,15))->create();

        foreach ($chats as $chat) {
            foreach ($users as $user) {
                if (rand(0,1)){
                    ChatUser::factory(1, ['user_id' => $user->id, 'chat_id' => $chat->id])->create();
                    Message::factory(rand(1,8), ['user_id' => $user->id, 'chat_id' => $chat->id])->create();
                }
            }
        }
    }
}
