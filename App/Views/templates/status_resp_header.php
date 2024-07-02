<!-- Usado para /resp/$1 (PWA responsável), precisa ser separado pois não pode incluir header de modal.-->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="theme-color" content="#ffffff" id="theme-color">
    <!-- Remove pinch to zoom: -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="<?= esc(base_url('/normalize.css'))?>">
    <link rel="stylesheet" href="<?= esc(base_url('/style.css'))?>">
    <!-- <script src=<?= esc(base_url('/admin.js'))?> defer ></script> -->
    <link rel="manifest" href="/manifest.json">
    <script>// JavaScript to switch theme color based on prefers-color-scheme
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            const themeColorMeta = document.querySelector('meta[name="theme-color"]');
            if (event.matches) {
                // Dark mode
                themeColorMeta.setAttribute('content', '#000000'); // Set your dark theme color here
            } else {
                // Light mode
                themeColorMeta.setAttribute('content', '#ffffff'); // Set your light theme color here
            }
        });

        // Initial check
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.querySelector('meta[name="theme-color"]').setAttribute('content', '#000000'); // Dark theme color
        } else {
            document.querySelector('meta[name="theme-color"]').setAttribute('content', '#ffffff'); // Light theme color
        }

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(function (registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function (error) {
                    console.log('Service Worker registration failed:', error);
                });
        }
    </script>
</head>
<body class="pwa-resp">
    