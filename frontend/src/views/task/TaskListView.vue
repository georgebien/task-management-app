<template>
  <div class="container-fluid my-5">
    <div class="text-left">
      <h1 class="mb-4">
        Tasks
      </h1>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <Multiselect
            v-model="filters.statuses"
            :options="statusOptions"
            :mode="'tags'"
            placeholder="Filter by status"
          />
        </div>

        <vue-good-table
          style="width: 100%;"
          mode="remote"
          :max-height="'50vh'"
          :total-rows="tasks.total"
          :columns="columns"
          :rows="tasks.data"
          :is-loading="isLoading"
          :search-options="{
            enabled: true,
            placeholder: 'Search title...',
          }"
          :pagination-options="{
            enabled: true,
            mode: 'records',
          }"
          theme="polar-bear"
          @search="handleSearch"
          @page-change="handlePageChange"
          @per-page-change="handlePerPageChange"
          @sort-change="handleSort"
        >
          <template #table-actions>
            <button
              class="btn btn-primary"
              @click="handleNewTask"
            >
              New Task
            </button>
          </template>
          <template #table-row="props">
            <span v-if="props.column.field == 'actions'">
              <button class="btn btn-sm btn-primary me-2" @click.stop="handleEditTask(props.row)">Edit</button>
              <button class="btn btn-sm btn-danger" @click.stop="handleDeleteTask(props.row)">Delete</button>
            </span>
            <span v-else>
              {{ props.formattedRow[props.column.field] }}
            </span>
          </template>
        </vue-good-table>
      </div>
    </div>

    <TaskModal 
      ref="taskModal"
      :config="taskModalConfig"
      :status-options="statusOptions"
      @refresh-list="getList"
    />
    <ConfirmationModal  ref="confirmModal"/>
  </div>
</template>

<script>
import { useToast } from 'vue-toast-notification';
import { VueGoodTable } from 'vue-good-table-next';
import Multiselect from '@vueform/multiselect';
import TaskModal from './modal/TaskModal.vue';
import ConfirmationModal from './modal/ConfirmationModal.vue';
import { getList } from '@/services/taskService';
import { debounce, isNull, isEmpty } from 'lodash-es';
import { statuses } from './constants/status';
import { remove } from '@/services/taskService';

const $toast = useToast();

export default {
  name: 'TaskListView',

  components: {
    VueGoodTable,
    Multiselect,
    TaskModal,
    ConfirmationModal
  },
  
  data() {
    return {
      columns: [
        {
          label: 'ID',
          field: 'id',
          sortable: false,
        },
        {
          label: 'Title',
          field: 'title',
          sortable: true,
          width: '20%'
        },
        {
          label: 'Content',
          field: 'content',
          sortable: false,
          width: '20%'
        },
        {
          label: 'Status',
          field: 'status',
          sortable: false,
          width: '10%'
        },
        {
          label: 'Created date',
          field: 'createdDate',
          sortable: true,
          width: '15%'
        },
        {
          label: 'Updated date',
          field: 'updatedDate',
          sortable: false,
          width: '15%'
        },
        {
          label: 'Actions',
          field: 'actions',
          sortable: false,
          width: '20%'
        },
      ],
      isLoading: false,
      tasks: {
        data: [],
        total: 0,
      },
       statusOptions: [
        { 
          value: 
          'TO_DO', 
          label: 'To do' 
        },
        { 
          value: 
          'IN_PROGRESS', 
          label: 'In progress' 
        },
        { 
          value: 'DONE', 
          label: 'Done' 
        },
      ],
      filters: {
        page: 1,
        perPage: 10,
        sortBy: null,
        sortDir: null,
        search: null,
        onlyDeleted: false,
        statuses: []
      },
      taskModalConfig: {
        title: null,
        data: null,
        isEdit: false
      }
    };
  },

  watch: {
    'filters.statuses': function(value) {
      this.getList();
    }
  },

  created() {
    this.getList();
  },

  methods: {
    getList: debounce(async function() {
      await this.fetchTasks();
    }, 300),

    async fetchTasks() {
      this.isLoading = true;

      const response = await getList(this.getFilters());

      if (!response) {
        $toast.error('An unexpected error occurred', {poisition: 'top'});
        return;
      }

      this.tasks.data = this.mapTasks(response.data);
      this.tasks.total = response.meta.total;

      this.isLoading = false;
    },

    mapTasks(tasks) {
      return tasks.map(task => ({
        id: task.id,
        title: task.title,
        content: !isNull(task.content)
          ? task.content
          : '-',
        status: task.status,
        createdDate: task.created_at,
        updatedDate: task.updated_at,
      }));
    },

    getFilters() {
      const filters = {
        page: this.filters.page,
        per_page: this.filters.perPage
      }

      if (!isNull(this.filters.sortBy)) {
        filters.order_by = this.filters.sortBy;
      }

      if (!isNull(this.filters.sortDir)) {
        filters.order_dir = this.filters.sortDir;
      }

      if (!isNull(this.filters.search)) {
        filters.search = this.filters.search;
      }

      if (!isEmpty(this.filters.statuses)) {
        filters.statuses = this.filters.statuses;
      }

      if (this.filters.onlyDeleted) {
        filters.only_deleted = this.filters.onlyDeleted;
      }

      return filters;
    },

    handleSearch(search) {
      this.filters.search = search.searchTerm != ''
        ? search.searchTerm
        : null;
      this.getList();
    },

    handleSort(sort) {
      const hasType = sort[0].type !== 'none';

      if (!hasType) {
        return;
      }

      const fields = {
        title: 'title',
        createdDate: 'created_at'
      }

      this.filters.sortBy = fields[sort[0].field];
      this.filters.sortDir = sort[0].type;

      this.getList();
    },
    
    handlePageChange(paging) {
      this.filters.page = paging.currentPage;
      this.filters.perPage = paging.currentPerPage;
      this.getList();
    },
    
    handlePerPageChange(paging) {
      this.filters.page = paging.currentPage;
      this.filters.perPage = paging.currentPerPage;
      this.getList();
    },

    handleNewTask() {
      this.taskModalConfig.title = 'New task';
      this.taskModalConfig.isEdit = false;

      this.$refs.taskModal.show();
    },

    handleEditTask(row) {
      this.taskModalConfig.title = 'Edit task';
      const modal = this.$refs.taskModal;

      this.taskModalConfig.isEdit = true;

      modal.id = row.id;
      modal.title = row.title;
      modal.content = row.content;
      modal.title = row.title;
      modal.status = statuses[row.status];
      modal.show();
    },

    async handleDeleteTask(row) {
      const confirmed = await this.$refs.confirmModal.open({
        title: 'Delete Task',
        message: 'Are you sure you want to delete this task?',
      });

      if (!confirmed) {
        return;
      }

      const { status, data} = await remove({ids: [row.id]});

      if (status !== 200) {
        $toast.error(data.message, {position: 'top'});
        return;
      }

      $toast.success(data.message, {position: 'top'});

      this.getList();
    },
  },
}
</script>