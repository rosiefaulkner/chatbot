<!doctype html>
<html>
    <head>
        <title>PetPro Connect</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css" rel="preload" />
        <link rel="stylesheet" type="text/css" href="<?= plugins_url('/../assets/css/style.css', __FILE__) ?>" rel="preload" />
    </head>
    <body>
        <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous" defer></script>
        <script src="//cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js" id="botmanWidget" defer></script>
        <script src="//assets.calendly.com/assets/external/widget.js" defer></script>
        <script src="<?= plugins_url('/../assets/js/jquery.initialize.min.js', __FILE__) ?>" defer></script>
        <script src="<?= plugins_url('/../assets/js/chat.js', __FILE__) ?>" defer></script>
        <script>
            window.onload = () => {
                $(function() {
                    // var chat = new Chat('.chat');
                    if (
                        typeof window.top.botmanChatWidget !== 'undefined'
                        // && !chat.started()
                    ) {
                        window.top.botmanChatWidget.whisper('Hi');
                    //} else {
                    //    chat.resume();t
                    }
                    $('body').on('click', '.btn', function() {
                        if (typeof window.top.botmanChatWidget !== 'undefined') {
                            // window.top.botmanChatWidget.say($(this).text());
                        }
                    });
                    $('body').on('click', '.scheduler-x', function() {
                        $(this).closest('.scheduler').hide();
                    });
                    $.initialize('.scheduler', function() {
                        let msg = $(this).closest('.chatbot');
                        if (msg.siblings().length == msg.index() +1) {
                            Calendly.initInlineWidget({
                                url: 'https://calendly.com/petpro-team/website',
                                parentElement: $(this)[0],
                                prefill: {},
                                utm: {}
                            });
                            $(this).show();
                        }
                    });
                });
            };
        </script>
    </body>
</html>