<template>
  <div class="container">
    <h3 v-if="error.statusCode === 404">
      Error 404: Page not found
    </h3>
    <h3 v-else-if="error.statusCode === 401">
      Error 401: Authorization required
    </h3>
    <h3 v-else>Error {{error.statusCode}}: {{error.message}}</h3>
  </div>
</template>

<script>
  export default {
    props: ['error'],
  }
</script>
