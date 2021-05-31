<!doctype html>
<html>
    <head>
        <title>PetPro Connect</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css" rel="preload" />
        <link rel="stylesheet" type="text/css" href="<?= plugins_url('/../assets/css/style.css', __FILE__) ?>" rel="preload" />
    </head>
    <body>
        <script type="text/javascript" src="//code.jquery.com/jquery-3.6.0.slim.min.js" defer></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js" id="botmanWidget" defer></script>
        <script type="text/javascript" src="//assets.calendly.com/assets/external/widget.js" defer></script>
        <script type="text/javascript" src="<?= plugins_url('/../assets/js/jquery.initialize.min.js', __FILE__) ?>" defer></script>
        <script type="text/javascript">
            window.onload = () => {
                $(function() {
                    if (typeof window.top.botmanChatWidget !== 'undefined') {
                        window.top.botmanChatWidget.whisper('Hi');
                    }
                    $('body').on('click', '.btn', function() {
                        if (typeof window.top.botmanChatWidget !== 'undefined') {
                            // window.top.botmanChatWidget.say($(this).text());
                        }
                    });
                    $('body').on('click', '.scheduler-x', function() {
                        if ($('#scheduler').length > 0) {
                            $('#scheduler').closest('.chatbot').remove();
                            obs.disconnect();
                        }
                    });
                    var obs = $.initialize('#scheduler', function() {
                        Calendly.initInlineWidget({
                            url: 'https://calendly.com/petpro-team/website',
                            parentElement: document.getElementById('scheduler'),
                            prefill: {},
                            utm: {}
                        });
                        // .then(function() {
                        //     obs.disconnect();
                        // });
                    });
                });
            };
        </script>
    </body>
</html>