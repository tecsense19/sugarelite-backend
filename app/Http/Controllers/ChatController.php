<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatMessageSent;
use App\Models\Messages;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $user_id = $request->input('message_from');
        $sender_id = $request->input('message_to');
        $message = $request->input('message');
        $currentTimeMillis = round(microtime(true) * 1000);

        $stringArr = [];
        $stringArr['user_id'] = $user_id;
        $stringArr['sender_id'] = $sender_id;
        $stringArr['message_from'] = $user_id;
        $stringArr['message_to'] = $sender_id;
        $stringArr['text'] = $message;
        $stringArr['milisecondtime'] = $currentTimeMillis;

        // $data = [
        //     'user_id' => $user_id,
        //     'sender_id' => $sender_id,
        //     'message_from' => $user_id,
        //     'message_to' => $sender_id,
        //     'message' => $message,
        //     'milisecondtime' => $currentTimeMillis,
        // ];
        
        // $endpoint = env('APP_URL').'/chat/webhook/message?' . http_build_query($data);
        
        // $ch = curl_init($endpoint);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPGET, true);
        
        // $response = curl_exec($ch);
        
        // if ($response === false) {
        //     echo 'Error: ' . curl_error($ch);
        // }
        
        // curl_close($ch);
        Messages::create($stringArr);
        return response()->json(['status' => 'true' , 'message_from' => $user_id, 'message_to' => $sender_id, 'message' => $message , 'milisecondtime' => $currentTimeMillis]);
    }

    public function messageList(Request $request)
    {
        $messageList = Messages::get();
        return response()->json(['status' => 'true', 'data' => $messageList]);
    }
  

    public function webhook(Request $request)
    {
        $user_id = $request->query('user_id');
        $sender_id = $request->query('sender_id');
        $message = $request->query('message');
        $currentTimeMillis = $request->query('milisecondtime');
    
        // Your further processing logic here
    
        $dataString = "User ID: $user_id, Sender ID: $sender_id, Message: $message , time: $currentTimeMillis\n";

         // Save data to the first URL
        // $url1 = 'http://example.com/endpoint1';
        // $this->sendDataToUrl($url1, $dataString);

        // // Save data to the second URL
        // $url2 = 'http://example.com/endpoint2';
        // $this->sendDataToUrl($url2, $dataString);
    
        file_put_contents('webhook_data.txt', $dataString, FILE_APPEND);
    
        return response()->json(['status' => true, 'user_id' => $user_id, 'sender_id' => $sender_id, 'message' => $message , 'milisecondtime' => $currentTimeMillis]);
    }

    private function sendDataToUrl($url, $dataString)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['data' => $dataString]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            // Handle error
            echo 'Error: ' . curl_error($ch);
        } else {
            // Handle response if needed
            echo 'Response: ' . $response;
        }

        curl_close($ch);
    }
}
