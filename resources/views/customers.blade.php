@extends('layouts.app')

@section('content')

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="alert alert-success mt-3">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('can_not_delete'))
                <div class="alert alert-danger mt-3">
                    {{ session('can_not_delete') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2>Клиенты <a href="{{ route('newCustomer') }}" class="ml-5 btn btn-secondary">Добавить</a></h2>
                    <table class="p-6 table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">FIO</th>
                                <th scope="col">Тел.</th>
                                <th scope="col">Email</th>
                                <th scope="col">Брони</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->fio }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>
                                        @if(count($customer->bookings) > 0)
                                            <a href="{{ route('bookings') }}?customer_id={{ $customer->id }}">Смотреть</a>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('editCustomer', ['id' => $customer->id]) }}">Изменить</a></td>
                                    <td>
                                        <form action="{{ route('deleteCustomer',['id' => $customer->id]) }}" method="post">
                                            @csrf
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="text-danger">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
@endsection
