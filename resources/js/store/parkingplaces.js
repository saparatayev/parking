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
                fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    
                    commit('setParkingPlaces',res)
                    commit('loadingvuex', false)

                    resolve(res)
                })
                .catch(err => reject(err))
            })
        },
        addParkingPlace ({commit,state}, newParkingPlace) {
            commit('loadingvuex', true)

            return new Promise((resolve,reject) => {
                const formData = new FormData();
                formData.append('id', newParkingPlace.id);
                formData.append('nom', newParkingPlace.nom);
                formData.append('price', newParkingPlace.price);

                fetch('https://parking/parking-places/create',{
                    method: 'post',
                    body: formData
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
        updateParkingPlace ({commit,state},newParkingPlace) {
            commit('loadingvuex', true)

            return new Promise((resolve,reject) => {
                const formData = new FormData();
                formData.append('id', newParkingPlace.id);
                formData.append('nom', newParkingPlace.nom);
                formData.append('price', newParkingPlace.price);
                // formData.append('_method', 'PUT');

                fetch(`https://parking/parking-places/${newParkingPlace.id}`,{
                    method: 'post',
                    body: formData
                })
                .then((res) => {
                    commit('loadingvuex', false)

                    resolve(res)
                })
                .catch(err => reject(err))
            })
        },
        deleteParkingPlace ({commit,state},id) {
            commit('loadingvuex', true)
                
            if(confirm('Вы уверены?')) {
                return new Promise((resolve,reject) => {
                    fetch(`https://parking/parking-places/${id}`,{
                        method:'delete'
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