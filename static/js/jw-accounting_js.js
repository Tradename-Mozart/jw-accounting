//window.history.forward(1);

function loginRwdFromGuest()
{
	location.href = ci_base_url+'login';
}

function registerFromSnlGuest()
{
	var error = '';
	// Validations
	var phone_email_el = document.getElementById('email_phone_el');
	var cb_copy_pass = document.getElementById('copy_pass_check');
	var confirm_sup_infor = document.getElementById('supplied_correct');
	var error_def_el = document.getElementById('error_def');
	var notif_error_el = document.getElementById('notif_error');
	var regBtn_el = document.getElementById('registerBtn');
	var spinnerLoad_el = document.getElementById('spinnerLoad');
	
	if(phone_email_el.value == '')
		{
			error += 'Email Or Phone Is Required <br/>';
		}
	
	if(cb_copy_pass.checked == false)
		{
			error += 'Copy Your Password And Confirm <br/>';
		}
	
	if(confirm_sup_infor.checked == false)
		{
			error += 'Confirm That The Supplied Infor Is Correct <br/>';
		}
	
	error_def_el.innerHTML = error;
	
	
	if(error != '')
		{
			notif_error_el.removeAttribute("hidden");
		}
	
	
	// EOF Validations
	
	
	if(error == '')
		{
			
			notif_error_el.setAttribute("hidden", "hidden");
			regBtn_el.setAttribute("class", "btn btn-primary disabled");
			spinnerLoad_el.removeAttribute("hidden");
			req_in();
			
         }
	
	
	
	function req_in() {
		$.ajax({
        type: "GET",
        url: ci_base_url+"register_snl_easy_rwd/"+phone_email_el.value+'/'+passForRegister,
        //headers: {  'Access-Control-Allow-Origin': '*' },
        //data: {patient_id: patient_id, order_id: order_id},
        //type: 'dataType'
    }).done(function (data) {
		
    	obj = JSON.parse(data);
		if(obj.status == 'true')
		{
		 console.log('uni_id => '+ obj.uni_id);
		 updateCodeBreakerEastRWD(obj.uni_id);
		}
		else if(obj.status == 'false')
		{
			error = obj.error;
			error_def_el.innerHTML = error;
			notif_error_el.removeAttribute("hidden");
			regBtn_el.setAttribute("class", "btn btn-primary");
			spinnerLoad_el.setAttribute("hidden", "hidden");
			
		}
		
    }).fail(function (err) {
    	console.log('fail');
    	req_in();
    }).always(function () {
    });
	}
}

function updateCodeBreakerEastRWD(uni_id)
{
	
	req_in();
	
	function req_in() {
		$.ajax({
        type: "GET",
        url: reqBaseURL+'codebreaker/register_snl/'+uni_id+'/'+uidFromServer,
        //headers: {  'Access-Control-Allow-Origin': '*' },
        //data: {patient_id: patient_id, order_id: order_id},
        //type: 'dataType'
    }).done(function (data) {
		
    	obj = JSON.parse(data);
		if(obj.status == 'true')
		{
		   location.href = ci_base_url+'login?registersuc=true'
		}
		
    }).fail(function (err) {
    	console.log('fail');
    	req_in();
    }).always(function () {
    });
	}
}

function getCurrentDate()
{
var today = new Date();
var yyyy = today.getFullYear();
let mm = today.getMonth() + 1; // Months start at 0!
let dd = today.getDate();
let hh = today.getHours();
let min = today.getMinutes();
let sec = today.getSeconds();

if (dd < 10) dd = '0' + dd;
if (mm < 10) mm = '0' + mm;

//today = dd + '/' + mm + '/' + yyyy;
today = yyyy + '-' + mm + '-' + dd+ '-T'+hh+min+sec;
return today;
}

function populateDescription(desc)
{
	var desTextArea = document.getElementsByClassName('desTextArea');
	desTextArea[0].value = '';
	desTextArea[0].value = desc;
	desTextArea.innerHTML = desc;

	//console.log(desc);
}