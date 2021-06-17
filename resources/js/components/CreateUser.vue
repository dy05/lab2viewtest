<template>
    <div>
        <h3>Ajouter un Utilisateur</h3>
        <hr>
        <form action="" v-on:submit.prevent="submitData()">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" v-model="user.name" required/>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" v-model="user.email" required/>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" v-model="user.phone"/>
            </div>
            <div>
                <button class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: 'CreateUser',
    data() {
        return {
            user: {
                name: 'name',
                email: 'dyosby237@gmail.com',
                phone: ''
            },
        }
    },
    methods: {
        submitData() {
            axios.post('/api/user', this.user).then((response) => {
                // alert('Ajouter avec success')
                // this.$router.push('/active')
                console.log(response.data)
            }).catch((e) => {
                let errors = e.response.data.errors;
                console.log(errors)
            })
        },
    }
}
</script>
