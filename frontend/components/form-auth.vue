<template>
  <b-form @submit.prevent="Login">
    <b-form-group id="input-username" label="Username:" label-for="input-username">
      <b-form-input
          :disabled="loading"
          id="input-username"
          v-model="form.username"
          required
          autofocus/>
    </b-form-group>

    <b-form-group id="input-password" label="Password:" label-for="input-password">
      <b-form-input
          :disabled="loading"
          id="input-password"
          v-model="form.password"
          type="password"
          required/>
    </b-form-group>

    <b-button type="submit" variant="primary">
      <b-spinner small v-if="loading"/>
      Submit
    </b-button>
  </b-form>
</template>

<script>
  export default {
    name: 'form-auth',
    data() {
      return {
        loading: false,
        form: {
          username: null,
          password: null,
        }
      }
    },
    methods: {
      async Login() {
        this.loading = true;
        try {
          await this.$auth.loginWith('local', {data: this.form});
          this.loading = false;
          this.$notify.success({message: 'Welcome!'});
        } catch (e) {
        } finally {
          this.loading = false
        }
      },
    }
  }
</script>
