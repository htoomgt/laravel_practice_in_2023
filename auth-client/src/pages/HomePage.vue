<template>
    <div class="container">
        <div v-if="isLoggedIn" class="card">
            <div class="card-content">
                <div class="content">
                    Welcome back <strong>{{ user.name }}!</strong>
                </div>
            </div>
        </div>
        <div v-else class="notification is-danger">You're not logged in!</div>
    </div>
</template>

<script>
export default {
    name: "HomePage",
    data() {
        return {
            user: {},
        };
    },
    computed: {
        isLoggedIn: () => false,
    },
};
</script>
