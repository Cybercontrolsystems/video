function setToday(name) {
	id = document.getElementById(name);
	var today = new Date();
	var month = today.getMonth() + 1;
	if (month < 10)
		month = '0' + month;
	var date = today.getDate();
	if (date < 10)
		date = '0' + date;
	var fulldate = today.getFullYear() + "-" + month + "-" + date;
	id.value = fulldate;
}

