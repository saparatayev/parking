@extends('layouts.app')

@section('content')


    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Забронировать место
    </h2>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" >
                    <!-- component -->
                    <book-form></book-form>
                    
                    <!-- end component -->
                </div>
            </div>
        </div>
    </div>


@endsection