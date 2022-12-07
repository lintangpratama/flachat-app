<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $data = ["friends" => [],
         "all_user" => []
        ];
        if ($request->search) {
            # code...
            $data["friends"] = User::whereNot("id", auth()->user()->id)
                ->where('name', 'like', '%'.$request->search. '%')->get();
            
        }
        else{
        
        $data["friends"] = (auth()->user()->friends);
        }
       return view("chats", $data);
    }

    public function saveMessage(Request $request)
    {
        # code...
        $roomid = $request->roomId;
        $userid = auth()->user()->id;
        $message = $request->message;
        broadcast(new SendMessage($roomid, $userid, $message));
        Message::create([
            "room_id" => $roomid,
            "user_id" => $userid,
            "message" => $message,

        ]);
        return response()->json([
            "succes" => true,
            "message" => "message succes sended",

        ]);
        
    }

    public function loadMessage($roomid)
    {
        # code...
        $message = Message::where("room_id", $roomid)->orderBy("updated_at", "asc")->get();
        return response()->json([
            "success" => true,
            "data" => $message
        ]);
    }
}
