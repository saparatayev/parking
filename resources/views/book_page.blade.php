@extends('layouts.app')

@section('content')
    

    
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" >
                    <h2>Забронировать место</h2>
                    <form action="{{ route('bookPage') }}" method="post">
                        @if(session('status'))
                            <div class="alert alert-success mt-3">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(count($errors))
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label>Марко авто</label>
                                <input type="text" class="form-control" name="car_model" required>
                            </div>
                            <div class="col">
                                <label>Номер парковочного места</label>
                                <select class="form-select" name="nom" size=5 id="my_select" data-skip="5" required>
                                    @foreach($empty_parking_places as $p)
                                        <option value="{{$p->id}}">{{$p->nom}}</option>
                                    @endforeach
                                    <option class="more" onclick="loadMore()" id="more" value="">Еще</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label>Дата въезда</label>
                                <input class="form-control" type="date" name="date_in"requireD>
                            </div>
                            <div class="col">
                                <label>Дата выезда</label>
                                <input class="form-control" type="date" name="date_out"requireD>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <!-- if customer exists -->
                                <input type="hidden" name="customer_id" id="customer_id">

                                <label>Email</label>
                                <input class="form-control" id="email" onkeyup="findCustomer()" type="email" name="email" required>
                                <div class="form-text">Начните набирать email. Если в базе он есть, то поля Тел. и ФИО заполнятся автоматически.</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label>FIO</label>
                                <input class="form-control" id="fio" type="text" name="fio" required>
                            </div>
                            <div class="col">
                                <label>Тел.</label>
                                <input class="form-control" id="phone" type="text" name="phone" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <input class="btn btn-secondary" type="submit" value="Забронировать">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
 


@endsection

@section('scripts')
<script>
    function loadMore() {
        let skipValue = my_select.getAttribute('data-skip')

        fetch('/parking-places/get-more?skip=' + skipValue)
        .then(res => res.json())
        .then((res) => {
            res.result.forEach(function(item){
                more.insertAdjacentHTML('beforebegin',
                 `<option value="${item.id}">${item.nom}</option>`)
            })

            my_select.setAttribute('data-skip', +skipValue + 5)
        })
        .catch(err => alert(err))
    }

    function findCustomer() {
        if(email.value != '') {
            fetch('/customers/find/' + email.value, {
                method:'get',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(res => res.json())
            .then(res => {
                if(res.customer) {
                    fio.value = res.customer.fio
                    fio.disabled = true
                    
                    phone.value = res.customer.phone
                    phone.disabled = true

                    customer_id.value = res.customer.id
                } else {
                    fio.value = ''
                    fio.disabled = false
                    phone.value = ''
                    phone.disabled = false
                    
                    customer_id.value = ''
                }
                
            })
            .catch(err => console.log(err))
        }
        
    }
</script>
@endsection