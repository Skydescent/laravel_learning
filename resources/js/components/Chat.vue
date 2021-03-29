<template>
  <div class="chat-popup">
      <h1>Чат</h1>

      <div class="chat-messages">
          <p v-for="message in messages">{{ message }}</p>
      </div>
      <input name="message" class="form-control" v-model="message" @keydown="whisperTyping" placeholder="Сообщениею...">
      <button class="btn btn-primary" @click.prevent="sendMessage">Отправить</button>
  </div>
</template>

<style>
    .chat-popup {
        display: block;
        position: fixed;
        bottom: 15px;
        right: 15px;
        width: 300px;
        height: 330px;
        padding: 10px;
        background-color: #ffffff;
        border: 3px solid #f1f1f1;
        z-index: 10;
    }

    .chat-popup .chat-messages {
        height: 150px;
        margin-bottom: 10px;
        overflow-y: scroll;
    }

    .chat-popup input {
        margin-bottom: 10px;
    }

</style>

<script>
    export default {

        data() {
            return {
                messages: [],
                message: '',
                channel: null, //добавим объект канала
            }
        },

        mounted() {
            this.channel = Echo.join('chat');
            this.channel
                .join('chat')
                .listen('ChatMessage', (data) => {
                    this.addMessage(data.user.name + ': ' + data.message);

                })
                .here((users) => {
                    this.addMessage('В чате ' + users.length + ' участников');
                })
                .joining((user) => {
                    this.addMessage('пользователь ' + user.name + ' подключился к чату');
                })
                .leaving((user) => {
                    this.addMessage('пользователь ' + user.name + ' покинул чат');
                })
            ;

            this.channel
                .listenForWhisper('typing', (data) => {
                    this.addMessage('Печатает: ' + data.name);
                })
            ;
        },
        methods: {
            sendMessage() {
                if (this.message.length > 0) {

                    let message = this.message;
                    this.message ='';
                    this.addMessage('Я: ' + message);

                    axios
                        .post('/chat', {message: message})
                        .then(() => {})
                }
            },
            addMessage(message) {
              this.messages.push(message);
            },
            whisperTyping() {
                this.channel.whisper('typing', {name: 'другой участник'}); //первый параметр - канал, второй json
            }
        }
    }
</script>
