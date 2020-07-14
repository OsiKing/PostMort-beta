<?php
include ('templates/header.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PortMort</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="./assets/jquery/jquery-3.5.1.min.js"></script>
    <script src="./assets/font awsome/js/"></script>
</head>
<body>
    <header class="container py-5">

        <!-- For demo purpose -->
        <div class="text-white text-center">
            <img src="./img/logo.png" alt="">

            <div class="com">
                <img src="./img/COMING SOON.png" alt="">
                <h1 class="com-header">Know the cause of death in split seconds</h1>
                <p class="lead-a mb-0">With advance AI technology you can get an autopsy result in few <br> seconds with 99% accuracy</p>
            </div>
        </div>
    
    
        <form  id="newsletter-frm" class="newsletter-frm">
        <div class="row py-4">
            <div class="col-lg-6 mi-auto">

                 <h2 class="text-center">Get notified when we launch</h2>
                <div class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm">
                    <input id="newsletter-email" placeholder="Enter Email" name="email" type="text" class="form-control border-0 newsletter-email">
                    <div class="input-group-append">
                        <label id="subscribe-newsletter" class="btn btn-light m-0 rounded-pill px-4"><small class="text-uppercase font-weight-bold text-muted" id="subscribe-newsletter">Subscribe</small></label>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </header>
    
    <script type="text/javascript">
        jQuery(document).on('click', 'button#subscribe-newsletter', function() {
            jQuery.ajax({
                type:'POST',
                url:'action.php',
                data:jQuery("form#newsletter-frm").serialize(),
                dataType:'json',    
                beforeSend: function () {
                    jQuery('button#subscribe-newsletter').button('Loding..');
                },  
        
                complete: function () {
                    jQuery('button#subscribe-newsletter').button('reset');
                    setTimeout(function () {
                        jQuery("form#newsletter-frm")[0].reset();
                    }, 2000);
                    
                },
        
                success: function (json) {
                    $('.text-danger').remove();
                    if (json['error']) {
                      jQuery('span#success-msg').html('');            
                        for (i in json['error']) {
                            var element = $('.newsletter-' + i.replace('_', '-'));
                            if ($(element).parent().hasClass('input-group')) {                       
                                $(element).parent().after('<div class="text-danger" style="font-size: 14px;">' + json['error'][i] + '</div>');
                            } else {
                                $(element).after('<div class="text-danger" style="font-size: 14px;">' + json['error'][i] + '</div>');
                            }
                        }
                    } else {
                      jQuery('span#success-msg').html('<div class="alert alert-success">You have successfully subscribed to the newsletter</div>');
                        
                    }                       
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }        
            });
        });
        </script> 
</body>
</html>
