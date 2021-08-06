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
