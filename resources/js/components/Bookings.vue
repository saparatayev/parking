<template>
<div>
    <spinner v-if="loadingContent"></spinner>
    <div v-else class="container">
        <h2>Забронированные места</h2>
        <div v-if="showBookings">
            <form v-if="!customerId" class="mb-2" @submit.prevent="fetchBookings()">
                <div class="row">
                    <div class="col">
                        <label>Дата</label>
                        <input v-model="filterData.sort_date" type="date" namd="sort_date" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Статус</label>
                        <select v-model="filterData.sort_status" name="sort_status" class="form-select" required>
                            <option value="in">Въехал</option>
                            <option value="out">Выехал</option>
                        </select>
                    </div>
                    <div class="col">
                        <label> </label>
                        <input type="submit" class="btn btn-secondary form-control" value="Показать">
                    </div>
                    <div class="col">
                        <label> </label>
                        <a href="#" @click="withoutFilters()" class="btn btn-secondary form-control">Показать все</a>
                    </div>
                </div>
                <div class="row col">
                    <span>Будут показаны брони с датой -- <span class="text-light bg-secondary">{{ filterData.sort_date }}</span>
                        -- фактического -- 
                        <span class="text-light bg-secondary">
                            {{ filterData.sort_status == 'out' ? 'выезда' : filterData.sort_status == 'in' ? 'въезда' : '' }} 
                        </span>--
                    </span>
                </div>
            </form>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li :class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                        <a @click="fetchBookings(pagination.prev_page_url)" class="page-link" href="#">&lt;</a>
                    </li>
                    <li v-for="(link, index) in pagination.links" :key="index" class="page-item" :class="[{disabled: pagination.current_page == index + 1}]">
                        <a @click="fetchBookings(link)" href="#" class="page-link">{{ index + 1 }}</a>
                    </li>
                    <li :class="[{disabled: !pagination.next_page_url}]" class="page-item">
                        <a @click="fetchBookings(pagination.next_page_url)" class="page-link" href="#">&gt;</a>
                    </li>
                </ul>
            </nav>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Mарка авто</th>
                        <th scope="col">Парк. место</th>
                        <th scope="col">Bъезд / Bыезд (бронь)</th>
                        <th scope="col">Bъехал / Bыехал (статус)</th>
                        <th v-if="!customerId" scope="col">Kлиент</th>
                        <th scope="col">Цена/д.</th>
                        <th scope="col">Всего</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="b in bookings" :key="b.id">
                        <td>{{b.car_model}}</td>
                        <td>{{b.parking_place.nom}}</td>
                        <td><span class="text-success">{{b.date_in}}</span> / <span class="text-danger">{{b.date_out}}</span></td>
                        <td><span class="text-success">{{b.actual_date_in}}</span> / <span class="text-danger">{{b.actual_date_out}}</span></td>
                        <td v-if="!customerId">
                            <a @click.prevent="getCustomer(b.customer)" href="#">
                                {{ b.customer.fio }}
                            </a>
                        </td>
                        <td>{{b.price}} &#8381;</td>
                        <td>{{b.amount}} &#8381;</td>
                        <td><a @click.prevent="getStatus(b)" href="#">Статус</a></td>
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
        <div v-else-if="showStatus">
            <div class="card">
                <div class="card-header">
                    Статус
                </div>
                <div class="card-body">
                    <form @submit.prevent="changeStatus">
                        <input type="hidden" name="id" v-model="status.id">
                        <div class="mb-3 form-check">
                            <input type="date" v-model="status.actual_date_in" class="form-control" name="actual_date_in" id="actual_date_in">
                            <label class="form-check-label" for="actual_date_in">Въехал</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="date" v-model="status.actual_date_out" class="form-control" name="actual_date_out" id="actual_date_out">
                            <label class="form-check-label" for="actual_date_out">Выехал</label>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Сохранить">
                    
                        <a @click.prevent="backToBookings" href="#" class="btn btn-secondary">Назад</a>
                            
                    </form>
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
            showStatus: false,
            customer: {},
            status: {
                id: 0,
                actual_date_in: '',
                actual_date_out: ''
            },
            filterData: {
                sort_date: '',
                sort_status: ''
            }
        }
    },
    created () {
        this.fetchBookings()
    },
    methods: {
        fetchBookings (page_url) {
            let vm = this;
            
            page_url = page_url || '/bookings/get-bookings?customer_id=' + this.customerId + '&sort_date=' + this.filterData.sort_date + '&sort_status=' + this.filterData.sort_status

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
                next_page_url: this.customerId && nxt ? nxt + '&customer_id=' + this.customerId : nxt,
                prev_page_url: this.customerId && prv ? prv + '&customer_id=' + this.customerId : prv,
                links: []
            }

            for(let i = 1; i <= lst_pg; i++) {
                let url = '/bookings/get-bookings?page=' + i;
                url = !this.customerId ? url : url + '&customer_id=' + this.customerId
                pagination.links.push(url)
            }

            if(this.filterData.sort_date != '' && this.filterData.sort_status != '') {
                if(pagination.next_page_url) {
                    pagination.next_page_url = `${pagination.next_page_url}&sort_date=${this.filterData.sort_date}&sort_status=${this.filterData.sort_status}`
                }
                if(pagination.prev_page_url) {
                    pagination.prev_page_url = `${pagination.prev_page_url}&sort_date=${this.filterData.sort_date}&sort_status=${this.filterData.sort_status}`
                }
                
                let vm = this;

                let newArr = [];

                pagination.links.forEach(function(item) {
                    newArr.push(`${item}&sort_date=${vm.filterData.sort_date}&sort_status=${vm.filterData.sort_status}`)
                })

                pagination.links = newArr;
            }

            this.pagination = pagination;
        },
        changeStatus() {
            this.$store.dispatch('changeStatus', this.status)
            .then(res => {
                if(res) {
                    if(res.status === 200) {
                        alert('Status changed')
                        this.fetchBookings()
                        this.backToBookings()
                    } else {
                        alert('Ошибка ' + res.status)
                    }
                }
            })
            .catch(err => alert(err))
        },
        getStatus(booking) {
            this.customer = {}
            this.status = {
                id: booking.id,
                actual_date_in: booking.actual_date_in,
                actual_date_out: booking.actual_date_out
            }
            this.showBookings = false
            this.showCustomer = false
            this.showStatus = true
        },
        getCustomer(customer) {
            this.customer = customer
            this.status = {
                id: 0,
                actual_date_in: '',
                actual_date_out: ''
            },
            this.showBookings = false
            this.showCustomer = true
            this.showStatus = false
        },
        backToBookings() {
            this.customer = {}
            this.status = {
                id: 0,
                actual_date_in: '',
                actual_date_out: ''
            },
            this.showBookings = true
            this.showCustomer = false
            this.showStatus = false
        },
        withoutFilters() {
            this.filterData = {
                sort_date: '',
                sort_status: ''
            }
            this.fetchBookings()
        }
    }
}
</script>