<template>
<div>
    <spinner v-if="loadingContent"></spinner>
    <div v-else class="container">
        <h2>Парковочные места</h2>
        <form @submit.prevent="addOrUpdatePlace" class="mb-3">
            <div class="form-group mb-2">
                <label>Номер парковочного места</label>
                <input required type="text" class="form-control" v-model="parkingPlace.nom">
            </div>
            <div class="form-group mb-2">
                <label>Цена</label>
                <input required type="number" step="0.01" class="form-control" v-model="parkingPlace.price">
            </div>
            <button class="btn btn-secondary btn-block" type="submit">{{ edit ? 'Изменить' : 'Добавить' }}</button>
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li :class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a @click="fetchParkingPlaces(pagination.prev_page_url)" class="page-link" href="#">&lt;</a>
                </li>
                <li class="page-item disabled"><a class="page-link" href="#">Страница {{pagination.current_page}} of {{pagination.last_page}}</a></li>
                <li :class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a @click="fetchParkingPlaces(pagination.next_page_url)" class="page-link" href="#">&gt;</a>
                </li>
            </ul>
        </nav>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Price</th>
                    <th scope="col">Booked</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="place in parkingPlaces" :key="place.id">
                    <td>{{place.nom}}</td>
                    <td>{{place.price}} &#8381;</td>
                    <td><span class="badge bg-danger">{{place.booked ? 'booked' : ''}}</span></td>
                    <td><a @click.prevent="editParkingPlace(place)" href="#">Изменить</a></td>
                    <td><a @click.prevent="deletParkingPlace(place.id)" href="#" class="text-danger">Удалить</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    
</template>

<script>
export default {
    computed: {
        parkingPlaces() {
            return this.$store.getters.getParkingPlaces
        },
        loadingContent () {
            return this.$store.getters.setLoadingValue
        }
    },
    data () {
        return {
            parkingPlace: {
                id: '',
                nom: '',
                price: 0,
            },
            edit: false,
            pagination: {}
        }
    },
    created () {
        this.fetchParkingPlaces()
    },
    methods: {
        fetchParkingPlaces (page_url) {
            let vm = this;
            
            page_url = page_url || '/parking-places/get-parking-places'

            this.$store.dispatch('fetchParkingPlaces', page_url)
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
        addOrUpdatePlace () {
            if(!this.edit) {
                this.$store.dispatch('addParkingPlace', this.parkingPlace)
                .then((res) => {
                    this.parkingPlace.id = ''
                    this.parkingPlace.nom = ''
                    this.parkingPlace.price = 0

                    if(res.status === 201) {
                        alert('Парковочное место добавлено')
                        this.fetchParkingPlaces()
                    } else {
                        alert('Ошибка ' + res.status)
                    }
                })
                .catch(err => alert(err))
            } else {
                this.$store.dispatch('updateParkingPlace', this.parkingPlace)
                .then((res) => {
                    this.edit = false
                    this.parkingPlace.id = ''
                    this.parkingPlace.nom = ''
                    this.parkingPlace.price = 0

                    if(res) {
                        if(res.status === 200) {
                            alert('Article Updated')
                            this.fetchParkingPlaces()
                        } else {
                            alert('Ошибка ' + res.status)
                        }
                    }
                })
                .catch(err => alert(err))
            }
        },
        editParkingPlace(place) {
            this.edit = true;
            this.parkingPlace.id = place.id;
            this.parkingPlace.nom = place.nom;
            this.parkingPlace.price = place.price;
        },
        deletParkingPlace(id) {
            this.$store.dispatch('deleteParkingPlace', id)
            .then((res)=> {
                if(res) {
                    if(res.status === 204) {
                        alert('Удалено')
                        this.fetchParkingPlaces()
                    }
                    else {
                        alert('Ошибка ' + res.status)
                    }
                }
            })
            .catch(err => alert(err))
        }
    }
}
</script>