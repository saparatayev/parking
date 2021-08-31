export default {
    state: {
        parking_places: [],
        loadingContent_vuex: true
    },
    mutations: {
        loadingvuex (state,boolValue) {
            state.loadingContent_vuex = boolValue
        },
        setParkingPlaces (state,loadedParkingPlaces) {
            state.parking_places = loadedParkingPlaces.data
        }
    },
    actions: {
        fetchParkingPlaces ({commit},page_url) {
            
            commit('loadingvuex', true)

            return new Promise((resolve, reject) => {
                fetch(page_url,{
                    method:'get',
                    headers: {
                        'Access-Control-Allow-Headers': '*',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(res => res.json())
                .then(res => {
                    
                    commit('setParkingPlaces',res)
                    commit('loadingvuex', false)

                    resolve(res)
                })
                .catch(err => reject(err))
            })
        },
        addParkingPlace ({commit}, newParkingPlace) {
            
            commit('loadingvuex', true)

            return new Promise((resolve,reject) => {
                const formData = new FormData();

                formData.append('id', newParkingPlace.id);
                formData.append('nom', newParkingPlace.nom);
                formData.append('price', newParkingPlace.price);

                console.log(newParkingPlace)

                fetch('/parking-places/create',{
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
                .catch(err => {
                    reject(err)
                })
            })
        },
        updateParkingPlace ({commit},newParkingPlace) {
            commit('loadingvuex', true)

            return new Promise((resolve,reject) => {
                const formData = new FormData();
                formData.append('id', newParkingPlace.id);
                formData.append('nom', newParkingPlace.nom);
                formData.append('price', newParkingPlace.price);

                fetch(`/parking-places/${newParkingPlace.id}`,{
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
        },
        deleteParkingPlace ({commit},id) {
            commit('loadingvuex', true)
                
            if(confirm('Вы уверены?')) {
                return new Promise((resolve,reject) => {
                    fetch(`/parking-places/${id}`,{
                        method:'delete',
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
        }
    },
    getters: {
        getParkingPlaces: state => state.parking_places,
        setLoadingValue: state => state.loadingContent_vuex
    }
}