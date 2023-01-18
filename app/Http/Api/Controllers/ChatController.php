<?php

namespace App\Http\Api\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\PaginateUserChatRequest;
use App\Http\Requests\UserChatListRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends ApiController
{
    /**
     * @param UserChatListRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function getList(UserChatListRequest $request): JsonResponse
    {
        $user = User::find($request->get('user_id'));

        if (empty($user)){
            throw new ApiException('User not found', ApiException::NOT_FOUND, 404);
        }

        $chatUser = ChatUser::where('user_id', $request->get('user_id'))->get();

        if ($chatUser->isEmpty()){
            throw new ApiException('No chats found for this user', ApiException::NOT_FOUND, 404);
        }

        $chatUser->load('chat');

        return $this->success(['chats' => ChatResource::collection($chatUser->pluck('chat'))]);
    }

    /**
     * @param Chat $chat
     * @param PaginateUserChatRequest $request
     * @return AnonymousResourceCollection
     * @throws ApiException
     */
    public function getMessagesList(Chat $chat, PaginateUserChatRequest $request)
    {
        $chatUser = ChatUser::where([
            ['chat_id', '=', $chat->id],
            ['user_id', '=', $request->get('user_id')],
        ])->get();

        if($chatUser->isEmpty()){
            throw new ApiException('User not found in this chat', ApiException::NOT_FOUND, 404);
        }

        $messages = $chat->message()
            ->orderBy('id', 'desc')
            ->paginate(config('content.pagination_size'),
                ['*'],
                'page',
                $request->get('page')
            );

        return MessageResource::collection($messages);
    }
}
