<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Serviço indisponível</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;background:#f7fafc;color:#2d3748;display:flex;align-items:center;justify-content:center;height:100vh;margin:0} .card{max-width:720px;padding:28px;border-radius:12px;background:#fff;box-shadow:0 6px 18px rgba(15,23,42,0.06)} h1{margin:0 0 12px;font-size:20px} p{margin:0 0 8px;color:#4a5568}</style>
</head>
<body>
<div class="card">
    <h1>Serviço temporariamente indisponível</h1>
    <p>Não foi possível estabelecer conexão com o banco de dados. Por favor verifique se o serviço de banco está ativo e as credenciais no arquivo .env.</p>
    <p>Se você for o administrador, execute:</p>
    <pre>sudo systemctl start mysql
# ou
sudo service mysql start</pre>
    <p>Depois disso, atualize a página.</p>
</div>
</body>
</html>
