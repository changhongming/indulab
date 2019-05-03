@extends('layouts.layout')

@section('content')

<div class="container introduce-bg2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">{{ __('User List') }}</div>
                <div class="card-body">
                  @if (Session::has('message'))
                  <div class="alert alert-success alert-dismissible fade show">
                    {{Session::get('message')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  @endif
                  <table id="usertable" class="table table-hover table-striped table-stacked-md">

                    <thead>
                      <th>id</th>
                      <th>使用者名稱</th>
                      <th>學校</th>
                      <th>學號</th>
                      <th>信箱</th>
                      <th>管理員</th>
                      <th>創建時間</th>
                      <th>註銷</th>
                    </thead>

                    <tbody>
                      @foreach($users as $i => $user)
                        <tr>
                          <td>{{$user->id}}</td>
                          <td>{{$user->name}}</td>
                          <td>{{$user->school}}</td>
                          <td>{{$user->student_id}}</td>
                          <td>{{$user->email}}</td>

                          {{-- 如果為管理員則將內容至換掉 --}}
                          @if (!!$user->is_admin)
                            <td style="text-align:center; color:green;"><span style="display:none">1</span><i class="fas fa-check"></i></td>
                          @else
                            <td style="text-align:center; color:red;"><span style="display:none">0</span><i class="fas fa-times"></i></td>
                          @endif

                          <td>{{$user->created_at}}</td>

                          <td><a class="btn btn-danger" href="{{ URL::to('users/' . $user->id) }}">刪除</a></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet"/> --}}
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
  var dt = null;
    function rowDelete(e) {
      {{-- var dt = $('#usertable').DataTable(); --}}
      var rowDom = e.target.parentElement.parentElement;
      var row = rowDom.getElementsByTagName('td');
      var col = row[2];
      console.log(col.innerText);

      {{-- $.ajax({

      }) --}}
      dt.row(rowDom)
      .remove()
      .draw();
    }
    $(document).ready(function() {

      dt = $('#usertable').DataTable();

      {{-- // 修改一頁幾個的顯示 --}}
      var onepage = document.querySelector('#usertable_length>label');
      onepage.replaceChild( document.createTextNode('一頁'), onepage.childNodes[0]);
      onepage.replaceChild( document.createTextNode('個'), onepage.childNodes[2]);

      {{-- // 左下角顯示總筆數(當前因有其他事件會更動到此數值，暫不修改為中文)
      document.getElementById('usertable_info').innerHTML = `總共有 ${document.querySelector('#usertable>tbody').childElementCount} 個使用者`; --}}

      {{-- // 修改搜尋框 --}}
      var serach = document.querySelector('#usertable_filter>label');
      serach.replaceChild(document.createTextNode('搜尋'), serach.childNodes[0]);

      {{-- // 換頁顯示(當前因有其他事件會更動到此數值，暫不修改為中文)
      document.querySelector('#usertable_previous>a').innerHTML = '前一頁';
      document.querySelector('#usertable_next>a').innerHTML = '下一頁'; --}}

    });

</script>
@endsection
