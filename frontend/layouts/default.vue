<template>
  <div id="app" class="d-flex flex-column">
    <app-header/>
    <b-container id="content" class="mt-5">
      <nuxt/>
    </b-container>
    <app-footer/>
  </div>
</template>

<script>
  import AppHeader from '../components/app-header'
  import AppFooter from '../components/app-footer'

  export default {
    components: {
      AppHeader,
      AppFooter
    }
  }
</script>
