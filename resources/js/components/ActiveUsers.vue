<template>
    <div>
        <h3>Utilisateurs actifs</h3>
        <hr>
        <div class="mt-4">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Login at</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="users.length > 0" v-for="(user, index) in users" :key="index">
                        <td>
                            {{ user.id }}
                        </td>
                        <td>
                            {{ user.name }}
                        </td>
                        <td>
                            {{ user.email }}
                        </td>
                        <td>
                            {{ user.logs[0]?.created_at }}
                        </td>
                    </tr>
                    <tr v-else>
                        <td colspan="4" class="text-center">
                            There's no log
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: 'ActiveUsers',
    mounted() {
        console.log(this.users.length)
    },
    computed: {
        ...mapGetters({
            users: 'getUsers'
        })
    },
    methods: {
    }
}
</script>
