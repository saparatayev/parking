<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Клиенты 
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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
                    <form action="{{ route('newCustomer') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="fio" class="form-label">ФИО</label>
                            <input type="text" class="form-control" id="fio" name="fio" value="{{ old('fio') }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Тел.</label>
                            <input type="phone" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                        <button type="submit" class="btn btn-secondary">Submit</button>
                    </form>
                    <!-- end form -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
