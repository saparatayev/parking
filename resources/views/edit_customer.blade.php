@extends('layouts.app')

@section('content')

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2>Клиенты </h2>
                    <!-- form to add new customer -->
                    @if(count($errors))
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('editCustomer',['id'=> $old->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $old->id }}">
                        <div class="mb-3">
                            <label for="fio" class="form-label">ФИО</label>
                            <input type="text" class="form-control" id="fio" name="fio" value="{{ $old->fio }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $old->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Тел.</label>
                            <input type="phone" class="form-control" id="phone" name="phone" value="{{ $old->phone }}">
                        </div>
                        <button type="submit" class="btn btn-secondary">Submit</button>
                    </form>
                    <!-- end form -->
                </div>
            </div>
        </div>
    
@endsection
