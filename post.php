<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POST</title>
    <link rel="stylesheet" href="css/uikit.css">
    <link rel="stylesheet" href="css/sticky.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</head>

<body>

    <body>
        <?php include_once("nav.php") ?>

        <form>
            <fieldset class="uk-fieldset">

                <legend class="uk-legend">Ajouter votre media</legend>

                <div class="uk-margin">
                    <textarea class="uk-textarea" style="resize: none;" rows="5" placeholder="Un petit récit "></textarea>
                </div>


                <div class="uk-margin" uk-margin>

                    <div uk-form-custom="target: true">
                        <input type="file">
                        <input class="uk-input uk-form-width-medium" type="text" placeholder="Votre Média" disabled>
                    </div>
                    <button class="uk-button uk-button-default">Ajouter</button>
                </div>

            </fieldset>
        </form>

    </body>
</body>

<script src="https://kit.fontawesome.com/03529e1b19.js" crossorigin="anonymous"></script>


</html>