<!doctype html>
<html>
    <head>
        <title>PetPro Connect</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= plugins_url('/../assets/css/style.css', __FILE__) ?>" />
    </head>
    <body>
        <script type="text/javascript" src="//code.jquery.com/jquery-3.6.0.slim.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js" id="botmanWidget"></script>
        <script type="text/javascript">
            $(function() {
                $('.btn').click(function() {
                    window.top.postMessage('botmanMsgBtnClick', '*');
                });
            });
        </script>
    </body>
</html>