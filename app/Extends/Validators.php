<?php

// 驗證輸入密碼與目前使用者密碼是否相同
\Validator::extendImplicit('current_password', function($attribute, $value, $parameters, $validator) {
        error_log('password:'.$value);
        $state = \Hash::check($value, auth()->user()->password);
        error_log('check:'.$state);
        return \Hash::check($value, auth()->user()->password);
    },
    '輸入密碼與當前密碼不符!'
);
