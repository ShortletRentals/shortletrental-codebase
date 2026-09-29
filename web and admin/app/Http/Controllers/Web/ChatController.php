<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as Controller;

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
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    protected  $page = 'chat';
    protected  $lang = 'Chat';
    protected  $table;

    public function __construct() {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $user_type = '';

        if (auth()->user()) {
            $user_type = auth()->user()->user_type;
        }

        if ($user_type == 1) {
            $chatObj = Chat::with('sender_detail', 'receiver_detail')->with(['last_message' => function ($query) {
                        $query->latest();
                    }])->with('last_message.sent_by_detail')->orderBy('updated_at', 'DESC')->get();

        } else {
            $chatObj = Chat::where('sender_id', auth()->id())
                ->orWhere('receiver_id', auth()->id())->with('sender_detail', 'receiver_detail')->with(['last_message' => function ($query) {
                        $query->latest();
                    }])->with('last_message.sent_by_detail')->orderBy('updated_at', 'DESC')->get();
        }

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
        $parms = array();
        $result = ApiCurlMethod('chat-list', $parms, 'Bearer', 'GET');
        $data = ['title' => __('backend.ChatList'), 'data' => $result];
        return view('web.chat', $data);
    }

    public function guestChat(request $request)
    {
        $auth_user = Session::get('AuthUserData');

        if (!$auth_user) {
            //Guest User
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            //check remote address exist

            if ($ipaddress) {
                $data = json_decode(checkGuestLogin($ipaddress));

                if ($data->status == true) {
                    Session::put('is_guest', '1');
                    Session::put('AuthUserData', $data);
                }
            }
            //End
        } else {
            $parms = array();
            $result = ApiAuthValid('user-profile', $parms, 'Bearer', 'GET');
            // dd($result);

            if ($result['httpcode'] == 401) {
                $ipaddress = $_SERVER['REMOTE_ADDR'];
                //check remote address exist

                if ($ipaddress) {
                    $data = json_decode(checkGuestLogin($ipaddress));

                    if ($data->status == true) {
                        Session::put('is_guest', '1');
                        Session::put('AuthUserData', $data);
                    }
                }
            }
        }
        $auth_user = Session::get('AuthUserData');

        $user_type = '';

        if ($auth_user) {
            $user_type = $auth_user->data->user_type;
        }

        if ($auth_user) {
            $chatObj = Chat::where('is_guest_chat', 1)->where(function ($query) use($auth_user) {
                        $query->where('sender_id', 1)->where('receiver_id', $auth_user->data->id);
                    })->with('sender_detail', 'receiver_detail')->with(['last_message' => function ($query) {
                        $query->latest();
                    }])->with('last_message.sent_by_detail')->orderBy('updated_at', 'DESC')->get();

            if (!count($chatObj)) {
                $chatData = new Chat;
                $chatData->sender_id = 1;
                $chatData->receiver_id = $auth_user->data->id;
                $chatData->is_guest_chat = 1;
                $chatData->save();

                $chatObj = Chat::where('is_guest_chat', 1)->where(function ($query) use($auth_user) {
                        $query->where('sender_id', 1)->where('receiver_id', $auth_user->data->id);
                    })->with('sender_detail', 'receiver_detail')->with(['last_message' => function ($query) {
                        $query->latest();
                    }])->with('last_message.sent_by_detail')->orderBy('updated_at', 'DESC')->get();
            }

        } else {
            $chatObj = array();
        }

        $chatObj->append('unseen_message_count');

        $newChatObj = [];
        $result = (object)[];

        if (count($chatObj) > 0) {
            $result->data = json_decode($chatObj);
            $result->success = TRUE;
            $result->message = 'Fetched chat list successfully';
        } else {
            $result->data = $chatObj;
            $result->success = FALSE;
            $result->message = 'Chat not found';
        }
        // $parms = array();
        // $result = ApiCurlMethod('chat-list', $parms, 'Bearer', 'GET');
        // dd($result);
        $data = ['title' => __('backend.ChatList'), 'data' => $result];
        return view('web.guest_chat', $data);
    }

    public function chatDetail(request $request, $chatId)
    {
        // if ($chatId) {
        //     $messages = Message::where('chat_id', $chatId)->with('sent_by_detail')->get();
        //     // dd($messages->toArray());
        //     $data['records'] = $messages;
        //     $data['chatId'] = $chatId;

        // } else {
        //     $data['records'] = array();
        //     $data['chatId'] = '';
        // }
        // $data['chatId'] = $chatId;
        $login_user_id = '';

        $userData =  Session::get('AuthUserData') ?? null;

        if ($userData) {
            $login_user_id = $userData->data->id;
        }

        $parms = array();
        $method = 'chat-detail/'.$chatId;
        $result = ApiCurlMethod($method, $parms, 'Bearer', 'GET');
        $data['login_user_id'] = $login_user_id;
        $data['data'] = $result;
        return view('web.show_msgs',$data);
    }
}
