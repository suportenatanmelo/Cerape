<?php

namespace App\Support;

use Chatify\ChatifyMessenger as BaseChatifyMessenger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pusher\Pusher;

class ChatifyMessenger extends BaseChatifyMessenger
{
    public function __construct()
    {
        $key = config('chatify.pusher.key');
        $secret = config('chatify.pusher.secret');
        $appId = config('chatify.pusher.app_id');

        if (filled($key) && filled($secret) && filled($appId)) {
            $this->pusher = new Pusher(
                $key,
                $secret,
                $appId,
                config('chatify.pusher.options'),
            );
        } else {
            $this->pusher = null;
        }
    }

    public function push($channel, $event, $data)
    {
        if (! $this->pusher instanceof Pusher) {
            return null;
        }

        return $this->pusher->trigger($channel, $event, $data);
    }

    public function pusherAuth($requestUser, $authUser, $channelName, $socket_id): JsonResponse|string
    {
        if (! $this->pusher instanceof Pusher) {
            return response()->json([
                'message' => 'Realtime indisponivel: configure as credenciais do Pusher para habilitar atualizacao em tempo real.',
            ], 503);
        }

        $authData = json_encode([
            'user_id' => $authUser->id,
            'user_info' => [
                'name' => $authUser->name,
            ],
        ]);

        if (Auth::check()) {
            if ($requestUser->id == $authUser->id) {
                return $this->pusher->socket_auth(
                    $channelName,
                    $socket_id,
                    $authData
                );
            }

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'Not authenticated'], 403);
    }

    public function getUserWithAvatar($user)
    {
        $avatar = (string) ($user->avatar ?? '');

        if (($avatar === '' || $avatar === config('chatify.user_avatar.default')) && config('chatify.gravatar.enabled')) {
            $imageSize = config('chatify.gravatar.image_size');
            $imageset = config('chatify.gravatar.imageset');
            $user->avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim((string) $user->email))) . '?s=' . $imageSize . '&d=' . $imageset;

            return $user;
        }

        $user->avatar = $this->getUserAvatarUrl($avatar);

        return $user;
    }

    public function getUserAvatarUrl($userAvatarName)
    {
        $disk = Storage::disk(config('chatify.storage_disk_name'));
        $avatar = trim((string) $userAvatarName);

        if ($avatar === '') {
            $avatar = config('chatify.user_avatar.default');
        }

        if (Str::startsWith($avatar, ['http://', 'https://', '//', 'data:'])) {
            return $avatar;
        }

        if ($url = PdfImage::publicUrl($avatar)) {
            return $url;
        }

        if (str_contains($avatar, '/')) {
            return $disk->url($avatar);
        }

        return $disk->url(trim(config('chatify.user_avatar.folder'), '/') . '/' . $avatar);
    }
}
