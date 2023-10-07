<template>
    <div id="app">
        <nav class="navbar mb-5" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a
                    class="navbar-item"
                    href="https://bulma.io"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <img
                        src="https://bulma.io/images/bulma-logo.png"
                        width="112"
                        height="28"
                    />
                </a>
            </div>

            <div class="navbar-menu">
                <div class="navbar-start">
                    <router-link to="/" class="navbar-item">Home</router-link>
                </div>

                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="buttons">
                            <button
                                @click="logout"
                                v-if="isLoggedIn"
                                class="button is-dark"
                            >
                                Log out
                            </button>
                            <button
                                @click="redirect"
                                v-else
                                class="button is-light"
                            >
                                Log in
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <router-view />
    </div>
</template>

<script>
export default {
    computed: {
        isLoggedIn: () => false,
    },
    methods: {
        redirect() {
            //
        },
        logout() {
            //
        },
    },
};
</script>
