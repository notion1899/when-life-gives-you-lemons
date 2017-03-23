jQuery(document).ready(function($){
	
	$( "#rpco-add-rule-btn" ).click(function() {
		$( "#rpco-curl-rule-row-template" ).clone().appendTo( "#rpco-curl-rules-table" ).css( "display", "table-row" ).removeAttr("id");
		$( "#rpco-curl-rules-table tr:last-child .rpco-rule-host" ).attr("name", "rpco_settings_arr[curl_rules][" + rpcoRuleCount + "][host]");
		$( "#rpco-curl-rules-table tr:last-child .rpco-rule-protocol" ).attr("name", "rpco_settings_arr[curl_rules][" + rpcoRuleCount + "][protocol]");
		$( "#rpco-curl-rules-table tr:last-child .rpco-rule-option" ).attr("name", "rpco_settings_arr[curl_rules][" + rpcoRuleCount + "][option]");
		$( "#rpco-curl-rules-table tr:last-child .rpco-rule-value" ).attr("name", "rpco_settings_arr[curl_rules][" + rpcoRuleCount + "][value]");
		$( "#rpco-curl-rules-table tr:last-child .rpco-del-rule-btn" ).click(function( event ) {
			$( this ).parent("td").parent("tr").remove();
		});
		rpcoRuleCount = rpcoRuleCount + 1;
	});
	
	$( ".rpco-del-rule-btn" ).click(function( event ) {
		$( this ).parent("td").parent("tr").remove();
	});
	
	var rpcoTestOptions = { 
		target: '#rpco-test-result',
		beforeSubmit: rpcoShowTesting,
		success: rpcoShowResponse,
		clearForm: false,
		resetForm: false,
		timeout: 20000
    };
	
	jQuery('#rpco-test-form').submit(function() {
		jQuery(this).ajaxSubmit(rpcoTestOptions);
		return false;
    });
	
});

function rpcoShowTesting(formData, jqForm, options) {
	jQuery('#rpco-test-result').html('');
	jQuery('#rpco-test-result').hide();
	jQuery("#rpco-testing-ind").fadeIn("fast");
	return true;
}

function rpcoShowResponse(responseText, statusText, xhr, $form) {
	jQuery('#rpco-testing-ind').hide();
	jQuery('#rpco-test-result').slideDown('slow');
}