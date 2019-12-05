<template>
  <modal-delete v-model="record" :url="url" title="Warning" submit-variant="danger">
    <template slot="fields">
      <b-form-group>
        <p>Are you sure you want to remove user group <strong>{{record.title}}</strong>?</p>
        <p>This can not be undone.</p>
      </b-form-group>
    </template>
  </modal-delete>
</template>

<script>
  export default {
    data() {
      return {
        url: 'admin/user-roles',
        record: {},
      }
    },
    async asyncData({app, params}) {
      try {
        const {data: record} = await app.$axios.get('admin/user-roles', {params: params});
        return {record}
      } catch (e) {
        error({statusCode: e.statusCode, message: e.data})
      }
    },
  }
</script>
