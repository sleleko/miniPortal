<template>
  <div>
    <b-navbar toggleable="md" type="dark" variant="dark">
      <b-container>
        <b-navbar-brand :to="{name: 'index'}">
          {{AppName}}
        </b-navbar-brand>

        <b-navbar-nav class="ml-md-auto order-md-last">
          <b-nav-item @click.prevent="showModal" v-if="!$auth.loggedIn">
            Login
            <fa :icon="['fas', 'sign-in-alt']"/>
          </b-nav-item>
          <b-dropdown :text="$auth.user.fullname" id="user-links" variant="dark" right v-else>
            <b-dropdown-item :to="{name: 'user-profile'}">
              Settings
            </b-dropdown-item>
            <b-dropdown-divider/>
            <b-dropdown-item class="logout" @click.prevent="Logout">
              Logout
              <fa :icon="['fas', 'sign-out-alt']" size="1x" class="ml-auto"/>
            </b-dropdown-item>
          </b-dropdown>
        </b-navbar-nav>

        <b-navbar-toggle target="nav-collapse"/>

        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav>
            <b-nav-item :to="{name: 'admin'}" v-if="$auth.hasScope('users') || $auth.hasScope('users/get')">Admin area</b-nav-item>
          </b-navbar-nav>
        </b-collapse>

      </b-container>
    </b-navbar>

    <b-modal title="Authorization" :visible="isModal && !$auth.loggedIn" @hidden="hideModal" hide-footer>
      <form-auth/>
    </b-modal>
  </div>
</template>

<script>
  import {faSignInAlt, faSignOutAlt} from '@fortawesome/free-solid-svg-icons'
  import FormAuth from '../components/form-auth'

  export default {
    name: 'app-header',
    components: {FormAuth},
    data() {
      return {
        isModal: false,
        AppName: process.env.APP_NAME,
      }
    },
    methods: {
      showModal() {
        this.isModal = true;
      },
      hideModal() {
        this.isModal = false;
      },
      Logout() {
        this.$auth.logout('local').then(() => {
          this.$notify.info({message: 'Goodbye!'});
        })
      },
    },
    created() {
      this.$fa.add(faSignInAlt, faSignOutAlt)
    }
  }
</script>

<style lang="scss">
  .navbar {
    .logout {
      a {
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
    }
  }
</style>
