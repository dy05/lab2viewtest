<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Menu</div>
                    <div class="card-body">
                        <ul>
                            <li><router-link to="/">Create User</router-link></li>
                            <li><router-link to="/active">Active User</router-link></li>
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


        new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001'
        }).private('connected_users')
        .listen('.app.new_member', function (e) {
            debugger
            console.log(e)
        })
    }
}

</script>
