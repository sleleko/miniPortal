<template>
  <b-row no-gutters class="justify-content-center justify-content-md-start mt-5">
    <b-pagination
        v-if="totalRows > limit"
        variant="dark"
        class="m-0"
        :total-rows="totalRows"
        :per-page="limit"
        :limit="pageLimit"
        v-model="currentPage"/>

    <b-button class="ml-2 d-none d-md-block" @click.prevent="refresh" variant="dark">
      <b-spinner small v-if="busy"/>
      <fa :icon="['fas', 'sync']" v-else/>
    </b-button>

    <div class="btn">
      <b>{{totalRows | number}}</b> rows
    </div>
  </b-row>
</template>

<script>
  import {faSync} from '@fortawesome/free-solid-svg-icons'

  export default {
    name: 'table-footer',
    methods: {
      refresh() {
        this.$root.$emit('bv::refresh::table', this.table)
      }
    },
    computed: {
      currentPage: {
        get() {
          return this.page
        },
        set(newValue) {
          this.$emit('update:page', newValue);
        }
      }
    },
    props: {
      table: {
        type: String,
        required: true,
      },
      page: {
        type: Number,
        required: true,
      },
      limit: {
        type: Number,
        required: true,
      },
      totalRows: {
        type: Number,
        required: true,
      },
      totalCost: {
        type: Number,
        required: false,
        default: null,
      },
      pageLimit: {
        type: Number,
        default: 7,
      },
      busy: {
        type: Boolean,
        required: false,
        default: false,
      },
    },
    created() {
      this.$fa.add(faSync)
    }
  }
</script>
