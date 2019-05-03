<?php

// 驗證輸入密碼與目前使用者密碼是否相同
\Validator::extendImplicit('current_password', function($attribute, $value, $parameters, $validator) {
        return \Hash::check($value, auth()->user()->password);
    },
    '輸入密碼與當前密碼不符!'
);

\Validator::extendImplicit('check_password', function($attribute, $value, $parameters, $validator) {
    try {
        return \Hash::check($value, \App\User::find($parameters[0])->makeVisible('password')->password);
    }
    catch(\Exception $e) {
        error_log($e);
        return false;
    }
},
'輸入密碼與當前密碼不符!'
);
