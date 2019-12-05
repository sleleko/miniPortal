<template>
  <div>
    <app-table :url="url" :fields="fields" :filters="filters" :sort="sort" :dir="dir">
      <template slot="actions">
        <nuxt-link :to="{name: 'admin-users-create'}" class="btn btn-dark">
          <fa :icon="['fas', 'plus']"/>
          Add User
        </nuxt-link>
      </template>

      <template v-slot:cell(username)="row">
        <nuxt-link :to="{name: 'admin-users-edit-id', params: {id: row.item.id}}">
          {{row.value}}
        </nuxt-link>
      </template>
      <template v-slot:cell(actions)="row">
        <nuxt-link :to="{name: 'admin-users-edit-id', params: {id: row.item.id}}" class="btn btn-sm btn-secondary">
          <fa :icon="['fas', 'edit']"/>
        </nuxt-link>
        <nuxt-link :to="{name: 'admin-users-delete-id', params: {id: row.item.id}}" class="btn btn-sm btn-danger">
          <fa :icon="['fas', 'times']"/>
        </nuxt-link>
      </template>
    </app-table>

    <nuxt-child/>
  </div>
</template>

<script>
  import {faPlus, faEdit, faTimes} from '@fortawesome/free-solid-svg-icons'

  export default {
    validate({app}) {
      return !app.$auth.loggedIn || app.$auth.hasScope('users')
    },
    data() {
      return {
        url: 'admin/users',
        fields: [
          {key: 'id', label: 'ID', sortable: true},
          {key: 'username', label: 'Username'},
          {key: 'fullname', label: 'Fullname'},
          {key: 'role.title', label: 'User Group'},
          {key: 'actions', label: 'Actions'},
        ],
        filters: {
          query: null,
        },
        sort: 'id',
        dir: 'asc',
      }
    },
    created() {
      this.$fa.add(faPlus, faEdit, faTimes)
    }
  }
</script>
