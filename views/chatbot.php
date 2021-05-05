<!doctype html>
<html>

<head>
    <title>PetPro Connect</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?= plugins_url('/../assets/css/style.css', __FILE__) ?>">
</head>

<body>
    <script id="botmanWidget" src="<?= plugins_url('/../assets/js/chat.js', __FILE__) ?>"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script>
        $(function() {
            $('.btn').click(function() {
                window.top.postMessage('botmanMsgBtnClick', '*');
            });
        });
    </script>
</body>

</html>