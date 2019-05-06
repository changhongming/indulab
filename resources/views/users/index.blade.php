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
                  <div id="no-more-tables">
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
                          <td data-title="ID">{{$user->id}}</td>
                          <td data-title="{{ __('User Name') }}">{{$user->name}}</td>
                          <td data-title="{{ __('School') }}">{{$user->school}}</td>
                          <td data-title="{{ __('Student Number') }}">{{$user->student_id}}</td>
                          {{-- <td>{{$user->email}}</td> --}}

                          {{-- 如果為管理員則將內容至換掉 --}}
                          @if (!!$user->is_admin)
                            <td data-title="{{ __('Admin') }}"><span style="display:none">1</span><i style="color:green;" class="fas fa-check"></i></td>
                          @else
                            <td data-title="{{ __('Admin') }}"><span style="display:none">0</span><i style="color:red;" class="fas fa-times"></i></td>
                          @endif

                          <td data-title="{{ __('Create Time') }}">{{ $user->created_at }}</td>

                          <td data-title="{{ __('Action') }}">
                            <div class="row">

                            <div class="col-4 col-md-12 col-lg-4">
                              <a class="btn btn-samll btn-danger"  onclick="delete_btn_click(event)" user_id="{{ $user->id }}" data-toggle="modal" data-target="#deltedia"><i user_id="{{ $user->id }}" style="color:white" class="fas fa-user-times"></i></a>
                            </div>
                            <div class="col-4 col-md-12 col-lg-4">
                              <a class="btn btn-samll btn-success" href="{{ Route('users.show', $user->id) }}"><i class="fas fa-user"></i></a>
                            </div>
                            <div class="col-4 col-md-12 col-lg-4">
                              <a class="btn btn-small btn-info" href="{{ Route('users.edit', $user->id) }}"><i style="color:white" class="fas fa-user-edit"></i></a>
                            </div>
                          </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

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

    document.getElementById('del_dia_body').innerText = '確認是否刪除id為 ' + userId + ' 之用戶？';
  }
  var dt = null;

  $(document).ready(function() {

    dt = $('#usertable').DataTable({
      "language": {
        "lengthMenu" : "一頁 _MENU_ 個",
        "zeroRecords" : "沒有搜尋到任何資料",
        "info" : "顯示 _PAGE_ / _PAGES_",
        "infoEmpty" : "沒有任何資料",
        "infoFiltered" : "(過濾 _MAX_ 筆資料)",
        "search" : "搜尋:",
        "paginate": {
          "next" : ">",
          "previous" : "<"
      },
    },
    "stateSave": true,
    });
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

    {{-- 如果畫面尺寸再800以下，套用區塊內的CSS --}}
    @media only screen and (max-width: 800px) {

    {{-- 將原本的資料表使用換行表示 --}}
    #no-more-tables table,
    #no-more-tables thead,
    #no-more-tables tbody,
    #no-more-tables th,
    #no-more-tables td,
    #no-more-tables tr {
      display: block;
    }

    {{-- 將原本的資料表標題列隱藏 --}}
    #no-more-tables thead tr {
      position: absolute;
      top: -9999px;
      left: -9999px;
    }

    {{-- 每列元素加入框線 --}}
    #no-more-tables tr { border: 1px solid #ccc; }

    {{-- 每行元素設定 --}}
    #no-more-tables td {
      border: none;
      border-bottom: 1px solid #eee;
      position: relative;
      padding-left: 50%;
      white-space: normal;
      text-align:left;
    }

    {{-- 設定表單格式 --}}
    #no-more-tables td:before {
      position: absolute;
      top: 6px;
      left: 6px;
      width: 45%;
      padding-right: 10px;
      white-space: nowrap;
      text-align:left;
      font-weight: bold;
      {{-- 將data-title(標題欄位)欄位資料插入每列的td元素中 --}}
      content: attr(data-title);
    }

</style>
@endsection
