<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Debugbar;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'student_id' => 'required|string|max:255|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|max:255',
        ]);
        // process the login
        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        else{
            User::create([
                'name' => $data['name'],
                'school' => $data['school'],
                'email' => $data['email'],
                'student_id' => $data['student_id'],
                'password' => \Hash::make($data['password']),
                'is_admin' => array_key_exists('is_admin', $data),
            ]);

            \Session::flash('message', '使用者創建成功!');
            return redirect('user');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 取得使用者資料
        $user = User::find($id);

        // 顯示使用者資料頁面
        return view('users.show')
                ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 取得使用者資料
        $user = User::find($id);

        // 顯示使用者編輯頁面
        return view('users.edit')
                ->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        // 將所有輸入為null值得key清除
        foreach($data as $key => $value)
            if(is_null($value))
                unset($data[$key]);

        // 由於在User的Model內password預設為隱藏，但因使用者可能需要修改密碼，故將此值取出使用makeVisible
        $user = User::find($id)->makeVisible('password');

        // 驗證表單
        $validator = \Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'school' => 'sometimes|string|max:255',
            // 忽略當前列的使用者，但檢查其他欄位的唯一性。
            'student_id' => 'sometimes|string|max:255|unique:users,student_id,'.$user->id,
            // 忽略當前列的使用者，但檢查其他欄位的唯一性。
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|check_password:'.$user->id,
            'new_password' => 'sometimes|string|min:4|max:255|confirmed',
        ]);

        // 驗證失敗返回當前頁面
        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }
        else{
            // is_admin 欄位暫不處理

            // 如果有輸入"密碼"則清除，因為未加密的密碼會覆蓋掉原本的加密的密碼
            if(array_key_exists('password',$data))
                unset($data['password']);

            // 將輸入資料填充該使用者資料
            $user->fill($data);

            // 如果使用者有輸入新密碼，則將舊密碼改為新密碼
            if(array_key_exists('new_password', $data) && !is_null($data['new_password'])) {
                $user['password'] = \Hash::make(request()->input('new_password'));
            }

            // 使用ORM進行修改資料
            $user->save();


            \Session::flash('message', '使用者創建成功!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
