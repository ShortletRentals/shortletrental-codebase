<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Country;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class ChatController extends Controller
{
    protected  $page = 'chat';
    protected  $lang = 'Chat';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        
        Gate::authorize('Chat-section');
        $user_type = '';

        if (auth()->user()) {
            $user_type = auth()->user()->user_type;
        }

        $chatObj = Chat::select('chats.*', 'bookings.booking_id as bookID')
            ->where('is_guest_chat', 0)
            ->with('sender_detail', 'receiver_detail')
            ->with(['last_message' => function ($query) {
                        $query->latest();
                }])->with('last_message.sent_by_detail')
                ->leftjoin('bookings', 'bookings.id', '=', 'chats.booking_id')
                ->orderBy('updated_at', 'DESC')
                ->get();

                /*
        if ($user_type == 1) {
            

        } else {
            $chatObj = Chat::select('chats.*', 'bookings.booking_id as bookID')
            ->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->with('sender_detail', 'receiver_detail')
            ->with(['last_message' => function ($query) {
                        $query->latest();
            }])
            ->with('last_message.sent_by_detail')
            ->leftjoin('bookings', 'bookings.id', '=', 'chats.booking_id')
            ->orderBy('updated_at', 'DESC')
            ->get();
        }*/

        // dd($chatObj->toArray());

        $chatObj->append('unseen_message_count');

        $newChatObj = [];

        if (count($chatObj) > 0) {

            /*foreach ($chatObj as $key => $value) {
                $deleteChatData = DeleteChat::where(['user_id' => $userId, 'chat_id' => $value->id])->orderBy('id', 'desc')->first();

                if ($deleteChatData) {

                    if ($value->last_message && $value->last_message->id && $value->last_message->id == $deleteChatData->message_id) {

                    } else {
                        $newChatObj[] = $value;
                    }

                } else {
                    $newChatObj[] = $value;
                }
            }*/

            /*if (count($chatObj) > 0) {
                $response['data'] = $chatObj;
                $response['success'] = TRUE;
                $response['message'] = 'Fetched chat list successfully';
                dd($response);

            } else {
                $response['data'] = $chatObj;
                $response['success'] = FALSE;
                $response['message'] = 'Data not found';
            }*/

        } else {
            /*$response['data'] = $chatObj;
            $response['success'] = FALSE;
            $response['message'] = 'Data not found';*/
        }
        $data= ['title'=>$this->lang,'page'=>$this->page, 'data'=>$chatObj];
        return view('admin.'.$this->page.'.listing',$data);
    }

    public function chatList(request $request)
    {
        
        Gate::authorize('Chat-section');
        $user_type = '';

        if (auth()->user()) {
            $user_type = auth()->user()->user_type;
        }

        $chatObj = Chat::select('chats.*', 'bookings.booking_id as bookID')
            ->where('is_guest_chat', 0)
            ->with('sender_detail', 'receiver_detail')
            ->with(['last_message' => function ($query) {
                        $query->latest();
                }])->with('last_message.sent_by_detail')
                ->leftjoin('bookings', 'bookings.id', '=', 'chats.booking_id')
                ->orderBy('updated_at', 'DESC')
                ->get();



        $chatObj->append('unseen_message_count');

        $newChatObj = [];

        $data= ['title'=>$this->lang,'page'=>$this->page, 'data'=>$chatObj];
        return view('admin.'.$this->page.'.chat_listing',$data);
    }

    

    public function guest_chat(request $request)
    {
        Gate::authorize('Chat-section');
        $user_type = '';

        if (auth()->user()) {
            $user_type = auth()->user()->user_type;
        }

        if ($user_type == 1 || $user_type == 2) {
            $chatObj = Chat::where('is_guest_chat', 1)->with('sender_detail', 'receiver_detail')->with(['last_message' => function ($query) {
                        $query->latest();
                    }])->with('last_message.sent_by_detail')->orderBy('updated_at', 'DESC')->get();

        } else {
            $chatObj = array();
        }

        // dd($chatObj->toArray());

        $chatObj->append('unseen_message_count');

        $newChatObj = [];

        if (count($chatObj) > 0) {

        } else {
            
        }
        $data= ['title'=>$this->lang,'page'=>$this->page, 'data'=>$chatObj];
        return view('admin.'.$this->page.'.guest_chat',$data);
    }


    public function guestList(request $request)
    {
        Gate::authorize('Chat-section');
        $user_type = '';

        if (auth()->user()) {
            $user_type = auth()->user()->user_type;
        }

        if ($user_type == 1 || $user_type == 2) {
            $chatObj = Chat::where('is_guest_chat', 1)->with('sender_detail', 'receiver_detail')->with(['last_message' => function ($query) {
                        $query->latest();
                    }])->with('last_message.sent_by_detail')->orderBy('updated_at', 'DESC')->get();

        } else {
            $chatObj = array();
        }

        // dd($chatObj->toArray());

        $chatObj->append('unseen_message_count');

        $newChatObj = [];

        if (count($chatObj) > 0) {

        } else {
            
        }
        $data= ['title'=>$this->lang,'page'=>$this->page, 'data'=>$chatObj];
        return view('admin.'.$this->page.'.chat_listing',$data);
    }
    public function chatDetail(request $request, $chatId)
    {
        if ($chatId) {
            $messages = Message::where('chat_id', $chatId)->with('sent_by_detail')->get();
            // dd($messages->toArray());
            $data['records'] = $messages;
            $data['chatId'] = $chatId;

        } else {
            $data['records'] = array();
            $data['chatId'] = '';
        }
        $data['chatId'] = $chatId;
        return view('admin.'.$this->page.'.show_msgs',$data);
    }

    public function show_notification(Request $request)
    {
        return view('admin.show_notification');
    }

    public function show_notification_count(Request $request)
    {
        return view('admin.show_notification_count');
    }
}
