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
                      <th>ID</th>
                      <th>{{ __('User Name') }}</th>
                      <th>{{ __('School') }}</th>
                      <th>{{ __('Student Number') }}</th>
                      {{-- <th>{{ __('E-Mail') }}</th> --}}
                      <th>{{ __('Admin') }}</th>
                      <th>{{ __('Create Time') }}</th>
                      <th>{{ __('Action') }}</th>
                    </thead>

                    <tbody>
                      @foreach($users as $i => $user)
                        <tr>
                          <td>{{$user->id}}</td>
                          <td>{{$user->name}}</td>
                          <td>{{$user->school}}</td>
                          <td>{{$user->student_id}}</td>
                          {{-- <td>{{$user->email}}</td> --}}

                          {{-- 如果為管理員則將內容至換掉 --}}
                          @if (!!$user->is_admin)
                            <td style="text-align:center; color:green;"><span style="display:none">1</span><i class="fas fa-check"></i></td>
                          @else
                            <td style="text-align:center; color:red;"><span style="display:none">0</span><i class="fas fa-times"></i></td>
                          @endif

                          <td>{{$user->created_at}}</td>

                          <td>
                            <a class="btn btn-samll btn-danger"  onclick="delete_btn_click(event)" user_id="{{ $user->id }}" data-toggle="modal" data-target="#deltedia"><i user_id="{{ $user->id }}" style="color:white" class="fas fa-user-times"></i></a>
                            <a class="btn btn-samll btn-success" href="{{ Route('users.show', $user->id) }}"><i class="fas fa-user"></i></a>
                            <a class="btn btn-small btn-info" href="{{ Route('users.edit', $user->id) }}"><i style="color:white" class="fas fa-user-edit"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                  <div class="modal fade align-middle" id="deltedia" tabindex="-1" role="dialog" aria-labelledby="modal_label" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="modal_label">{{ __('Confirm') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div id="del_dia_body" class="modal-body">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                            <form id="delete_form" action="" method="POST">
                              @csrf
                              @method('DELETE')
                              <button onclick="confirm_delete_btn_click()" data-dismiss="modal" class="btn btn-danger">確認</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
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
  function confirm_delete_btn_click() {
    document.getElementById("delete_form").submit();
  }
  function delete_btn_click(event) {
    var userId = event.target.getAttribute('user_id');
    var deleteURL = '{{ route('users.destroy', '@id') }}';

    console.log(event.target.getAttribute('user_id'));
    document.getElementById('delete_form').setAttribute('action', deleteURL.replace('@id', userId));
    {{-- document.getElementById('confirm_del_btn').setAttribute('href', '/users/' + userId) --}}
    document.getElementById('del_dia_body').innerText = '確認是否刪除id為 ' + userId + ' 之用戶？';
  }
  var dt = null;
    function rowDelete(e) {
      {{-- var dt = $('#usertable').DataTable(); --}}
      var rowDom = e.target.parentElement.parentElement;
      var row = rowDom.getElementsByTagName('td');
      var col = row[2];
      console.log(col.innerText);

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
<style>
    .modal {
      text-align: center;
    }

    @media screen and (min-width: 768px) {
      .modal:before {
        display: inline-block;
        vertical-align: middle;
        content: " ";
        height: 100%;
      }
    }

    .modal-dialog {
      display: inline-block;
      text-align: left;
      vertical-align: middle;
    }
</style>
@endsection
