<template>
    <div v-if="reportReceived" role="alert" aria-live="assertive" aria-atomic="true" class="popup toast show" data-autohide="false">
        <div class="toast-header">
            <strong class="mr-auto">Получен отчёт:</strong>
            <button  @click.prevent="close()" type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="toast-body">
            <ul id="example-1">
                <li v-for="item in report" >
                    {{ item.title + ' ' + item.value }}
                </li>
            </ul>
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
    props: ['userId', 'acceptedUrl'],
    data() {
        return {
            reportReceived: false,
            report: null,
        }
    },

    mounted() {
        if (window.location.href.includes(this.acceptedUrl)) {
            Echo
                .private(`user.${this.userId}`)
                .listen('ReportGenerated', (data) => {
                    this.report = data.report;
                    this.reportReceived = true;
                });
        }
    },
    methods: {
        close() {
            this.reportReceived = false;
        }
    }
}
</script>
