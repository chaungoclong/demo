function validateLogin() {

}

function validateRegistor() {

}

function isEmail(string){
	var pattern = /^[a-z][a-z0-9_\.]{5,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/;
	return pattern.test(string);
}

function isPassword(string) {

}

function isDate(string) {
	var pattern = /^[12]\d{3}-(0[1-9]|1[12])-(0[1-9]|[12]\d|3[01])$/;
	return pattern.test(string);
}

function formatDate(string) {
	return string.split("-").reverse().join("-");
}