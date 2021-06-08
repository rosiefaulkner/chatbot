class Chat {

    constructor(wrapper)
    {
        this.wrapper = wrapper;
        this.content = '';
        this.modified = 0;
        this.loaded = 0;

        this.listen();
        setInterval(this.load.bind(this), 5000);
    }

    listen()
    {
        // $('body').on('DOMSubtreeModified', this.wrapper, function() {
        $(document).ajaxComplete(function() {
            alert('ajax');
            if (Date.now() > this.modified) {
                setTimeout(this.modify.bind(this), 1000);
            }
        }.bind(this))
    }

    modify()
    {
        console.log('modified');
        this.modified = Date.now()
        this.content = $(this.wrapper).html()
    }

    load()
    {
        console.log('load called');
        if (this.modified > this.loaded) {
            console.log('loaded');
            this.loaded = Date.now()
            this.write()
            this.writeLog()
        }
    }

    resume()
    {
        this.modified = Date.now()
        this.content = localStorage.chatLog
    }

    started()
    {
      return typeof localStorage.chatLog !== 'undefined'
    }

    write()
    {
        $(this.wrapper).html(this.content)
    }

    writeLog()
    {
        localStorage.chatLog = this.content
    }
}
