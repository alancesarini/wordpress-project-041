(function($) {
	
	$(document).ready(function() {

        setTimeout("window.open(self.location, '_self');", 300000);

        $('.wp-social-login-provider-linkedin').text('Acceder con LinkedIn');
        console.log($('.wp-social-login-provider-linkedin'));

        RESPONSIVEUI.responsiveTabs();

        $('.linkedin-login').click(function(e) {
            e.preventDefault();
            var cid = $(this).data('cid');
            $.ajax({
                url: ajaxurl + '?action=project041-save-id',
                data: {'cid': cid},
                cache: false,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    if(data.response == 'OK') {
                        $('.wp-social-login-provider-linkedin')[0].click();
                    }        	
                }
            });	            
        });

        $('.subscribe-to-magazine').click(function(e) {
            e.preventDefault();
            var the_button = $(this);
            var mid = $(this).data('mid');
            $.ajax({
                url: ajaxurl + '?action=project041-subscribe-to-magazine',
                data: {'mid': mid},
                cache: false,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    if(data.response == 'OK') {
                        document.location.reload();
                    }        	
                }
            });	            
        });

        $('.register-in-conference').click(function(e) {
            e.preventDefault();
            var the_button = $(this);
            var cid = $(this).data('cid');
            $.ajax({
                url: ajaxurl + '?action=project041-register-in-conference',
                data: {'cid': cid},
                cache: false,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    if(data.response == 'OK') {
                        var message = the_button.siblings('span').text();
                        the_button.parent('.register-conference-message').html('<strong>' + message + '</strong>');
                    }        	
                }
            });	            
        });

        $('#calendar .prev, #calendar .next').live('click', function(e) {
            e.preventDefault();
            var month = $(this).data('month');
            var year = $(this).data('year');
            $('#calendar').load(
                ajaxurl + '?action=project041-load-calendar',
                {mm: month, yy: year},
                function() {
                    $('.event-action').tooltipster({
                        theme: ['tooltipster-shadow'],
                        maxWidth: 400,
                        size: {
                            width: 250,
                            height: 100
                        }
                    });    
                }
            );	            
        });        
        
        $('#search').on("click",(function(e){
            $(".form-group").addClass("sb-search-open");
            e.stopPropagation()
        }));
        
        $(document).on("click", function(e) {
          if ($(e.target).is("#search") === false && $(".form-control").val().length == 0) {
            $(".form-group").removeClass("sb-search-open");
          }
        });
        
        $(".form-control-submit").click(function(e){
            $(".form-control").each(function(){
              if($(".form-control").val().length == 0){
                e.preventDefault();
                $(this).css('border', '2px solid red');
              }
          });
        });        

        $('#fotos-demo').owlCarousel({
            navigation : true,
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem : true,
            dots : false,
            nav : false   
        });
        
        $(window).scroll(function(){
            if ($(window).scrollTop() >= 40) {
               $('.main_nav').addClass('fixed-header');
               //$('.top_header .addbanner').addClass('fixed-banner');
               $('.main_header').addClass('no-padding-top');
               $('.bodycontent').addClass('padding-top-150');
            }
            else {
               $('.main_nav').removeClass('fixed-header');
               //$('.top_header .addbanner').removeClass('fixed-banner');               
               $('.main_header').removeClass('no-padding-top');    
               $('.bodycontent').removeClass('padding-top-150');                          
            }
        });  

        $('.link-to-letter').click(function(event) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 1000, function() {
                        var $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) {
                            return false;
                        } else {
                            $target.attr('tabindex','-1');
                            $target.focus();
                        }
                    });
                }
        });

        $('#form-register-event').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: ajaxurl + '?action=project041-register-user-event',
                data: $('#form-register-event').serialize(),
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function(data){
                    if(data.response == 'OK') {
                        $('.register-messages').addClass('message-success');
                    } else {
                        $('.register-messages').addClass('message-error');
                    }
                    $('.register-messages').text(data.message);
                }
            });	                    
        });        
            
    });
})(jQuery);


