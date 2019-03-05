@extends('layouts.app')
@section('content')

    <header class="page-header">
        <h2>Dashboard</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Dashboard</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12" id="info">
            <section class="panel">
                <header class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
                    </div>

                <h2 class="panel-title">Users data</h2>
                </header>
                <div class="panel-body">
                    <a href="#modalForm" class="modalCreate mb-xs mt-xs mr-xs modal-basic btn btn-primary">Add user</a>
                    <?php if (!$data->isEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-none">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Date of birth</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td class="id" style="display:none">{{$item->id}}</td>
                                        <td class="name">{{$item->name}}</td>
                                        <td class="email">{{$item->email}}</td>
                                        <td class="city">{{$item->city}}</td>
                                        <td data-value="{{$item->country_id}}" class="country">{{$item->countryName}}</td>
                                        <td class="date_of_birth">{{$item->date_of_birth}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td class="actionsCell"><a href="#modalForm" class="modalEdit"><i aria-hidden="true" class="fa fa-pencil primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modalDelete" class="modalDelete"><i aria-hidden="true" class="fa fa-trash text-danger"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                <?php else: echo "<h3>No data</h3>"; endif;?>
                </div>
            </section>
            {{$data->links()}}
        </div>
        <div class="col-lg-12">
            <div id="modalForm" class="modal-block modal-block-primary mfp-hide modalForm">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">Add user</h2>
                    </header>
                    <div class="panel-body">
                        <div class="modal-wrapper">
                            <form id="formData" class="form-horizontal form-bordered" method="get">
                                    {!! csrf_field() !!}
                                    <input type="text" hidden="true" value="" id="uid" name="id">
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="name">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="name" name="name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="email">Email</label>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="password">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label class="col-md-3 control-label" for="country">Country</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="country" name="country">
                                    </div>
                                </div> --}}
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="country">Country</label>
                                    <div class="col-md-6">
                                <select class="form-control mb-md" id="country" name="country">
                                    <?php foreach($dataCountry as $item): ?>
                                    <?php echo "<option value='".$item->id."'>".$item->name."</option>" ; ?>
                                    <?php endforeach;?>
                                </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="city">City</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="city" name="city">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="date_of_birth">Date of birth</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" data-plugin-datepicker="" class="form-control" id="date_of_birth" name="date_of_birth">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button id="confirm" class="btn btn-primary modal-confirm">Confirm</button>
                                <button id="cancel" class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </section>
            </div>

            <div id="modalDelete" class="modal-block modal-block-danger mfp-hide modalForm">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">Remove user</h2>
                    </header>
                    <div class="panel-body">
                        <div class="modal-wrapper">
                            <p>Are you sure you want to delete <span class="userEmail text-primary" style="font-style:italic;"></span></p>
                                <form id="deleteUser" class="form-horizontal form-bordered" method="get">
                                    {!! csrf_field() !!}
                                    <input type="text" hidden="true" value="" id="duid" name="id">
                                </form>
                        </div>
                    </div>
                    <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button id="confirmDelete" class="btn btn-danger modal-confirm">Confirm</button>
                                    <button id="cancel" class="btn btn-default modal-dismiss">Cancel</button>
                                </div>
                            </div>
                    </footer>
                </section>
            </div>
        </div>
    </div>
    <script>
        
    </script>
@endsection

