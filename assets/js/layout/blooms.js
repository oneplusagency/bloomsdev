(function($) {

    $('#werte-view').hide();
    $('#facts-view').hide();
    $('#historie-view').hide();
    $('#verwaltung-view').hide();
})(jQuery);

function showData(selection, div) {
    let buttons = $('#selection-tabs').find('button');

    for (let i = 0; i < buttons.length; i++) {
        jQuery(buttons[i]).removeClass('btn-active');
    }

    jQuery('#leitung-view').fadeOut("slow");
    jQuery('#werte-view').fadeOut("slow");
    jQuery('#facts-view').fadeOut("slow");
    jQuery('#historie-view').fadeOut("slow");
    jQuery('#verwaltung-view').fadeOut("slow");

    jQuery(selection).addClass('btn-active');
    jQuery(div).fadeIn("slow");


    // 12.01.2021
    // when clicking on tabs, the content should always be centered
    var tdiv = jQuery(div);
    if (tdiv.length) {
        var hh = tdiv.height(),
            chh = -250;
        // alert(hh)
        if (hh < 300) { chh = 0 }

        if (hh > 400) {
            chh = hh / 4;
            chh = -chh
        }
        jQuery('html, body').delay(200).animate({
            //scrollTop: hh + chh
            scrollTop: parseInt(jQuery(selection).offset().top)
        }, '600');
    }

}


function showDataLong(selection, div) {
    let buttons = $('#selection-tabs').find('button');

    for (let i = 0; i < buttons.length; i++) {
        jQuery(buttons[i]).removeClass('btn-active');
    }

    jQuery('#leitung-view').fadeOut("slow");
    jQuery('#werte-view').fadeOut("slow");
    jQuery('#facts-view').fadeOut("slow");
    jQuery('#historie-view').fadeOut("slow");
    jQuery('#verwaltung-view').fadeOut("slow");

    jQuery(selection).addClass('btn-active');
    jQuery(div).fadeIn("slow");


    // 12.01.2021
    // when clicking on tabs, the content should always be centered
    var tdiv = jQuery(div);
    if (tdiv.length) {
        var hh = tdiv.height(),
            chh = -250;
        // alert(hh)
        if (hh < 300) { chh = 0 }

        if (hh > 400) {
            chh = hh / 1;
            chh = -chh
        }
        jQuery('html, body').delay(200).animate({
            scrollTop: hh + chh + 600
        }, '600');
    }

}

function showcarouselformAdmin(who){
 
        $(who).hide();
       
       
        $('form#kontaktcarouselAdmin').fadeIn("slow");
}

jQuery(document).ready(function($) {
    $('[data-toggle="tooltip"]').tooltip();
    
    
    
    $(".btn-hotel").click(function(){
        var $this = $(this);
        $this.hide();
        $this.next('.btn-hotel-after').show();
    });
    $("body").on('click','.SeminareNozdrya',function(){
        var $this = $(this);
        var slideObj = $("#LeitungNozdrya").children('.carousel-inner');
        slideObj.children('.carousel-item').removeClass('active');
        slideObj.children('#staffSlide_'+$this.attr('data-slide-to')).addClass('active');
        
        $("#kontaktcarouselAdmin").hide();
        $("#kontaktcarouselAdmin")[0].reset();
        uploadBtnResetLable('kontaktcarouselAdmin');
        $("#adminEmail").val('');
        $("#adminEmail").val($this.attr('data-email'));
        $('.btn-hotel').show();
        $('.btn-hotel-after').hide();
    });
    
    $(".moveCrScroll").click(function(){
        var $this = $(this);
        $("#kontaktcarouselAdmin").hide();
        $("#kontaktcarouselAdmin")[0].reset();
        $('.btn-hotel').show();
        $('.btn-hotel-after').hide();
        
        selectedEmail='';
        if($this.attr('data-slide') == "prev"){
            //console.log("ID "+$($this.attr('href')).children('.carousel-inner').find('.active').prev().attr('id'));
            var selectedEmail = $($this.attr('href')).children('.carousel-inner').find('.active').prev().attr('data-email');
        }else{
           // console.log("ID "+$($this.attr('href')).children('.carousel-inner').find('.active').next().attr('id'));
            var selectedEmail = $($this.attr('href')).children('.carousel-inner').find('.active').next().attr('data-email');
        }
        
        $("#kontaktcarouselAdmin").find('input[name="email"]').val('');
        
        //console.log("Selectred  "+selectedEmail);
        $("#kontaktcarouselAdmin").find('input[name="email"]').val(selectedEmail);
    });
    
    
    
    $('#kontaktcarouselAdmin').submit(function(event) {
        event.preventDefault();
        // var dats = JSON.stringify($(this).serializeArray());
         $('#kontact_kontakt_status').html('Laden...');
        let data = new FormData(this);
        var files = $('#file')[0].files[0];
        data.append('image', files);
        $.ajax({
            url: BloombaseUrl + '/kontakt/sendinfo',
            type: 'post',
            data: data,
            dataType: 'text',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function() {
                $('#kontact_sendbtn').buttonSalo('loading');
            },
            success: function(response) {
                //console.log("Information "+response)
                // alert(response)
                $('#kontact_kontakt_status').html(response);
               
                $('#kontact_sendbtn').removeAttr('disabled');
                if(response != '<div class="alert alert-danger rounded-0" role="alert">Falsches Dateiformat</div>'){
                     $('#kontact_sendbtn').buttonSalo('reset');
                    $("#kontaktcarouselAdmin")[0].reset();
                    uploadBtnResetLable('kontaktcarouselAdmin');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                // alert("Status: " + textStatus);
                // alert("Error: " + errorThrown);
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
                $('#kontact_sendbtn').buttonSalo('reset');
            }
        });
        return false;
    });
    
    function uploadBtnResetLable(formid){
        $("#"+formid).find('.inputfile').next('label').children('span').text('Upload');
    }
});