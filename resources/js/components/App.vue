<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Menu</div>
                    <div class="card-body">
                        <ul>
                            <li><router-link to="/">Create User</router-link></li>
                            <li><router-link to="/active">Online Users</router-link></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <router-view />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Echo from "laravel-echo";

export default {
    name: "App",
    async mounted() {
        let user = window.LaravelApp.user;
        if (user) {
            this.$store.commit('setUser', {user})
        }

        const echoHeaders = {
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001'
        };

        let echoServer = new Echo(echoHeaders).join('connected_users')

        echoServer.here((users) => {
            console.log('Here')
            this.$store.commit('resetUsers', users)
            console.log(users)
        }).joining((user) => {
            console.log('Joining')
            console.log(user)
            this.$store.commit('addActiveUser', {user})
        }).leaving((user) => {
            console.log('Leaving')
            console.log(user)
            this.$store.commit('removeActiveUser', user)
        })

        new Echo(echoHeaders).private('notify_member')
            .listen('.app.notify_member', function (e) {
                console.log('loll')
                console.log(e)
            })
    }
}

</script>
