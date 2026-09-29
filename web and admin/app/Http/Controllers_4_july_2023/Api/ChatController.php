<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\DeleteChat;
use App\Library\PushNotification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;
use Redis;
use DB;
use Exception;
use Illuminate\Support\Facades\Config;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['sendMessage', 'saveMessage', 'seenMessage', 'seenMessage']]);
    }

    public function sendMessage(Request $request)
    {
        // $redis = Redis::connection();

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $data = ['message' => $request->get('message'), 'user' => $request->get('user')];
        $redis->publish('message', json_encode($data));
        return response()->json([]);
    }

    public function chatList(Request $request)
    {
        $response = [];
        $response['success'] = FALSE;

        try {
            // $userId = $request->user()->id;
            $userData = auth()->user();
            $userId = $userData->id;

            $chatObj = Chat::select('chats.*', 'bookings.booking_id as bookID')->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId)
                ->with('sender_detail', 'receiver_detail')
                ->with(['last_message' => function ($query) {
                    $query->latest();
                }])
                ->with('last_message.sent_by_detail')
                ->leftjoin('bookings', 'bookings.id', '=', 'chats.booking_id')
                ->orderBy('updated_at', 'DESC')
                ->get();

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
                // dd($newChatObj);
                // $response['data'] = $newChatObj->append('unseen_message_count');

                if (count($chatObj) > 0) {
                    $response['data'] = $chatObj;
                    $response['success'] = TRUE;
                    $response['message'] = 'Fetched chat list successfully';

                } else {
                    $response['data'] = $chatObj;
                    $response['success'] = FALSE;
                    $response['message'] = 'Data not found';
                }

            } else {
                $response['data'] = $chatObj;
                $response['success'] = FALSE;
                $response['message'] = 'Data not found';
            }

            $response['status'] = 200;
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage() . ' Line No ' . $e->getLine() . ' in File' . $e->getFile()
            ];
            Log::error($e->getTraceAsString());
            $response['status'] = 500;
        }
        return $response;
    }

    public function chatDetail(Request $request, $chatId='')
    {
        $response = [];
        $response['success'] = FALSE;

        try {
            $userData = auth()->user();
            $userId = $userData->id;
            $requestData = $request->all();

            if (!empty($chatId)) {
                $response['data'] = Message::where('chat_id', $chatId)->with('sent_by_detail')->get();
                $response['message'] = 'Chat detail fetched successfully';
                $response['success'] = true;

            } else {
                $response['data'] = array();
                $response['message'] = 'No msg available';
                $response['success'] = false;
            }
            $response['status'] = 200;

        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage() . ' Line No ' . $e->getLine() . ' in File' . $e->getFile()
            ];
            Log::error($e->getTraceAsString());
            $response['status'] = 500;
        }
        return $response;
    }

    public function chatDelete(Request $request)
    {
        $response = [];
        $response['success'] = FALSE;

        try {
            $requestData = $request->all();

            $rules = [
                // 'user_id' => 'required',
                // 'gift_id' => 'required',
            ];

            if (isset($requestData['type']) && $requestData['type'] == "text") {
                $rules['message'] = "";
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errorResponse = validation_error_response($validator->errors()->toArray());
                return $errorResponse;
            }

            $loggedInUserId = $request->user()->id;
            $userId = $requestData['user_id'] ?? '';

            if (isset($requestData['chat_id']) && !empty($requestData['chat_id'])) {
                $msgdata = Message::where('chat_id', $requestData['chat_id'])->orderBy('id', 'desc')->first();

            } else {
                $chatdata = Chat::where(['sender_id' => $loggedInUserId, 'receiver_id' => $userId])->first();

                if (!$chatdata) {
                    $chatdata = Chat::where(['sender_id' => $userId, 'receiver_id' => $loggedInUserId])->first();
                }

                if ($chatdata) {
                    $msgdata = Message::where('chat_id', $chatdata->id)->orderBy('id', 'desc')->first();
                } else {
                    $msgdata = '';
                }
            }

            if (!$msgdata) {
                $response['logged_in_user_id'] = $request->user()->id;
                $response['request_data'] = $request->all();
                $response['message'] = 'Invalid request';
                return $response;

            } else {
                $deleteMsgObj = new DeleteChat;
                $deleteMsgObj->user_id = $loggedInUserId;
                $deleteMsgObj->message_id = $msgdata->id;
                $deleteMsgObj->chat_id = $msgdata->chat_id;
                $deleteMsgObj->delete_type = 'Chat';

                if ($deleteMsgObj->save()) {
                    $response['message'] = 'Chat deleted successfully';
                    $response['success'] = TRUE;
                    $response['status'] = 200;

                } else {
                    $response['message'] = 'Somthing went wrong.';
                    $response['success'] = FALSE;
                    $response['status'] = 200;
                }
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage() . ' Line No ' . $e->getLine() . ' in File' . $e->getFile()
            ];
            Log::error($e->getTraceAsString());
            $response['status'] = 500;
        }
        return $response;
    }

    public function saveMessage(Request $request)
    {
      
        $response = [];
        $response['success'] = FALSE;

        DB::beginTransaction();
        try {
            $requestData = $request->all();

            $rules = [
                'sender_id' => 'required',
                'receiver_id' => 'required',
                // 'gift_id' => 'required',
                'type' => 'required',
            ];

            if (isset($requestData['type']) && $requestData['type'] == "text") {
                $rules['message'] = "";
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errorResponse = validation_error_response($validator->errors()->toArray());
                return $errorResponse;
            }
            //Check chat exist or not

            if (isset($requestData['chatId'])) {
                $chatObj = Chat::where(['id' => $requestData['chatId']])->first();

            } else {
                $chatObj = Chat::where([
                    'sender_id' => $requestData['sender_id'],
                    'receiver_id' => $requestData['receiver_id']
                ])
                    ->orWhere([
                        'sender_id' => $requestData['receiver_id'],
                        'receiver_id' => $requestData['sender_id']
                    ])->first();

                if ($chatObj = Chat::where(['sender_id' => $requestData['sender_id'], 'receiver_id' => $requestData['receiver_id']])->first()) {

                } elseif ($chatObj = Chat::where(['sender_id' => $requestData['receiver_id'], 'receiver_id' => $requestData['sender_id']])->first()) {

                } else {
                    $chatObj = new Chat;
                    $chatObj->sender_id = $requestData['sender_id'];
                    $chatObj->receiver_id = $requestData['receiver_id'];
                    // $chatObj->gift_id = $requestData['gift_id'];
                }
                // $chatObj->gift_id = $requestData['gift_id'] ?? NULL;
                $chatObj->save();
            }

            $chatObj->updated_at = date('Y-m-d H:i:s');
            $chatObj->save();

            $messageObj = new Message;
            $messageObj->chat_id = $chatObj->id;
            $messageObj->sent_by = $requestData['sender_id'];
            $messageObj->message = $requestData['message'] ?? "";
            $messageObj->type = $requestData['type'] ?? 'text';

        
            if (isset($requestData['type']) && $requestData['type'] == "file") {

                if($request->hasFile('file')) 
                {
                    $file = $request->file('file');
                    $uploadedImages = uploadImage($file, public_path() . '/uploads/images/');

                    if ( $uploadedImages['file_type'] == "video") {
                        $videoUrl = public_path() . '/uploads/images/' . $uploadedImages['file_name'];
                        $storageUrl = THUMBNAIL_UPLOAD_PATH;
                        $thumbnailName = date('d_m_y_H_i_s') . rand(100, 999) . ".jpg";
                        try {
                            VideoThumbnail::createThumbnail(
                                $videoUrl,
                                $storageUrl,
                                $thumbnailName,
                                2,
                                800,
                                500
                            );
                            $uploadedImages['thumbnail'] = $thumbnailName;
                        } catch (Exception $e) {
                            $response['message'] = $e->getMessage() . ' Line No ' . $e->getLine() . ' in File' . $e->getFile();
                            Log::error($e->getTraceAsString());
                        }
                    }
                    $messageObj->file_type = $uploadedImages['file_type'];
                    $messageObj->file_extension = $uploadedImages['file_extension'] ?? "";
                    $messageObj->file = $uploadedImages['file_name'] ?? "";

                } else {
                    $fileInfo = chat_base64_to_image($requestData['file']);
                    $messageObj->file_type = 'image';
                    $messageObj->file_extension = $fileInfo['file_type'] ?? "";
                    $messageObj->file = $fileInfo['file_name'] ?? "";
                }
            }

            if ($messageObj->save()) {
                
            }
            DB::commit();
            $likedata = User::Find($requestData['sender_id']);
            $data = User::Find($requestData['receiver_id']);

            $notificationData = new Notification;
            $notificationData->user_type = 1;
            $notificationData->notification_type = 1;
            if($likedata->is_guest==1)
            {
                $notificationData->notification_for = 'Guest-Chat';
            
            }
            else
            {
                $notificationData->notification_for = 'Chat';
            
            }
            $notificationData->title = $likedata->name . ' guest sent a message';
            $notificationData->message =  $requestData['message'] ?? "";
            $notificationData->user_id = $data->id;
            $notificationData->save();
          //  send_notification(1, 1, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );


            
            /*if (isset($requestData['type']) && $requestData['type'] == "file") {

                if ($data && $data->notification_allow != 0) {
                    $notificationData = [
                        'title' => $likedata->first_name . '  ' . 'sent a message',
                        'message' => 'You received a image.',
                        'device_token' => $data->device_token,
                        'Info' => json_decode($chatObj, true),
                        'badge' => Notification::where(['user_id' => $requestData['receiver_id'], 'is_read' => 0])->count(),
                         'send_by' => $requestData['sender_id'],
                         'type' => 10,
                     ];
                     PushNotification::send($notificationData);
                }
            }else{

                if ($data && $data->notification_allow != 0) {
                    $notificationData = [
                        'title' => $likedata->first_name . '  ' . 'sent a message',
                        'message' => $requestData['message'],
                        'device_token' => $data->device_token,
                        'Info' => json_decode($chatObj, true),
                        'badge' => Notification::where(['user_id' => $requestData['receiver_id'], 'is_read' => 0])->count(),
                        'send_by' => $requestData['sender_id'],
                        'type' => 10,
                    ];
                    PushNotification::send($notificationData);
                }
            }*/
            $response['message'] = 'Message sent successfully';

            $chatId = $chatObj->id;

            $chatData = Chat::where('id', $chatId)->with(['last_message' => function ($query) {
                $query->latest()->first();
            }])->with('last_message.sent_by_detail')->first();

            if ($chatData) {
                $chatData->append('unseen_message_count');
            }

            //$chatData = $messageObj;

            $response['data'] = $chatData;
            $response['success'] = TRUE;
            $response['status'] = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response['message'] = $e->getMessage() . ' Line No ' . $e->getLine() . ' in File' . $e->getFile();
            Log::error($e->getTraceAsString());
            $response['status'] = FALSE;
        }
        return $response;
    }

    public function seenMessage(Request $request)
    {
        $response = [];
        $response['success'] = FALSE;

        try {
            $requestData = $request->all();

            $messageId = $requestData['message_id'] ?? 0;

            Message::where('id', $messageId)->update([
                'is_read' => '1'
            ]);

            $response['message'] = "Message seen successfully";
            $response['success'] = TRUE;
            $response['status'] = 200;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage() . ' Line No ' . $e->getLine() . ' in File' . $e->getFile();
            Log::error($e->getTraceAsString());
            $response['status'] = FALSE;
        }
        return $response;
    }
}
