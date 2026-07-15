<style>
  #missing-fields-modal { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; z-index: 9999; }
  #missing-fields-modal .backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.5); }
  #missing-fields-modal .panel { position: relative; background: white; border-radius: 8px; padding: 20px; max-width: 720px; width: 90%; box-shadow: 0 10px 30px rgba(2,6,23,0.4); }
  #missing-fields-modal .panel h3 { margin-top: 0; }
  #missing-fields-modal .panel ul { margin: 10px 0 16px 18px; }
  #missing-fields-modal .panel .actions { text-align: right; }
  #missing-fields-modal .panel .btn { margin-left: 8px; }
</style>

<div id="missing-fields-modal" aria-hidden="true">
  <div class="backdrop" role="presentation"></div>
  <div class="panel" role="dialog" aria-modal="true" aria-labelledby="missing-fields-title">
    <h3 id="missing-fields-title">Campos faltando</h3>
    <div id="missing-fields-body"></div>
    <div class="actions">
      <button type="button" class="btn btn-secondary" id="missing-fields-cancel">Fechar</button>
      <a href="#" id="missing-fields-edit" class="btn btn-primary">Ir para edição</a>
    </div>
  </div>
</div>

<script>
  function showMissingFieldsModal(payload) {
    const modal = document.getElementById('missing-fields-modal');
    const body = document.getElementById('missing-fields-body');
    const edit = document.getElementById('missing-fields-edit');
    const cancel = document.getElementById('missing-fields-cancel');

    const missing = payload.missing || [];
    const situacao = payload.situacao || '';

    let html = '<p>O cadastro foi salvo como <strong>' + (situacao || 'não informado') + '</strong> e os seguintes campos estão vazios:</p>';
    html += '<ul>' + missing.map(m => '<li>' + m + '</li>').join('') + '</ul>';
    html += '<p>Complete-os para finalizar o cadastro.</p>';

    body.innerHTML = html;

    edit.setAttribute('href', window.location.pathname);

    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');

    cancel.onclick = function () { modal.style.display = 'none'; };
    modal.querySelector('.backdrop').onclick = function () { modal.style.display = 'none'; };
  }

  document.addEventListener('show-missing-fields', function (e) {
    showMissingFieldsModal(e.detail || {});
  });

  // If server placed payload in a global variable, open immediately
  try {
    if (window.__ACOLHIDO_MISSING_FIELDS) {
      showMissingFieldsModal(window.__ACOLHIDO_MISSING_FIELDS);
    }
  } catch (err) {}
</script>
