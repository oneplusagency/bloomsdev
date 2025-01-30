// Kontakt form
(function($) {
    $('#bloom_kontakt_form').submit(function(event) {
        event.preventDefault();
        // var dats = JSON.stringify($(this).serializeArray());
        var value = $(this).serializeArray(),
            request = {
                'option': 'bloom_kontakt',
                'type': 'kontakt-form',
                'data': value,
                // 'format': 'jsonp'
            };
        $.ajax({
            url: BloombaseUrl + '/json/kontakt',
            type: 'POST',
            data: request,
            beforeSend: function() {
                $('#bloom_kontakt_submit').buttonSalo('Laden...');
            },
            success: function(response) {
                console.log("Information "+response)
                // alert(response)
                $('#bloom_kontakt_status').hide().html(response).fadeIn().delay(2000).fadeOut(800);
                $('#bloom_kontakt_submit').buttonSalo('reset');

                $('#bloom_kontakt_submit').removeAttr('disabled');
                $('#bloom_kontakt_message').val('');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                // alert("Status: " + textStatus);
                // alert("Error: " + errorThrown);
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
                $('#bloom_kontakt_submit').buttonSalo('reset');
            }
        });
        return false;
    });

    /**
     *  oppo (webiprog.de)  @Date: 2021-01-14 20:25:53
     * @Desc: disable modal after click email
     */
    $('body').on('click', 'a.dismodal', function(e) {
        e.stopPropagation();
        // $('#LeitungDataModal').modal("hide");
    });
    
    $('#slider-images').carousel({ 
        //interval: 1000, 
        interval: 50, 
        cycle: true 
    });
    
    $('body').on('click','.close',function(){
       $('#kontaktcarousel').hide();
       $("#kontaktcarouselStaff")[0].reset();
       $("#kontaktcarouselAdmin")[0].reset();
        
    });
    
    
    $("body").on('click','.SeminareNozdryaAdmin',function(){
        var $this = $(this);
        var slideObj = $("#AdminLeitungNozdrya").children('.carousel-inner');
        slideObj.children('.carousel-item').removeClass('active');
        slideObj.children('#adminSlide_'+$this.attr('data-slide-to')).addClass('active');
        
        $("#kontaktcarouselAdmin").hide();
        $("#kontaktcarouselAdmin")[0].reset();
        uploadBtnResetLable('kontaktcarouselAdmin');
        $("#adminEmail").val('');
        $("#adminEmail").val($this.attr('data-email'));
        $('#kontact_kontakt_status_admin').html('');
        $('.btn-hotel').show();
        $('.btn-hotel-after').hide();
    });
    
    $("body").on('click','.SeminareNozdryaStaff',function(){
        var $this = $(this);
        var slideObj = $("#StaffLeitungNozdrya").children('.carousel-inner');
        slideObj.children('.carousel-item').removeClass('active');
        slideObj.children('#staffSlide_'+$this.attr('data-slide-to')).addClass('active');
        
        $("#kontaktcarouselStaff").hide();
        $("#kontaktcarouselStaff")[0].reset();
        $("#staffEmail").val('');
        $("#staffEmail").val($this.attr('data-email'));
        $('#kontact_kontakt_status').html('');
        
        uploadBtnResetLable('kontaktcarouselStaff');
        
        $('.btn-hotel').show();
        $('.btn-hotel-after').hide();
    });
    
    $(".moveCrScroll").click(function(){
        var $this = $(this);
        $("#"+$this.attr('data-form')).hide();
        $("#"+$this.attr('data-form'))[0].reset();
        selectedEmail='';
        if($this.attr('data-slide') == "prev"){
            //console.log("ID "+$($this.attr('href')).children('.carousel-inner').find('.active').prev().attr('id'));
            var selectedEmail = $($this.attr('href')).children('.carousel-inner').find('.active').prev().attr('data-email');
        }else{
           // console.log("ID "+$($this.attr('href')).children('.carousel-inner').find('.active').next().attr('id'));
            var selectedEmail = $($this.attr('href')).children('.carousel-inner').find('.active').next().attr('data-email');
        }
        
        $("#"+$this.attr('data-form')).find('input[name="email"]').val('');
        
        //console.log("Selectred  "+selectedEmail);
        $("#"+$this.attr('data-form')).find('input[name="email"]').val(selectedEmail);
        
        $('#kontact_kontakt_status').html('');
        $('#kontact_kontakt_status_admin').html('');
        
        
        uploadBtnResetLable('kontaktcarouselAdmin');
        uploadBtnResetLable('kontaktcarouselStaff');
        
        $('.btn-hotel').show();
        $('.btn-hotel-after').hide();
        
    });
    
    $(".btn-hotel").click(function(){
        var $this = $(this);
        $this.hide();
        $this.next('.btn-hotel-after').show();
    });
    
   
    $('#kontaktcarouselAdmin').submit(function(event) {
        event.preventDefault();
        // var dats = JSON.stringify($(this).serializeArray());
        
        $('#kontact_kontakt_status_admin').html('Laden...');
        
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
                $('#kontact_sendbtn_admin').buttonSalo('loading');
            },
            success: function(response) {
                // alert(response)
                $('#kontact_kontakt_status_admin').html(response);
                

                $('#kontact_sendbtn_admin').removeAttr('disabled');
                
                if(response != '<div class="alert alert-danger rounded-0" role="alert">Falsches Dateiformat</div>'){
                    $('#kontact_sendbtn_admin').buttonSalo('reset');
                    $("#kontaktcarouselAdmin")[0].reset();
                    uploadBtnResetLable('kontaktcarouselAdmin');
                }
                
                
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                // alert("Status: " + textStatus);
                // alert("Error: " + errorThrown);
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
                $('#kontact_sendbtn_admin').buttonSalo('reset');
            }
        });
        return false;
    });
    
    function uploadBtnResetLable(formid){
        $("#"+formid).find('.inputfile').next('label').children('span').text('Upload');
    }
    
    $('#kontaktcarouselStaff').submit(function(event) {
        event.preventDefault();
        
            $('#kontact_kontakt_status').html('Laden...');
              let data = new FormData(document.getElementById("kontaktcarouselStaff"));
              var files = $('#fileStaff')[0].files[0];
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
                      // alert(response)
                      $('#kontact_kontakt_status').html(response);
                      

                      $('#kontact_sendbtn').removeAttr('disabled');
                      if(response != '<div class="alert alert-danger rounded-0" role="alert">Falsches Dateiformat</div>'){
                        console.log("Resp "+response);
                        $('#kontact_sendbtn').buttonSalo('reset');  
                        $("#kontaktcarouselStaff")[0].reset();
                        uploadBtnResetLable('kontaktcarouselStaff');
                      }
                      
                  },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                      // alert("Status: " + textStatus);
                      // alert("Error: " + errorThrown);
                      $('#kontact_kontakt_status').html('<div class="alert alert-danger rounded-0" role="alert">Something went wrong.Please try after some time.</div>');
                      console.log("Status: " + textStatus);
                      console.log("Error: " + errorThrown);
                      $('#kontact_sendbtn').buttonSalo('reset');
                  }
              });
              return false;
         
        });
        
       
        
    
    
    /*Google captcha*/
    /*
    $('#kontaktcarouselStaff').submit(function(event) {
        event.preventDefault();
        
        
        grecaptcha.ready(function() {
          grecaptcha.execute('6LdAaVMaAAAAABYEeIq-0L0Oz8CF79utem7g4ZWZ', {action: 'submit'}).then(function(token) {
              $('#kontact_kontakt_status').html('Laden...');
                let data = new FormData(document.getElementById("kontaktcarouselStaff"));
                var files = $('#fileStaff')[0].files[0];
                data.append('image', files);
                data.append('g-recaptcha-response',token);
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
                        console.log("Information "+response)
                        // alert(response)
                        $('#kontact_kontakt_status').html(response);
                        $('#kontact_sendbtn').buttonSalo('reset');

                        $('#kontact_sendbtn').removeAttr('disabled');

                        $("#kontaktcarouselStaff")[0].reset();
                        $("#staffEmail").val('');

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        // alert("Status: " + textStatus);
                        // alert("Error: " + errorThrown);
                        $('#kontact_kontakt_status').html('<div class="alert alert-danger rounded-0" role="alert">Something went wrong.Please try after some time.</div>');
                        console.log("Status: " + textStatus);
                        console.log("Error: " + errorThrown);
                        $('#kontact_sendbtn').buttonSalo('reset');
                    }
                });
                return false;
          });
        });
        
       
        
    });*/
    
    
    


})(jQuery);

        

	function showcarouselformAdmin(who){
		$(who).hide();
		$('form#kontaktcarouselAdmin').fadeIn("slow");
	}
        function showcarouselformStaff(who){
		$(who).hide();
		$('form#kontaktcarouselStaff').fadeIn("slow");
	}
        
       