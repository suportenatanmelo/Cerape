@php
    $browserTitle = config('app.name', 'CERAPE');
@endphp

<script>
    (() => {
        const baseTitle = @json($browserTitle);

        const updateTitle = () => {
            const unread = Number(window.__cerapeUnreadNotifications ?? 0);
            document.title = unread > 0 ? `(${unread}) ${baseTitle}` : baseTitle;
        };

        updateTitle();

        document.addEventListener('visibilitychange', updateTitle);

        window.addEventListener('cerape-browser-notification-count', (event) => {
            window.__cerapeUnreadNotifications = event?.detail?.count ?? 0;
            updateTitle();
        });
    })();
</script>
