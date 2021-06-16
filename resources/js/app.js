import { createApp } from 'vue'
import { createStore } from 'vuex'
import { createRouter, createWebHashHistory } from 'vue-router'
import App from "./components/App"
import CreateUser from "./components/CreateUser";

require('./bootstrap')

const ActiveUsers = { template: '<div>ActiveUsers</div>' }

const routes = [
    {
        path: "/",
        name: "create_user",
        component: CreateUser,
    },
    {
        path: "/active",
        name: "active_users",
        component: ActiveUsers,
    },
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

const store = createStore({
    state () {
        return {
            authUser: null
        }
    },
    getters: {
        authUser: (state) => state.authUser
    },
    mutations: {
        setUser (state, {user}) {
            state.authUser = user
        }
    }
})

let $rootApp = document.getElementById('myApp')
if ($rootApp) {
    axios.get('/sanctum/csrf-cookie').then(response => {
        createApp(App).use(router)
            .use(store)
            .mount($rootApp)
    })
}
