<template>
  <modal-update v-model="record" :url="url" :title="record.title">
    <template slot="fields">
      <b-form-group label="Title">
        <b-form-input v-model.trim="record.title" autofocus required/>
      </b-form-group>

      <b-form-group label="Scopes" description="Comma-separated list of permissions for this group">
        <b-form-input v-model.trim="record.scope"/>
      </b-form-group>
    </template>
  </modal-update>
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
        const {data: record} = await app.$axios.get('admin/user-roles', {params});
        record.scope = record.scope.join(', ',);
        return {record}
      } catch (e) {
        error({statusCode: e.statusCode, message: e.data})
      }
    },
  }
</script>
