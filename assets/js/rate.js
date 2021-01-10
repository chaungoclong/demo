function fetchRate(proID) {
    let data = { proID: proID, action: "fetch_rate" };
    let result = sendAJax(
        'process_rate.php',
        'post',
        'json',
        data
    );
    if (result) {
        $('.display_rate').html(result.html);
    }
}

function rateExist(cusID, proID, action) {
	let result = sendAJax(
		'process_rate.php',
		'post',
		'text',
		{cusID:cusID, proID:proID, action:action}
	);
	if(result == '1'){
		return true;
	}
	return false;
}

function setRate(cusID, proID, rateValue, rateContent, action) {
	let result = sendAJax(
		"process_rate.php",
		"post",
		"json",
		{
			cusID:cusID,
			proID:proID,
			rateValue:rateValue,
			rateContent:rateContent,
			action:action
		}
	);
	return result;
}