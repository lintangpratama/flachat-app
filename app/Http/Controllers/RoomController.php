<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    //
   public function create(Request $request)
   {
    # code...
    $me = auth()->user()->id;
    $friend = $request->friend_id;

    $status = Friend::where([["user_id", '=', $me], ["friend_id",'=', $friend]])
             ->orWhere([["user_id", '=', $friend], ["friend_id", '=', $me]])->count();

        if ($status < 1){
            $data = [
                'user_id' => $me,
                'friend_id' => $friend,
                'accepted' => 1
            ];
            Friend::create($data);
        }
    $room = Room::where("users", $me.":".$friend)
                        ->orWhere("users", $friend.":".$me)->first();
    if ($room){
        $dataRoom = $room;
    }
    else{
        $dataRoom = Room::create([
            "users" => $me.":".$friend
        ]);
    }
    return response()->json([
        "success" => true,
        "data" => $dataRoom,
    ]);
   }
}
