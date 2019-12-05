<template>
  <modal-update v-model="record" :url="url" :title="record.fullname">
    <template slot="fields">
      <b-form-group label="Username">
        <b-form-input v-model.trim="record.username" autofocus required/>
      </b-form-group>

      <b-form-group label="Password">
        <b-input-group>
          <b-form-input v-model.trim="record.password"/>
          <b-input-group-append>
            <b-button @click.prevent="record.password = $password(8)" tabindex="-1">
              <fa :icon="['fas', 'key']"/>
            </b-button>
          </b-input-group-append>
        </b-input-group>
      </b-form-group>

      <b-form-group label="Fullname">
        <b-form-input v-model.trim="record.fullname" required/>
      </b-form-group>

      <b-form-group label="User group">
        <b-form-select v-model="record.role_id" :options="roles" value-field="id" text-field="title" required/>
      </b-form-group>

      <b-form-group>
        <b-form-checkbox v-model.number="record.active">Active</b-form-checkbox>
      </b-form-group>
    </template>
  </modal-update>
</template>

<script>
  import {faKey} from '@fortawesome/free-solid-svg-icons'

  export default {
    data() {
      return {
        url: 'admin/users',
        roles: [],
        record: {},
      }
    },
    async asyncData({app, params}) {
      try {
        let [{data: roles}, {data: record}] = await Promise.all([
          app.$axios.get('admin/user-roles', {params: {limit: 0}}),
          app.$axios.get('admin/users', {params: params})
        ]);
        record.password = null; // For vue watcher

        return {
          record,
          roles: roles.rows,
        }
      } catch (e) {
        error({statusCode: e.statusCode, message: e.data})
      }
    },
    created() {
      this.$fa.add(faKey)
    }
  }
</script>
