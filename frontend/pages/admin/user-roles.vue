<template>
  <div>
    <app-table :url="url" :fields="fields" :filters="filters" :sort="sort" :dir="dir">
      <template slot="actions">
        <nuxt-link :to="{name: 'admin-user-roles-create'}" class="btn btn-dark">
          <fa :icon="['fas', 'plus']"/>
          Add User Group
        </nuxt-link>
      </template>

      <template v-slot:cell(title)="row">
        <nuxt-link :to="{name: 'admin-user-roles-edit-id', params: {id: row.item.id}}">
          {{row.value}}
        </nuxt-link>
      </template>
      <template v-slot:cell(actions)="row">
        <nuxt-link :to="{name: 'admin-user-roles-edit-id', params: {id: row.item.id}}" class="btn btn-sm btn-secondary">
          <fa :icon="['fas', 'edit']"/>
        </nuxt-link>
        <nuxt-link :to="{name: 'admin-user-roles-delete-id', params: {id: row.item.id}}" class="btn btn-sm btn-danger">
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
      return !app.$auth.loggedIn || app.$auth.hasScope('users') || app.$auth.hasScope('users/get')
    },
    data() {
      return {
        url: 'admin/user-roles',
        fields: [
          {key: 'id', label: 'ID', sortable: true},
          {key: 'title', label: 'Title'},
          {key: 'scope', label: 'Scopes', formatter: this.formatScopes},
          {key: 'users_count', label: 'Users'},
          {key: 'actions', label: 'Actions'},
        ],
        filters: {
          query: null,
        },
        sort: 'id',
        dir: 'asc',
      }
    },
    methods: {
      formatScopes: (val) => {
        return val.join(', ')
      }
    },
    created() {
      this.$fa.add(faPlus, faEdit, faTimes)
    }
  }
</script>
