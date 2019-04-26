<?php

namespace App\Http\Controllers\Response;

use Illuminate\Http\Response;

/*  處理錯誤回應
 *  以Json格式作為回傳訊息，將錯誤資訊放到message內，以及狀態碼放到status內。
*/
class JsonMsgResponse
{
     public static function response($message, $status) {
        return response()->json(['message' => $message, 'status' => $status], $status);
    }
}
