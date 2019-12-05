<template>
  <div>
    <b-row no-gutters class="justify-content-center justify-content-md-between">
      <slot name="actions"/>

      <slot name="search">
        <b-input-group class="mt-2 mt-md-0 ml-md-auto col-md-4" v-if="filters.query !== undefined">
          <b-form-input v-model="filters.query" placeholder="Search..." variant="dark"/>
          <b-input-group-append>
            <b-button :disabled="!filters.query" @click.prevent="filters.query = ''" variant="dark">
              <fa :icon="['fas', 'times']"/>
            </b-button>
          </b-input-group-append>
        </b-input-group>
      </slot>
    </b-row>
  </div>
</template>

<script>
  import {faTimes} from '@fortawesome/free-solid-svg-icons'

  export default {
    name: 'table-filter',
    props: {
      filters: {
        type: Object,
        required: false,
        default() {
          return {}
        }
      },
      table: {
        type: String,
        required: true,
      },
    },
    data() {
      return {
        showFilters: this.visible === true,
      }
    },
    watch: {
      filters: {
        deep: true,
        handler() {
          this.$root.$emit('app::' + this.table + '::update', this.filters);
        },
      }
    },
    created() {
      this.$fa.add(faTimes);
    }
  }
</script>
