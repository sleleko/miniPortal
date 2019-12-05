<template>
  <modal-delete v-model="record" :url="url" title="Warning" submit-variant="danger">
    <template slot="fields">
      <b-form-group>
        <p>Are you sure you want to remove user <strong>{{record.fullname}}</strong>?</p>
        <p>This can not be undone.</p>
      </b-form-group>
    </template>
  </modal-delete>
</template>

<script>
  export default {
    data() {
      return {
        url: 'admin/users',
        record: {},
      }
    },
    async asyncData({app, params}) {
      try {
        const {data: record} = await app.$axios.get('admin/users', {params: params});
        return {record}
      } catch (e) {
        error({statusCode: e.statusCode, message: e.data})
      }
    },
  }
</script>
