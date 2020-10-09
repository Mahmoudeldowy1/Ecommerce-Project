$(function () {

    'use strict';

    //Switch Between Login and Signup

    $('.login-page h1 span').click(function () {

        $(this).addClass('selected').siblings().removeClass('selected');

        $('.login-page form').hide();

        $('.' + $(this).data('class')).fadeIn(100);


    });

    //Hide placeholder on form focus

    $('[placeholder]').focus(function () {

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder' , '');

    }) . blur(function () {

        $(this).attr('placeholder', $(this).attr('data-text'));

    });

    // Add Asterisk On Required Field

   $('input').each(function () {

        if ($(this).attr('required') == 'required'){
            $(this).after('<span class="asterisk">*</span>')
        }
    })

    // Convert Password Field To Text Field On Hover


    // Confirmation Message On Button
    $('.confirm').click(function () {

        return confirm('Are You Sure?');
    })


    $('.live-name').keyup(function (){
        $('.live-preview .caption h3').text($(this).val());
    })

    $('.live-desc').keyup(function (){
        $('.live-preview .caption p').text($(this).val());
    })

    $('.live-price').keyup(function (){
        $('.live-preview  span').text('$' + $(this).val());
    })


});
