<?php

namespace App\Http\Controllers;

use App\Models\Communicationmessage;
use App\Models\User;
use Illuminate\Http\Request;
use Pusher\Pusher;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //$users = User::where('id', '!=', auth()->user()->id)->get();

        /**
         * @todo review this ...
         */
        // count how many message are unread from the selected user
        $users = \DB::select("select users.id, users.name, users.image, users.email, count(is_viewed) as unread
        from users LEFT  JOIN  communicationmessages ON users.id = communicationmessages.sender and is_viewed = 0 and communicationmessages.receiver = " . \Auth::id() . "
        where users.id != " . \Auth::id() . "
        group by users.id, users.name, users.image, users.email");

        return view('home', ['users' => $users]);
    }

    public function receivemessage($user_id)
    {
        $auth_user_id = auth()->user()->id;

        Communicationmessage::where(['sender'=>$user_id, 'receiver'=>$auth_user_id])->update(['is_viewed'=>1]);

        $communicationmessages = Communicationmessage::where(function ($query) use ($user_id, $auth_user_id) {

            $query->where('sender', $user_id);
            $query->where('receiver', $auth_user_id);

        })->orWhere(function ($query) use ($user_id, $auth_user_id) {

            $query->where('sender', $auth_user_id);
            $query->where('receiver', $user_id);

        })->get();

        return view('communicationmessages.index', ['communicationmessages'=>$communicationmessages]);
    }

    public function sendmessage(Request $request)
    {
        $data = [
            'sender' => auth()->user()->id,
            'receiver' => $request->rec_id,
            'communicationmessage' => $request->communicationmessage,
            'is_viewed' => 0
        ];

        Communicationmessage::create($data);

        $options = [
            'cluster' => 'eu',
            'useTLS' => true
        ];

        /**
         * @todo debug this, no messages appear in Pusher ...
         */
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), $options);

        $pusher->trigger('my-channel', 'my-event', $data);
    }
}
