<template>
    <div v-if="hasUpdate" role="alert" aria-live="assertive" aria-atomic="true" class="popup toast show" data-autohide="false">
        <div class="toast-header">
            <strong class="mr-auto">Статья была обновлена</strong>
            <button  @click.prevent="close()" type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="toast-body">
            <strong>Название статьи:</strong>
            <br>
            <em><a v-bind:href="postUrl">{{title}}</a></em>
            <br>
            <strong>Поля, которые изменились:</strong>
            <br>
            <em>{{fields}}</em>
            <br>

        </div>
    </div>
</template>

<style>
.popup {
    display: block;
    position: fixed;
    bottom: 15px;
    right: 15px;
    width: 350px;
    height: 170px;
    padding: 10px;
    background-color: #ffffff;
    z-index: 10;
}
</style>

<script>
export default {

    data() {
        return {
            hasUpdate: false,
            title: '',
            fields: '',
            postUrl: '',
            channel: null,
            data: null
        }
    },

    mounted() {
        this.channel = Echo.join('post_updated');
        this.channel
            .listen('PostUpdated', (data) => {
                this.title = data.post.title;
                this.fields = data.updatedFields;
                this.postUrl = data.postUrl;
                this.hasUpdate = true;
            });
    },
    methods: {
        close() {
            this.hasUpdate = false;
        }
    }
}
</script>
