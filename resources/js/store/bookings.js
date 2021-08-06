export default {
    state: {
        bookings: [],
        loadingContent_vuex: true
    },
    mutations: {
        loadingvuex (state,boolValue) {
            state.loadingContent_vuex = boolValue
        },
        setBookings (state,loadedBookings) {
            state.bookings = loadedBookings.data
        }
    },
    actions: {
        fetchBookings ({commit},page_url) {
            
            commit('loadingvuex', true)

            return new Promise((resolve, reject) => {
                fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    
                    commit('setBookings',res)
                    commit('loadingvuex', false)

                    resolve(res)
                })
                .catch(err => reject(err))
            })
        },
        changeStatus({commit}, status) {
            commit('loadingvuex', true)

            return new Promise((resolve,reject) => {
                const formData = new FormData();
                formData.append('id', status.id);
                formData.append('actual_date_in', status.actual_date_in);
                formData.append('actual_date_out', status.actual_date_out);

                fetch(`/bookings/change-status/${status.id}`,{
                    method: 'post',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then((res) => {
                    commit('loadingvuex', false)

                    resolve(res)
                })
                .catch(err => reject(err))
            })
        }
    },
    getters: {
        getBookings: state => state.bookings,
        bookingsLoadingValue: state => state.loadingContent_vuex
    }
}