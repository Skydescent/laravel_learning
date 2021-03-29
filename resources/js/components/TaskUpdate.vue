<template>
  <div>
    <div v-if="hasUpdate">
      Задача была обновлена <button @click.prevent="reload()" class="btn btn-danger">Обновить страницу</button>
    </div>
  </div>
</template>

<script>
    export default {

        props: ['taskId'],
        data() {
            return {
                hasUpdate: false
            }
        },

        mounted() {
            Echo
                .private('task' + this.taskId)
                .listen('TaskUpdated', (data) => {

                    // data.task.title - в формате json хранятся любые другие данные о задаче
                    this.hasUpdate = true;
                });
        },
        methods: {
            reload() {
                window.location.reload();
            }
        }
    }
</script>
