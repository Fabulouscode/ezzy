<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatHistory;
use App\Models\ChatLastActivity;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class EjabbredChatController extends BaseApiController
{
    public function chat_history(Request $request)
    {
        if ($request->user_id != '') {
            $limit = 10;
            $user_id = $request->user_id.'@'.config('app.EJABBERD_HOST');
            $query = ChatHistory::where('username', $request->user()->id)->whereRaw("bare_peer = '{$user_id}'");

            if ($request->last_id != '') {
                $query->where('id', '<', $request->last_id);
            }
            $history = $query->limit($limit)->orderBy('id', 'Desc')->get()->map(function ($hsitem) {
                $hsitem->content = str_replace('\\n', "\n", $hsitem->content);
                return $hsitem;
            });
            if ($history->count() > 0)
                return self::sendSuccess($history, 'Chat History listing');
            else
                return self::sendError('', 'Not Found Chat Users', 500);
        } else
            return self::sendError('', 'Not Found Chat Users', 500);
    }

    public function users(Request $request)
    {
        $limit = 10;
        $user_chat_id = $request->user()->id;
        $chat_users = \DB::connection('chat')->table('archive')->where('username', $user_chat_id)->get()->pluck('bare_peer');
        $query = User::select(
            '*',
            DB::raw("(SELECT COUNT(*) FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.peer = users.id AND ch.username = {$user_chat_id}) as read_count"),
            // DB::raw("(SELECT content FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id}  )) as last_message"),
            DB::raw("(SELECT bare_peer FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id} )) as bare_peer"),
            DB::raw("(SELECT username FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id} )) as userChatname"),
            DB::raw("(SELECT created_at FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id} )) as createdAt"),
            // DB::raw("(SELECT timestamp FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id})) as timestamp"),
            // DB::raw("(SELECT from_id FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id} )) as sender"),
            // DB::raw("(SELECT to_id FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id} )) as receiver"),
            // DB::raw("(SELECT senderName FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id} )) as senderName"),
            // DB::raw("(SELECT senderAvtar FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id} )) as senderAvtar"),
            // DB::raw("(SELECT avatar FROM `" . config('database.connections.chat.database') . "`.`archive` as ch WHERE ch.id = (SELECT MAX(ch2.id) FROM `" . config('database.connections.chat.database') . "`.archive as ch2 WHERE ch2.peer = users.id AND ch2.username = {$user_chat_id} )) as avatar")

        )
            ->whereIn('id', $chat_users);

        // $query->havingRaw('`last_message` IS NOT NULL');

        if (!empty(request('last_id'))) {
            $query->where('users.id', '<', request('last_id'));
        }

        if (!empty(request('search'))) {
            $query->where('users.name', 'like', '%' . request('search') . '%');
        }

        $users = $query->limit($limit)->orderBy('createdAt', 'Desc')->orderBy('users.id', 'Desc')->get();

        if ($users->count() > 0) {
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'id' => $user->id,
                    // 'title' => $user->name,
                    // 'picture' => $user->avatar_url,
                    'read_count' => $user->read_count,
                    // 'message' => str_replace('\\n', "\n", $user->last_message),
                    'type' => $user->type,
                    // 'created_at' => $user->createdAt,
                    // 'timestamp' => $user->timestamp,
                    // 'sender' => $user->sender,
                    // 'receiver' => $user->receiver,
                    // 'deleted_at' => $user->deleted_at,
                    'chat_history' => $this->chat_history_data($user->userChatname, $user->bare_peer)
                    // 'senderName' => $user->senderName,
                    // 'senderAvtar' => $user->senderAvtar,
                    // 'txt_json' => json_decode($user->txt_json),
                ];
            }
            return self::sendSuccess($data, 'Chat Users listing');
        } else
            return self::sendError('', 'Not Found Chat Users', 500);
    }

    public function chat_history_data($sender, $receiver)
    {
        $query = ChatHistory::where('username', $sender)->whereRaw("bare_peer = '{$receiver}'");
        
        $history = $query->orderBy('id', 'Desc')->get()->map(function ($hsitem) {
            $hsitem->txt = str_replace('\\n', "\n", $hsitem->txt);
            return $hsitem;
        });
        return $history;
    }

    public function chatHistoryClear(Request $request)
    {
        if ($request->user_id != '') {
            $user_id = $request->user_id.'@'.config('app.EJABBERD_HOST');
            $query = ChatHistory::where('username', $request->user()->id)->whereRaw("bare_peer = '{$user_id}'");

            if ($query->delete())
                return self::sendSuccess([], 'Chat Users listing');
            else
                return self::sendSuccess([], 'Not Clear Chat History');
        } else
            return self::sendSuccess([], 'Must be required user id');
    }
 
    public function getLastActivity($user_id)
    {
        if ($user_id != '') {
            $query = ChatLastActivity::where('username', $user_id)->first();
            return self::sendSuccess($query, 'user last activity');
        } else
            return self::sendSuccess([], 'Must be required user id');
    }
}
