<template>
<div>
    <spinner v-if="loadingContent"></spinner>
    <div v-else class="container">
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
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="place in parkingPlaces" :key="place.id">
                    <td>{{place.nom}}</td>
                    <td>{{place.price}} &#8381;</td>
                    <td><a href="#">Изменить</a></td>
                    <td><a href="#" class="text-danger">Удалить</a></td>
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
                price: '',
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
        // addOrUpdateArticle () {
        //     if(!this.edit) {
        //         this.$store.dispatch('addArticle_vuex',this.article)
        //         .then((res) => {
        //             this.article.title = ''
        //             this.article.title_ru = ''
        //             this.article.body = ''
        //             this.article.img = ''
        //             this.image = ''

        //             if(res) {
        //                 if(res.status === 401) {
        //                     alert('Unauthorized logged in but expired token')
        //                     this.$store.dispatch('logout')
        //                     .then(() => {
        //                         this.$router.push({name:'signin'})
        //                     })
        //                 }
        //                 else {
        //                     alert('Article Added')
        //                     this.fetchArticles()
        //                 }
        //             }
        //         })
        //         .catch(err => alert(err))
        //     } else {
        //         this.$store.dispatch('updateArticle_vuex',this.article)
        //         .then((res) => {
        //             this.edit = false
        //             this.article.title = ''
        //             this.article.title_ru = ''
        //             this.article.body = ''
        //             this.article.img = ''
        //             this.image = ''

        //             if(res) {
        //                 if(res.status === 401) {
        //                     alert('Unauthorized')
        //                     this.$store.dispatch('logout')
        //                     .then(() => {
        //                         this.$router.push({name:'signin'})
        //                     })
        //                 }
        //                 else {
        //                     alert('Article Updated')
        //                     this.fetchArticles()
        //                 }
        //             }
        //         })
        //         .catch(err => alert(err))
        //     }
        // },
        // editArticle(article) {
        //     this.edit = true;
        //     this.article.id = article.id;
        //     this.article.article_id = article.id;
        //     this.article.title = article.title;
        //     this.article.title_ru = article.title_ru;
        //     this.article.body = article.body;
        //     this.article.img = article.img;
        //     this.image = article.img;
        // },
        // deleteArticle (id) {
        //     this.$store.dispatch('deleteArticle_vuex',id)
        //     .then((res)=> {
        //         if(res) {
        //             if(res.status === 401) {
        //                 alert('Unauthorized')
        //                 this.$store.dispatch('logout')
        //                 .then(() => {
        //                     this.$router.push('signin')
        //                 })
        //             }
        //             else {
        //                 alert('Article deleted')
        //                 this.fetchArticles()
        //             }
        //         }
        //     })
        //     .catch(err => alert(err))
        // }
    }
}
</script>