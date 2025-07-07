<template>
  <div>
    <div v-if="isModalVisible"  class="modal-backdrop fade show" />
    <div
      v-if="isModalVisible"
      class="modal fade show"
      style="display: block;"
      tabindex="-1"
      role="dialog"
    >
      <div
        class="modal-dialog"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{config.title}}
            </h5>
            <button
              type="button"
              class="btn-close"
              @click="hide"
            />
          </div>
          <div class="modal-body">
            <div class="form-group mb-2">
              <label>Title</label>
              <input v-model="title" type="text" class="form-control" placeholder="Enter title">
            </div>
            <div class="form-group mb-2">
              <label>Content</label>
              <textarea v-model="content" class="form-control" rows="3"></textarea>
            </div>
            <div v-show="config.isEdit" class="form-group mb-2">
              <label>Status</label>
              <Multiselect
                v-model="status"
                :options="statusOptions"
                :mode="'single'"
                placeholder="Select status"
              />
            </div>
            <div class="form-group mb-2">
              <label>Attachment</label>
              <div class="card">
                <input 
                  v-on:change="handleAttachment" 
                  type="file" 
                  class="form-control-file mt-2"
                  accept="image/jpeg, image/png, image/jpg"
                >
              </div>
            </div>
            <div class="form-check">
              <input v-model="isDraft" class="form-check-input" type="checkbox" value="" id="isDraft">
              <label class="form-check-label" for="isDraft">
                Save as draft
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="hide"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="save"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from '@vueform/multiselect';
import { useToast } from 'vue-toast-notification';
import { create, update } from '@/services/taskService';
import { isNull } from 'lodash-es';

const $toast = useToast();

export default {
  components: {
    Multiselect
  },

  props: {
    config: {
      type: Object,
      required: true
    },
    statusOptions: {
      type:Array,
      required: true
    }
  },

  data() {
    return {
      isModalVisible: false,
      id: null,
      title: null,
      content: null,
      status: null,
      image: null,
      isDraft: false
    }
  },

  methods:{
    show() {
      this.isModalVisible = true;
    },

    hide() {
      this.isModalVisible = false;
    },

    handleAttachment(event) {
      const file = event.target.files[0];

      if (!file) {
        return;
      }

      const allowedTypes = [
        'image/jpeg', 
        'image/png', 
        'image/jpg'
      ];

      if (!allowedTypes.includes(file.type)) {
        $toast.error(
          'Invalid file type. Please select a JPG, JPEG, or PNG file.', {
          position: 'top'
        });
        
        event.target.value = '';
        return;
      }

      this.image = file;
    },

    async save() {
      const action = this.config.isEdit
        ? () => update(this.buildFormData(), this.id)
        : () => create(this.buildFormData());
        
      const { status, data} = await action();

      if (status !== 200) {
        $toast.error(data.message, {position: 'top'});
        return;
      }

      $toast.success(data.message, {position: 'top'});
      
      this.hide();
      this.$emit('refreshList');
    },

    buildFormData() {
      const formData = new FormData();

      formData.append('title', this.title);
      formData.append(
        'is_draft', 
        this.isDraft
          ? 1
          : 0
      );
      
      if (!isNull(this.content)) {
        formData.append('content', this.content);
      }

      if (!isNull(this.image)) {
        formData.append('image', this.image);
      }

      if (this.config.isEdit) {
        formData.append('status', this.status);
        formData.append('_method','PUT');
      }

      return formData;
    }
  }
}
</script>

<style>

</style>