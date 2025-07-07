<template>
  <div v-if="visible">
    <div class="modal-backdrop fade show" />
    <div
      class="modal fade show"
      style="display: block;"
      tabindex="-1"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ config.title }}
            </h5>
          </div>
          <div class="modal-body">
            <p>{{ config.message }}</p>
          </div>
          <div class="modal-footer">
            <button
              class="btn btn-secondary"
              @click="cancel"
            >
              Cancel
            </button>
            <button
              class="btn btn-danger"
              @click="confirm"
            >
              Confirm
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      visible: false,
      config: {
        title: '',
        message: '',
      },
    };
  },
  methods: {
    open(config) {
      this.config = config;
      this.visible = true;

      return new Promise((resolve) => {
        this.resolvePromise = resolve;
      });
    },
    confirm() {
      this.visible = false;
      this.resolvePromise(true);
    },
    cancel() {
      this.visible = false;
      this.resolvePromise(false);
    },
  },
};
</script>
