<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
    </head>
    <body>
        <h1>Se connecter par email</h1>
        
        <form action="{{ url('/auth/login') }}" method="POST">
            @csrf
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Recevoir un lien Magic Link</button>
        </form>
    </body>
</html>
