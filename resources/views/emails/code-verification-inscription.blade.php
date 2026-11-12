
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification GEST-HOTEL</title>
</head>
<body style="margin:0; padding:30px; background:#f1f5f9; font-family:Arial,sans-serif;">

    <div style="max-width:550px; margin:auto; padding:35px; background:#ffffff; border-radius:12px;">

        <h1 style="color:#2457A7; text-align:center;">
            GEST-HOTEL
        </h1>

        <h2>Vérification de votre adresse e-mail</h2>

        <p>Bonjour {{ $nomComplet }},</p>

        <p>
            Merci de votre inscription sur GEST-HOTEL.
            Pour confirmer votre adresse e-mail, utilisez le code suivant :
        </p>

        <div style="margin:30px 0; padding:20px; background:#eff6ff; text-align:center; border-radius:8px;">
            <strong style="font-size:32px; letter-spacing:8px; color:#2457A7;">
                {{ $code }}
            </strong>
        </div>

        <p>
            Ce code est valable pendant 10 minutes.
            Ne le partagez avec personne.
        </p>

        <p>
            Si vous n'êtes pas à l'origine de cette inscription,
            vous pouvez ignorer ce message.
        </p>

        <p style="margin-top:30px; color:#64748b;">
            L'équipe GEST-HOTEL
        </p>

    </div>

</body>
</html>
