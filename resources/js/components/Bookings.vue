<template>
<div>
    <spinner v-if="loadingContent"></spinner>
    <div v-else class="container">
        <div v-if="showBookings">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li :class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                        <a @click="fetchBookings(pagination.prev_page_url)" class="page-link" href="#">&lt;</a>
                    </li>
                    <li class="page-item disabled"><a class="page-link" href="#">Страница {{pagination.current_page}} of {{pagination.last_page}}</a></li>
                    <li :class="[{disabled: !pagination.next_page_url}]" class="page-item">
                        <a @click="fetchBookings(pagination.next_page_url)" class="page-link" href="#">&gt;</a>
                    </li>
                </ul>
            </nav>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Mарка авто</th>
                        <th scope="col">Hомер парковочного места</th>
                        <th scope="col">Bъезд / Bыезд</th>
                        <th v-if="!customerId" scope="col">Kлиент</th>
                        <th scope="col">Цена</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="b in bookings" :key="b.id">
                        <td>{{b.car_model}}</td>
                        <td>{{b.parking_place.nom}}</td>
                        <td><span class="text-success">{{b.date_in}}</span> / <span class="text-danger">{{b.date_out}}</span></td>
                        <td v-if="!customerId">
                            <a @click.prevent="getCustomer(b.customer)" href="#">
                                {{ b.customer.fio }}
                            </a>
                        </td>
                        <td>{{b.price}} &#8381;</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else-if="showCustomer">
            <div class="card">
                <div class="card-header">
                    Клиент
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ customer.fio }}</h5>
                    <p class="card-text">{{customer.phone}}</p>
                    <p class="card-text">{{customer.email}}</p>
                    <a @click.prevent="backToBookings" href="#" class="btn btn-primary">Назад</a>
                </div>
            </div>
        </div>
    </div>
    
</div>
    
</template>

<script>
export default {
    computed: {
        bookings() {
            return this.$store.getters.getBookings
        },
        loadingContent () {
            return this.$store.getters.bookingsLoadingValue
        }
    },
    props: {
        customerId: {
            type: Number,
            default: 0
        }
    },
    data () {
        return {
            pagination: {},
            showBookings: true,
            showCustomer: false,
            customer: {}
        }
    },
    created () {
        this.fetchBookings()
    },
    methods: {
        fetchBookings (page_url) {
            let vm = this;
            
            page_url = page_url || '/bookings/get-bookings?customer_id=' + this.customerId

            this.$store.dispatch('fetchBookings', page_url)
            .then(res => {
                vm.makePagination(res.current_page,res.last_page,res.next_page_url,res.prev_page_url)
            })
            .catch(err => {
                alert(err)
            })
        },
        makePagination (cur_pg,lst_pg,nxt,prv) {
            let pagination = {
                current_page: cur_pg,
                last_page: lst_pg,
                next_page_url: nxt,
                prev_page_url: prv
            }

            this.pagination = pagination;
        },
        getCustomer(customer) {
            this.customer = customer
            this.showBookings = false
            this.showCustomer = true
        },
        backToBookings() {
            this.customer = {}
            this.showBookings = true
            this.showCustomer = false
        }
    }
}
</script>