Echo
    .channel('hello')
    .listen('.newEventName', (e) => {
      alert(e.whatHappens);
    });

Echo
    .private('task.1')
    .listen('event', (data) => {
        console.log(data);
    });

Echo
  .private('App.User' + userId)
  .notification((notification) => {
    alert(notification.type + ': ' + notification.subject);
  })