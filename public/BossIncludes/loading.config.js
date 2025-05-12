        $.fn.Center= function(){
            this.css({
                'position':'fixed',
                'left':'50%',
                'top':'10%',
                'font-size':'30px',
                'color':'#8da',
                'display':'none'
            });
            this.css({
                'margin-left':-this.width()/2 + 'px',
                'margin-top' :-this.height()/2 + 'px'
            });	
        };