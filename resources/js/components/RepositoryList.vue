<template>
    <div>
        <ul class="list-group">
            <li v-for="item in repositories" :key="item.name" class="list-group-item d-flex justify-content-between align-items-center">
                {{ item.name }} - {{ item.lastCommitMessage }}
            </li>
        </ul>
        <hr>
        <form id="signup-form" @submit.prevent="add" class="form-group">
            <div class="input-group mb-3">
                <input v-model="newRepositoryUrl" type="text" class="form-control" placeholder="Paste git repository url here..." aria-label="git repository" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button">Submit</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    mounted() {
        axios.get('/api/repositories')
            .then((response) => {
                this.repositories = response.data.data
            })
            .catch((error) => {
                console.log(error)
            })
    },

    data () {
        return {
            repositories: [],
            newRepositoryUrl: ''
        }
    },

    methods: {
        add() {
            axios.post('/api/repositories', {
                url: this.newRepositoryUrl,
            })
                .then((response) => {
                    // this.repositories = response.data.data
                })
                .catch((error) => {
                    console.log(error)
                })
        }
    }
}
</script>
