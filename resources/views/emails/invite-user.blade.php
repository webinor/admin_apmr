<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Définir votre mot de passe</title>
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <style>
        :root {
            color-scheme: light dark;
            supported-color-schemes: light dark;
        }

        /* Mode clair */
        body {
            background-color: #f4f6f8;
            color: #333333;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .container {
            background-color: #ffffff;
            color: #333333;
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
        }
        .highlight {
            background-color: #f0f7ff;
            border-left: 4px solid #007bff;
        }
        .danger {
            color: #e50914;
        }

        /* Mode sombre */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #121212 !important;
                color: #dddddd !important;
            }
            .container {
                background-color: #1e1e1e !important;
                color: #e5e5e5 !important;
            }
            .header {
                background-color: #0056b3 !important;
                color: #ffffff !important;
            }
            .highlight {
                background-color: #002244 !important;
                border-left: 4px solid #007bff !important;
            }
            .danger {
                color: #ff3b3b !important;
            }
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
    </style>
</head>

<body style="margin:0; padding:0;">
    <table align="center" cellpadding="0" cellspacing="0" width="100%"
        style="max-width:600px; border-radius:10px; overflow:hidden;
        box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-top:30px;"
        class="container">
        
        <tr>
            <td align="center" class="header" style="padding:20px;">
                <h1 style="margin:0; font-size:24px;">👋🏾 Bienvenue sur la plateforme CAS</h1>
            </td>
        </tr>

        <tr>
            <td style="padding:30px;">
                <p style="font-size:16px; margin-bottom:10px;">
                    Bonjour <strong style="color:#007bff;">{{ $name }}</strong>,
                </p>

                <p style="font-size:15px; line-height:1.6;">
                    Votre compte a été créé sur la plateforme
                    <strong>Cameroun Assistance Sanitaire</strong> afin de vous permettre de
                    <strong>signer les fiches PMR de manière digitale</strong>.
                </p>

                <div class="highlight" style="padding:15px; border-radius:6px; margin:20px 0;">
                    <p style="margin:0; font-weight:600;">
                        🔐 Merci de cliquer sur le bouton ci-dessous pour définir votre mot de passe, nécessaire à la signature électronique des fiches.
                    </p>
                </div>

                <p style="text-align:center;">
                    <a href="{{ $link }}" class="button">Définir mon mot de passe</a>
                </p>

               {{--  <p style="font-size:14px; margin-top:30px; color:#888;">
                    Si vous n’êtes pas à l’origine de cette demande, ignorez simplement ce message.
                </p> --}}

                <p style="font-size:14px; margin-top:20px; color:#888;">
                    Cordialement,<br>
                    <span style="color:#007bff; font-weight:600;">{{-- L’équipe sécurité  --}}Cameroun Assistance Sanitaire</span> {{-- 💙 --}}
                </p>
            </td>
        </tr>

        <tr>
            <td align="center" style="background-color:#f9f9f9; padding:15px;">
                <p style="font-size:12px; color:#999; margin:0;">
                    &copy; {{ $year }} Cameroun Assistance Sanitaire — Tous droits réservés.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
