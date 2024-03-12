<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatMessageSent;
use App\Models\Messages;

class ChatController extends Controller
{
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
