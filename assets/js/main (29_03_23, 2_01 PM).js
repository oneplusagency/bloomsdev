/*Author global script*/
var modalwho;

(function($) {
   if(window.opener == null){
		if(document.getElementById("page-impressum")){
			 document.getElementById("page-impressum").classList.add("no-parent-opener");
		}
		 
   }
	$("button#next-button.btn-modal-arrow").on( "click", function() {
		$('.modal.modal-salon-subpage .modal-body img').css('opacity','0.3');
		modalwho =  $(modalwho).next();
		if(modalwho.length  == 0) {
			modalwho =  $('#team-view .team-employee-profile').first();
		}	
		var img = modalwho.find('img').attr('src-alt');
		$('.modal.modal-salon-subpage .modal-body img').attr('src',img);
		setTimeout(function(){		
			$('.modal.modal-salon-subpage .modal-body img').css('opacity','1');
		}, 400);
	
		
	});	
	$("button#prev-button.btn-modal-arrow").on( "click", function() {
		$('.modal.modal-salon-subpage .modal-body img').css('opacity','0.3');
		modalwho =  $(modalwho).prev();
		if(modalwho.length  == 0) {
			modalwho =  $('#team-view .team-employee-profile').last();
		}	
		var img = modalwho.find('img').attr('src-alt');
		$('.modal.modal-salon-subpage .modal-body img').attr('src',img);
		setTimeout(function(){		
			$('.modal.modal-salon-subpage .modal-body img').css('opacity','1');
		}, 400);		
	
	});
	$("#team-view .team-employee-profile").on( "click", function() {
		modalwho = $(this);
		$('.modal.modal-salon-subpage .modal-body img').css('opacity','0.3');
		var img = modalwho.find('img').attr('src-alt');
		$('.modal.modal-salon-subpage .modal-body img').attr('src',img);
		setTimeout(function(){
			$('.modal.modal-salon-subpage .modal-body img').css('opacity','1');
		}, 400)
		
	});

    $(document).ready(function() {
        $("#option_salon option[value='31']").prop('selected', true);
        // you need to specify id of combo to set right combo, if more than one combo
    });


	$('#bloomstip').on({
			
		"click": function() {
				$('.tooltip.bloomstip').css('display', 'block');
				var service = $("#servicePackageField option:selected").val();
				var url = BloombaseUrl + '/termine/getServicePackageDescription';
                $.getJSON(url, {
                        format: 'JSON',
                        servicePackage: service
                    })
                    .done(function(res) {
			let Servise_Description = res.ServicePackage.Description;
			Servise_Description = Servise_Description.replace(/(?:\r\n|\r|\n)/g, '<br>');
			$('#bloomstip').tooltip({ tooltipClass: "bloomstip", items: "#bloomstip", content:Servise_Description});
			$('#bloomstip').tooltip("open");
		  })
		.fail(function(jqxhr, textStatus, error) {
                        var err = textStatus + ', ' + error;
                        console.log('Request Failed: ' + err);
		});		
		setTimeout(function() {
			$('.tooltip.bloomstip').css('display', 'none');
		}, 4000);

		},
		"mouseout": function() {
			//$('.bloomstip.'+bloomstooltip).css('display','none');
		}
		});
	
	/* $('span#s2tooltip').on({
			
		"click": function() {
				$('.tooltip.bloomstip').css('display', 'block');
		
      

			$('#s2tooltip').tooltip({ tooltipClass: "bloomstip", items: "#s2tooltip",  placement: "top"});
			$('.tooltip-super-ua.bloomstip').css('display', 'block');
		
		setTimeout(function() {
				$('.tooltip.bloomstip').css('display', 'none');
		}, 4000);

		},
		"mouseout": function() {
			//$('.bloomstip.'+bloomstooltip).css('display','none');
		}
		});
	$('#s2tooltip').live('mouseleave', function() {
        $('#s2tooltip').fadeOut('fast');
    }); */

})(jQuery);
function modalthis(modalwho){

}






