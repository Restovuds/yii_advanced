<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");


$csrfToken = Yii::$app->request->csrfToken;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>


<?php $this->endBody() ?>

<script type="module">
    // Импортируйте необходимые модули Firebase
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
    import { onMessage, getMessaging, getToken } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging.js";

    const firebaseConfig = {
        apiKey: "AIzaSyB5GLBynJyYoU6hecFfI0KluYyB3HOBH3s",
        authDomain: "push-notification-40b22.firebaseapp.com",
        projectId: "push-notification-40b22",
        storageBucket: "push-notification-40b22.appspot.com",
        messagingSenderId: "17594588280",
        appId: "1:17594588280:web:44aa8258c1bbcaa040a18d"
    };

    // Инициализация Firebase
    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);


    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then((registration) => {
                console.log('Service Worker registered with scope:', registration.scope);
            }).catch((error) => {
            console.error('Service Worker registration failed:', error);
        });
    }


    // Запрос разрешения на получение уведомлений
    async function requestPermission() {
        console.log('Requesting permission...');
        try {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                console.log('Notification permission granted.');
                const token = await getToken(messaging, {
                    vapidKey: 'BJPCB7fiBVSE8FthHxVUCS-GzxpMxa3fDfcaZ3n5qv145QgJm78rwqmUDo7mnokvAMYcqepzfuXhGWY3Tmp8k9I'
                });
                console.log('FCM Token:', token);
                sendTokenToServer(token);
            } else {
                console.log('Unable to get permission to notify.');
            }
        } catch (error) {
            console.error('Error getting permission:', error);
        }
    }

    // Функция отправки токена на сервер
    function sendTokenToServer(token) {
        fetch('/firebase/save-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ token: token }),
        })
            .then(response => response.json())
            .then(data => {
                console.log('Token sent to server:', data);
            })
            .catch((error) => {
                console.error('Error sending token to server:', error);
            });
    }

    // Вызовите requestPermission после авторизации
    requestPermission();

    const messaging1 = getMessaging();

    messaging1.onMessage((payload) => {
        console.log('Received message: ', payload);
        const notificationTitle = payload.notification.title;
        const notificationOptions = {
            body: payload.notification.body,
            icon: '/path/to/icon.png',
        };
        return self.registration.showNotification(notificationTitle, notificationOptions);
    });
</script>







</body>
</html>
<?php $this->endPage();


