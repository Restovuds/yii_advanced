// firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');


// Инициализация Firebase
firebase.initializeApp({
    apiKey: "AIzaSyB5GLBynJyYoU6hecFfI0KluYyB3HOBH3s",
    authDomain: "push-notification-40b22.firebaseapp.com",
    databaseURL: 'https://push-notification-40b22.firebaseio.com',
    projectId: "push-notification-40b22",
    storageBucket: "push-notification-40b22.appspot.com",
    messagingSenderId: "17594588280",
    appId: "1:17594588280:web:44aa8258c1bbcaa040a18d"
});

const messaging = firebase.messaging();

// Обработка фоновых
messaging.onBackgroundMessage((payload) => {
    console.log('Received background message: ', payload);
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/path/to/icon.png',
    };
    return self.registration.showNotification(notificationTitle, notificationOptions);
});



