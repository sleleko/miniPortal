<template>
  <div>
    <slot name="filters">
      <table-filter :filters="filters" :table="name" :visible="filtersVisible">
        <slot v-for="(_, name) in $slots" :name="name" :slot="name"/>
      </table-filter>
    </slot>

    <b-table
        :busy="busy"
        :stacked="tableStacked"
        :class="tableClass"
        :id="name"
        :items="items"
        :fields="fields"
        :current-page="page"
        :per-page="tLimit"
        :sort-by.sync="tSort"
        :sort-direction.sync="tDir"
        :sort-desc="dir === 'desc'"
        :tbody-tr-class="rowClass"
        :primary-key="primaryKey"
        show-empty
        no-sort-reset
        no-local-sorting>
      <template v-for="(_, name) in $scopedSlots" :slot="name" slot-scope="slotData">
        <slot :name="name" v-bind="slotData"/>
      </template>
    </b-table>

    <slot name="footer">
      <table-footer
          :busy="busy"
          :table="name"
          :total-rows="totalRows"
          :total-cost="totalCost"
          :page.sync="page"
          :limit.sync="limit"/>
    </slot>
  </div>
</template>

<script>
  import TableFilter from './table-filter'
  import TableFooter from './table-footer'

  export default {
    name: 'app-table',
    data() {
      return {
        page: 1,
        busy: false,
        totalRows: 0,
        totalCost: null,
        pageLimit: 7,
        items: (ctx) => {
          return this.loadTable(ctx, this, this.url);
        }
      }
    },
    components: {
      TableFilter,
      TableFooter
    },
    computed: {
      tSort: {
        get() {
          return this.sort
        },
        set(newValue) {
          this.$emit('update:sort', newValue)
        }
      },
      tDir: {
        get() {
          return this.dir
        },
        set(newValue) {
          this.$emit('update:dir', newValue)
        }
      },
      tLimit: {
        get() {
          return this.limit
        },
        set(newValue) {
          this.$emit('update:limit', newValue)
        }
      }
    },
    props: {
      fields: {
        type: Array,
        required: false,
        default() {
          return []
        }
      },
      url: {
        type: String,
        required: true,
      },
      name: {
        type: String,
        required: false,
        default() {
          return this.url.replace('/', '-')
        }
      },
      limit: {
        type: Number,
        required: false,
        default: 20,
      },
      sort: {
        type: String,
        required: false,
        default: 'id',
      },
      dir: {
        type: String,
        required: false,
        default: 'asc',
      },
      filters: {
        type: Object,
        required: false,
        default() {
          return {
            //query: '',
          }
        }
      },
      filtersVisible: {
        type: Boolean,
        default: false,
      },
      primaryKey: {
        type: String,
        default: 'id',
      },
      rowClass: {
        type: Function,
        required: false,
        default() {
        }
      },
      tableClass: {
        default: 'mt-5',
      },
      tableStacked: {
        default: 'md',
      }
    },
    methods: {
      refresh() {
        this.$root.$emit('bv::refresh::table', this.name)
      },
    },
    created() {
      this.$root.$on('app::' + this.name + '::update', () => {
        this.refresh();
      });
    }
  }
</script>
