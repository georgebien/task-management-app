<template>
  <div class="d-flex min-vh-100">
    <aside class="bg-light border-end p-3" style="width: 250px;">
      <h4 class="mb-4">Task Manager</h4>
      <nav>
        <ul class="nav flex-column">
          <li class="nav-item">
            <router-link class="nav-link" to="/tasks">Tasks</router-link>
            <router-link class="nav-link" to="/deleted-tasks">Deleted tasks</router-link>
          </li>
        </ul>
      </nav>
    </aside>

    <main class="flex-grow-1 p-4">
      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  name: 'Main',
}
</script>
