importScripts('https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js');



// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyB5GLBynJyYoU6hecFfI0KluYyB3HOBH3s",
    authDomain: "push-notification-40b22.firebaseapp.com",
    projectId: "push-notification-40b22",
    storageBucket: "push-notification-40b22.appspot.com",
    messagingSenderId: "17594588280",
    appId: "1:17594588280:web:44aa8258c1bbcaa040a18d"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firebase Messaging
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('Received background message ', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/firebase-logo.png' // Укажите путь к вашему иконке
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
