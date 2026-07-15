<script>
document.addEventListener('click', function (e) {
    const a = e.target.closest('a[href*="/reminder/"][href*="/ack"]');
    if (! a) {
        return;
    }

    e.preventDefault();

    const url = a.getAttribute('href');
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const token = tokenMeta ? tokenMeta.getAttribute('content') : null;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    }).then(function (resp) {
        if (! resp.ok) {
            throw resp;
        }
        return resp.json();
    }).then(function (json) {
        try {
            if (window.filament && typeof filament !== 'undefined' && filament.notify) {
                filament.notify({
                    title: 'Lembrete marcado',
                    body: 'Lembrete marcado como avisado.',
                    type: 'success'
                });
            } else {
                // fallback
                alert('Lembrete marcado como avisado.');
            }
        } catch (err) {
            // ignore
        }

        // optionally remove notification element from DOM
        const notif = a.closest('[data-notification-id]');
        if (notif) {
            notif.remove();
        }
    }).catch(function () {
        alert('Erro ao marcar lembrete. Tente novamente.');
    });
});
</script>
