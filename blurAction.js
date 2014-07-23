<script type="text/javascript">
$(document).ready( function() {

	function blurAction() {

		if ($('#MessageMlsRequest').is(':checked') && $('#MessageMlsRequested').val().length != 6) {
			$(':submit').attr('disabled','disabled');
			
			if ($('p.warn_mls').length == false) {
				$('#MessageSendForm').append('<p class="warn_mls" style="float:left;font-weight:bold;">Please enter the MLS number of the listing you are interested in.</p>');
			}
			
			$('#MessageMlsRequested').removeAttr('style');
			$('label[for="MessageMlsRequested"]').removeAttr('style');
			
		} else if ($('#MessageMlsRequest').is(':checked') && $('#MessageMlsRequested').val().length == 6 ) { 
			$(':submit').removeAttr('disabled');
			$('.warn_mls').remove();
			
		} else {
			$(':submit').removeAttr('disabled');
			$('#warn_mls').remove();
			$('#MessageMlsRequested').attr('style', 'display:none');
					$('label[for="MessageMlsRequested"]').attr('style', 'display:none;');
		}
	}

	$('label[for="MessageMlsRequested"]').attr('style', 'display:none;');

	blurAction();
	
	$('#MessageMlsRequest').click( function() {
		 blurAction();
	});
	
	$('#MessageMlsRequested').blur( function() {
		 blurAction();
	});
	
	$('#MessageMlsRequested').keyup( function() {
		 blurAction();
	});
});

</script>
