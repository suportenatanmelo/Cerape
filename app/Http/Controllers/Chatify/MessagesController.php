<?php

namespace App\Http\Controllers\Chatify;

use App\Models\ChFavorite as Favorite;
use App\Models\ChMessage as Message;
use App\Models\User;
use App\Support\PortalContext;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Chatify\Http\Controllers\MessagesController as BaseMessagesController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class MessagesController extends BaseMessagesController
{
    public function index($id = null)
    {
        if ($id !== null && $id !== 0 && ! $this->canAccessConversationWith((int) $id)) {
            $id = 0;
        }

        return parent::index($id);
    }

    public function idFetchData(Request $request)
    {
        $id = (int) $request['id'];

        if (! $this->canAccessConversationWith($id)) {
            return Response::json([
                'favorite' => false,
                'fetch' => null,
                'user_avatar' => null,
            ], 403);
        }

        return parent::idFetchData($request);
    }

    public function send(Request $request)
    {
        $id = (int) $request['id'];

        if (! $this->canAccessConversationWith($id)) {
            return Response::json([
                'status' => 403,
                'error' => ['status' => 1, 'message' => 'Destinatario nao permitido.'],
                'message' => null,
            ], 403);
        }

        $error = (object) [
            'status' => 0,
            'message' => null,
        ];
        $attachment = null;
        $attachmentTitle = null;

        if ($request->hasFile('file')) {
            $allowedImages = Chatify::getAllowedImages();
            $allowedFiles = Chatify::getAllowedFiles();
            $allowed = array_merge($allowedImages, $allowedFiles);

            $file = $request->file('file');

            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed)) {
                    $attachmentTitle = $file->getClientOriginalName();
                    $attachment = Str::uuid() . '.' . $file->extension();
                    $file->storeAs(config('chatify.attachments.folder'), $attachment, config('chatify.storage_disk_name'));
                } else {
                    $error->status = 1;
                    $error->message = 'Extensao de arquivo nao permitida.';
                }
            } else {
                $error->status = 1;
                $error->message = 'O arquivo enviado e muito grande.';
            }
        }

        if (! $error->status) {
            $message = Chatify::newMessage([
                'from_id' => Auth::id(),
                'to_id' => $id,
                'body' => htmlentities(trim((string) $request['message']), ENT_QUOTES, 'UTF-8'),
                'attachment' => $attachment ? json_encode((object) [
                    'new_name' => $attachment,
                    'old_name' => htmlentities(trim((string) $attachmentTitle), ENT_QUOTES, 'UTF-8'),
                ]) : null,
            ]);

            $messageData = Chatify::parseMessage($message);

            if ($this->hasRealtimeConfigured() && Auth::id() !== $id) {
                Chatify::push('private-chatify.' . $id, 'messaging', [
                    'from_id' => Auth::id(),
                    'to_id' => $id,
                    'message' => Chatify::messageCard($messageData, true),
                ]);
            }
        }

        return Response::json([
            'status' => 200,
            'error' => $error,
            'message' => Chatify::messageCard($messageData ?? []),
            'tempID' => $request['temporaryMsgId'],
        ]);
    }

    public function fetch(Request $request)
    {
        if (! $this->canAccessConversationWith((int) $request['id'])) {
            return Response::json([
                'total' => 0,
                'last_page' => 1,
                'last_message_id' => null,
                'messages' => '<p class="message-hint center-el"><span>Conversa nao permitida.</span></p>',
            ], 403);
        }

        return parent::fetch($request);
    }

    public function seen(Request $request)
    {
        if (! $this->canChatWith((int) $request['id'])) {
            return Response::json(['status' => 0], 403);
        }

        return parent::seen($request);
    }

    public function getContacts(Request $request): JsonResponse
    {
        $users = Message::join('users', function ($join) {
            $join->on('ch_messages.from_id', '=', 'users.id')
                ->orOn('ch_messages.to_id', '=', 'users.id');
        })
            ->where(function ($query) {
                $query->where('ch_messages.from_id', Auth::id())
                    ->orWhere('ch_messages.to_id', Auth::id());
            })
            ->where('users.id', '!=', Auth::id())
            ->select('users.*', DB::raw('MAX(ch_messages.created_at) max_created_at'))
            ->orderBy('max_created_at', 'desc')
            ->groupBy('users.id')
            ->paginate($request->per_page ?? $this->perPage);

        $usersList = $users->items();
        $contacts = count($usersList) > 0
            ? collect($usersList)->map(fn ($user) => Chatify::getContactItem($user))->implode('')
            : '<p class="message-hint center-el"><span>Sua lista de contatos esta vazia.</span></p>';

        return Response::json([
            'contacts' => $contacts,
            'total' => $users->total() ?? 0,
            'last_page' => $users->lastPage() ?? 1,
        ], 200);
    }

    public function updateContactItem(Request $request)
    {
        if (! $this->canAccessConversationWith((int) $request['user_id'])) {
            return Response::json([
                'message' => 'Contato nao permitido.',
            ], 403);
        }

        return parent::updateContactItem($request);
    }

    public function favorite(Request $request)
    {
        if (! $this->canAccessConversationWith((int) $request['user_id'])) {
            return Response::json(['status' => 0], 403);
        }

        return parent::favorite($request);
    }

    public function getFavorites(Request $request)
    {
        $favoritesList = null;
        $allowedIds = User::query()
            ->whereKeyNot(Auth::id())
            ->get()
            ->filter(fn (User $user): bool => $this->canAccessConversationWith((int) $user->getKey()))
            ->pluck('id')
            ->all();
        $favorites = Favorite::where('user_id', Auth::id())
            ->whereIn('favorite_id', $allowedIds);

        foreach ($favorites->get() as $favorite) {
            $user = User::where('id', $favorite->favorite_id)->first();

            if ($user) {
                $favoritesList .= view('Chatify::layouts.favorite', [
                    'user' => $user,
                ]);
            }
        }

        return Response::json([
            'count' => $favorites->count(),
            'favorites' => $favorites->count() > 0 ? $favoritesList : 0,
        ], 200);
    }

    public function search(Request $request)
    {
        $getRecords = null;
        $input = trim((string) filter_var($request['input']));
        $records = $this->searchableUsersQuery()
            ->where('name', 'LIKE', "%{$input}%")
            ->paginate($request->per_page ?? $this->perPage);

        foreach ($records->items() as $record) {
            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'search_item',
                'user' => Chatify::getUserWithAvatar($record),
            ])->render();
        }

        if ($records->total() < 1) {
            $getRecords = '<p class="message-hint center-el"><span>Nada para mostrar.</span></p>';
        }

        return Response::json([
            'records' => $getRecords,
            'total' => $records->total(),
            'last_page' => $records->lastPage(),
        ], 200);
    }

    public function sharedPhotos(Request $request)
    {
        if (! $this->canAccessConversationWith((int) $request['user_id'])) {
            return Response::json([
                'shared' => '<p class="message-hint"><span>Conversa nao permitida.</span></p>',
            ], 403);
        }

        return parent::sharedPhotos($request);
    }

    public function deleteConversation(Request $request)
    {
        if (! $this->canAccessConversationWith((int) $request['id'])) {
            return Response::json(['deleted' => 0], 403);
        }

        return parent::deleteConversation($request);
    }

    protected function searchableUsersQuery()
    {
        $user = Auth::user();

        $query = User::query()->whereKeyNot($user?->getKey());

        if (PortalContext::isFamilyUser($user)) {
            return $query->whereNull('acolhido_id');
        }

        return $query->whereNull('acolhido_id');
    }

    protected function canAccessConversationWith(int $userId): bool
    {
        if ($userId === 0 || $userId === (int) Auth::id()) {
            return true;
        }

        if ($this->searchableUsersQuery()->whereKey($userId)->exists()) {
            return true;
        }

        return Message::query()
            ->where(function ($query) use ($userId) {
                $query->where('from_id', Auth::id())
                    ->where('to_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('from_id', $userId)
                    ->where('to_id', Auth::id());
            })
            ->exists();
    }

    protected function hasRealtimeConfigured(): bool
    {
        return filled(config('chatify.pusher.key'))
            && filled(config('chatify.pusher.secret'))
            && filled(config('chatify.pusher.app_id'));
    }
}
