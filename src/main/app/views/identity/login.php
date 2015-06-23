<!doctype html>
<html>
<head>
    <title>Inloggen bij de Hondsrug</title>

    <link type="text/css" rel="stylesheet" href="/css/vendor.css">
    <link type="text/css" rel="stylesheet" href="/css/bootstrap.min.css">

    <meta http-equiv="Content-Type" content="text/html; CHARSET=utf-8">
    <meta name="Robots" content="NOINDEX, NOFOLLOW">
</head>
<body class="logon">
    <form action="/login" method="post" autocomplete="off">
        <div class="holder">
            <div class="sidebar">
                <div class="image_header" role="heading">
                    <img src="/gfx/logo.png" alt="De Hondsrug" />
                </div>

                <div class="message_holder">
                    <?php if(isset($this->data['error'])) { ?>
                    <div class="message error">
                        <strong>Welkom bij de Hondsrug.</strong><br />
                        <?=$this->data['error'];?>
                    </div>
                    <?php } else { ?>
                    <div class="message info">
                        <strong>Welkom bij de Hondsrug.</strong><br />
                        Log in met uw Hondsrug account.
                    </div>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" class="form-control" id="username" name="username" />
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Wachtwoord</label>
                    <input type="password" class="form-control" id="password" name="password" />
                </div>

                <button type="submit" class="btn btn-primary">Inloggen</button>

                <footer>
                    <strong>Copyright &copy; SG de Hondsrug 2015</strong><br />
                </footer>
            </div>
        </div>
    </form>
</body>
</html>

