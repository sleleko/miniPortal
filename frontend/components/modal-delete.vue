<script>
  import ModalCreate from './modal-create'

  export default {
    extends: ModalCreate,
    name: 'modal-delete',
    methods: {
      async Submit() {
        try {
          let record = JSON.parse(JSON.stringify(this.record));
          record = this.beforeSubmit(record);
          if (record === false) {
            return;
          }
          this.loading = true;
          const {data} = await this.$axios.delete(this.url, {params: record});
          this.$refs.modal.hide();
          this.$root.$emit(`app::${this.name}::update`, data)
        } catch (err) {
        } finally {
          this.loading = false
        }
      },
    },
  }
</script>
