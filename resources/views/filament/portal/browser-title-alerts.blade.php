@auth
    <script>
        (() => {
            const statusUrl = @json(route('browser-alerts.status'));
            const systemTitle = 'SISTEMA CERAPE';
            const titleSuffix = 'CERAPE';
            const chatPrefix = @json(trim(config('chatify.routes.prefix', 'chatify'), '/'));
            const pollInterval = 10000;

            const cleanText = (value) => (value || '').replace(/\s+/g, ' ').trim();

            const isPanelDashboard = () => {
                const path = window.location.pathname.replace(/\/+$/, '') || '/';

                return path === '/admin' || path === '/frontend';
            };

            const isChatPage = () => {
                const path = window.location.pathname.replace(/^\/+/, '');

                return path === chatPrefix || path.startsWith(`${chatPrefix}/`);
            };

            const findModuleName = () => {
                if (isPanelDashboard()) {
                    return systemTitle;
                }

                if (isChatPage()) {
                    return 'Chat';
                }

                const selectors = [
                    '.fi-header-heading',
                    '.fi-page-heading',
                    'h1',
                    '.fi-sidebar-item-active .fi-sidebar-item-label',
                    '.fi-topbar-item-active .fi-topbar-item-label',
                    '[aria-current="page"]',
                ];

                for (const selector of selectors) {
                    const element = document.querySelector(selector);
                    const text = cleanText(element?.textContent);

                    if (text) {
                        return text;
                    }
                }

                const currentTitle = cleanText(document.title)
                    .replace(/^\(\d+\)\s*/, '')
                    .replace(/\s+[|—-]\s+CERAPE$/i, '')
                    .replace(/\s+[|—-]\s+SISTEMA CERAPE$/i, '');

                return currentTitle && currentTitle !== systemTitle ? currentTitle : systemTitle;
            };

            const defaultTitle = () => {
                const moduleName = findModuleName();

                return moduleName === systemTitle ? systemTitle : `${moduleName} | ${titleSuffix}`;
            };

            const applyDefaultTitle = () => {
                document.title = defaultTitle();
            };

            const applyAlertTitle = (payload) => {
                const chatUnread = Number(payload?.chat_unread || 0);
                const notificationUnread = Number(payload?.notification_unread || 0);
                const totalUnread = Number(payload?.total_unread || (chatUnread + notificationUnread));

                if (totalUnread <= 0) {
                    applyDefaultTitle();
                    return;
                }

                const label = chatUnread > 0 ? 'Nova mensagem' : 'Nova notificacao';
                document.title = `(${totalUnread}) ${label} | ${systemTitle}`;
            };

            const refreshTitle = async () => {
                try {
                    const response = await fetch(statusUrl, {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (! response.ok) {
                        applyDefaultTitle();
                        return;
                    }

                    applyAlertTitle(await response.json());
                } catch (error) {
                    applyDefaultTitle();
                }
            };

            const scheduleRefresh = () => window.setTimeout(refreshTitle, 150);

            applyDefaultTitle();
            document.addEventListener('DOMContentLoaded', refreshTitle);
            document.addEventListener('livewire:navigated', scheduleRefresh);
            document.addEventListener('visibilitychange', refreshTitle);
            window.addEventListener('focus', refreshTitle);
            window.setInterval(refreshTitle, pollInterval);
        })();
    </script>
@endauth
