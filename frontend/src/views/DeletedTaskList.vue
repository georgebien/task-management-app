<template>
  <div class="container my-5">
    <div class="text-left">
      <h1 class="mb-4">Deleted task</h1>
      <p class="lead text-muted">Manage your tasks efficiently and stay organized.</p>
    </div>

    <!-- You can add more components or UI blocks inside this container -->
  </div>
</template>

<script>
export default {
  name: 'DeletedTaskList',

  data() {
    return {
      tasks: [],
    };
  },

  created() {
    this.fetchTasks();
  },

  methods: {
    fetchTasks() {
      // Simulate fetching tasks from an API
      this.tasks = [
        { id: 1, title: 'Task 1', completed: false },
        { id: 2, title: 'Task 2', completed: true },
      ];
    },
  },
}
</script>

<style>

</style>