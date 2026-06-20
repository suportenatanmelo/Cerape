<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CERAPE | Em breve</title>
        <meta name="description" content="Em breve, o site institucional da CERAPE.">
        <style>
            :root {
            --bg: #faf7f2;
            --bg-2: #f1ece2;
            --card: rgba(255, 255, 255, 0.84);
            --text: #2b2823;
            --muted: #6b6459;
            --accent: #1e3d36;
            --accent-2: #e08e4f;
            --border: #e2dbcb;
            }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            background:
                radial-gradient(circle at top, rgba(242, 196, 154, 0.25), transparent 35%),
                radial-gradient(circle at bottom right, rgba(107, 142, 120, 0.14), transparent 30%),
                linear-gradient(160deg, var(--bg), var(--bg-2));
            color: var(--text);
            font-family: Inter, Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .shell {
            width: min(100%, 920px);
            padding: 24px;
        }

        .card {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 28px;
            background: var(--card);
            backdrop-filter: blur(16px);
            box-shadow: 0 30px 80px rgba(30, 61, 54, 0.12);
            padding: clamp(32px, 6vw, 72px);
            text-align: center;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid rgba(224, 142, 79, 0.2);
            background: rgba(255, 255, 255, 0.72);
            color: var(--accent);
            font-size: 0.92rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .badge::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent-2);
            box-shadow: 0 0 18px rgba(224, 142, 79, 0.35);
        }

        h1 {
            margin: 24px 0 12px;
            font-size: clamp(2.4rem, 6vw, 5rem);
            line-height: 0.98;
            letter-spacing: -0.04em;
        }

        p {
            margin: 0;
            font-size: clamp(1.05rem, 2.2vw, 1.35rem);
            line-height: 1.7;
            color: var(--muted);
        }

        .site-name {
            display: block;
            margin-top: 8px;
            color: var(--accent);
            font-weight: 700;
        }

        .divider {
            width: 120px;
            height: 4px;
            margin: 28px auto;
            border-radius: 999px;
            background: linear-gradient(90deg, transparent, var(--accent), var(--accent-2), transparent);
        }

        .footer {
            margin-top: 18px;
            font-size: 0.95rem;
            color: var(--muted);
        }

        @media (max-width: 640px) {
            .shell {
                padding: 0;
            }

            .card {
                border-radius: 22px;
                padding: 28px 20px;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="card" aria-labelledby="title">
            <div class="badge">Página em construção</div>
            <h1 id="title">
                Em breve<br>
                <span class="site-name">Site Cerape.com</span>
            </h1>
            <div class="divider" aria-hidden="true"></div>
            <p>Centro de Reabilitação de preso e Egreso</p>
            <p class="footer">Um novo espaço digital está sendo preparado para acolher melhor o trabalho do CERAPE.</p>
        </section>
    </main>
</body>
</html>
