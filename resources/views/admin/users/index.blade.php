@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Usuarios</div>
                
                @if (Session::has('message'))
                    <p class="alert alert-success">{{ Session::get('message') }}</p>
                @endif

                <div class="panel-body">
                    {!! Form::model(Request::all(), ['route' => 'admin.users.index', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) !!}
                        <div class="form-group">
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Buscar']) !!}
                            {!! Form::select('type', config('options.types'), null, ['class' => 'form-control']) !!}
                        </div>
                        <button type="submit" class="btn btn-warning">Buscar</button>
                    {!! Form::close() !!}
                    <p>
                        <a class="btn btn-info" href="{{ route('admin.users.create') }}" role="button">Nuevo Usuario</a>
                    </p>
                    <p>
                        Hay un total de {{ $users->total() }}
                    </p>
                    <p>
                        Página {{ $users->currentPage() }} / {{ $users->lastPage() }}
                    </p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr data-id="{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->type }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}">Editar</a>
                                        <a href="#" class="btn-delete">Eliminar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $users->setPath('')->appends(Request::only(['name', 'type']))->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

{!! Form::open(['route' => ['admin.users.destroy', ':USER_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) !!}
{!! Form::close() !!}

@endsection