<?php

namespace App\Http\Api\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\UserAddMessageRequest;
use App\Http\Requests\UserDeleteMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Message;
use Illuminate\Http\JsonResponse;

class MessageController extends ApiController
{
    /**
     * @param Chat $chat
     * @param UserAddMessageRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function add(Chat $chat, UserAddMessageRequest $request): JsonResponse
    {
        $chatUser = ChatUser::where([
            ['chat_id', '=', $chat->id],
            ['user_id', '=', $request->get('user_id')],
        ])->get();

        if ($chatUser->isEmpty()){
            throw new ApiException('You are not in the chat', ApiException::NOT_FOUND, 404);
        }

        $message = Message::create([
            'text' => $request->text,
            'chat_id' => $chat->id,
            'user_id' => $request->user_id
        ]);

        return $this->success(['message' => new MessageResource($message)]);
    }

    /**
     * @param Chat $chat
     * @param UserDeleteMessageRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function delete(Chat $chat, UserDeleteMessageRequest $request): JsonResponse
    {
        $chatUser = ChatUser::where([
            ['chat_id', '=', $chat->id],
            ['user_id', '=', $request->get('user_id')],
        ])->get();

        if ($chatUser->isEmpty()){
            throw new ApiException('You are not in the chat', ApiException::NOT_FOUND, 404);
        }

        $message = Message::findOrFail($request->get('message_id'))
            ->where('user_id', $request->get('user_id'));

        if ($message->get()->isEmpty()){
            throw new ApiException('Message not found', ApiException::NOT_FOUND, 404);
        }

        $message->delete();

        return $this->success('Message deleted');
    }
}
