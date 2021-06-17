import { createApp } from 'vue'
import { createStore } from 'vuex'
import { createRouter, createWebHashHistory } from 'vue-router'
import App from "./components/App"
import CreateUser from "./components/CreateUser";
import ActiveUsers from "./components/ActiveUsers";

require('./bootstrap')

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
            authUser: null,
            activeUsers: [],
        }
    },
    getters: {
        authUser: (state) => state.authUser,
        getUsers: (state) => state.activeUsers,
        getUsersIds: (state) => state.activeUsers.map((user) => user.id)
    },
    mutations: {
        setUser (state, {user}) {
            state.authUser = user
        },
        resetUsers (state, users) {
            state.activeUsers = users.filter((user) => user.id !== state.authUser.id)
        },
        addActiveUser (state, {user}) {
            if (! this.getters.getUsersIds.includes(user.id)) {
                state.activeUsers.push(user)
            }
        },
        removeActiveUser (state, activeUser) {
            state.activeUsers = state.activeUsers.filter((user) => user.id !== activeUser.id)
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
