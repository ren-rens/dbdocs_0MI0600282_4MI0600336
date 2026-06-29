<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Generate structured HTML documentation from your database schema JSON.">
    <title>Database Documentation Generator - Authentication</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-body">
    <main class="app-wrapper">
        <header class="app-header">
            <img src="images/db-symbol.png" alt="" role="presentation" class="db-symbol">
            <h1>Database Documentation Generator</h1>
        </header>

        <section id="loginSection" class="content-section" aria-labelledby="loginHeading">
            <h2 id="loginHeading">Sign In</h2>
            <div id="loginMessage" class="ui-message hidden" role="alert" aria-live="assertive"></div>
            <form novalidate>
                <div class="form-group">
                    <label for="loginEmail">Email Address</label>
                    <input type="email" id="loginEmail" placeholder="you@example.com" autocomplete="email" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" placeholder="********" autocomplete="current-password" required>
                </div>
                <div class="auth-actions">
                    <button id="loginBtn" class="btn-primary" type="button">Login</button>
                    <button id="toRegisterBtn" class="btn-link" type="button">Sign up</button>
                </div>
            </form>
        </section>

        <section id="registerSection" class="content-section hidden" aria-labelledby="registerHeading">
            <h2 id="registerHeading">Register</h2>
            <div id="registerMessage" class="ui-message hidden" role="alert" aria-live="assertive"></div>
            <form novalidate>
                <div class="form-group">
                    <label for="registerEmail">Email Address</label>
                    <input type="email" id="registerEmail" placeholder="you@example.com" autocomplete="email" required>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Password</label>
                    <input type="password" id="registerPassword" placeholder="********" autocomplete="new-password" required>
                </div>
                <div class="auth-actions">
                    <button id="registerBtn" class="btn-primary" type="button">Register</button>
                    <button id="toLoginBtn" class="btn-link" type="button">Back to Sign In</button>
                </div>
            </form>
        </section>
    </main>
    <script src="js/auth.js"></script>
</body>
</html>