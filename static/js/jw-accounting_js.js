function generateReport(period_id, type)
{
	$("#reportExtractModal").modal();

	var UrlSelectionReport = '';

	if(type == 's26')
	{
		UrlSelectionReport = 'generate-report-s26'
	}
	else if(type == 'to62')
	{
		UrlSelectionReport = 'generate-report-to62'
	}
	else if(type == 's30')
	{
		UrlSelectionReport = 'generate-report-s30'
	}

	
	var spinnerLoad_el = document.getElementById('spinnerLoad');
	var downloadJwPDF_el = document.getElementById('downloadJwPDF');
	var urlRequestPDF = '';
	
	spinnerLoad_el.removeAttribute("hidden");
	downloadJwPDF_el.innerHTML = '';

	
	req_in();
	
	function req_in() {
		$.ajax({
        type: "GET",
        url: ci_base_url+UrlSelectionReport+"/"+period_id,
        //headers: {  'Access-Control-Allow-Origin': '*' },
        //data: {patient_id: patient_id, order_id: order_id},
        //type: 'dataType'
    }).done(function (data) {
		
    	obj = JSON.parse(data);
		if(obj.status == 'true')
		{
			urlRequestPDF = reportURL + obj.response;
			var forDownload = '<a href="'+urlRequestPDF+'" class=" d-sm-inline-block btn btn-sm btn-success shadow-sm" download><i class="fas fa-download fa-sm text-white-50"></i>'+obj.response+'</a>';
		   spinnerLoad_el.setAttribute("hidden", "hidden");

		   downloadJwPDF_el.innerHTML = forDownload;
		}
		else if(obj.status == 'false')
		{
						
			spinnerLoad_el.removeAttribute("hidden");
		}
		
    }).fail(function (err) {
    	console.log('fail');
    	req_in();
    }).always(function () {
    });
	}
}

function deleteTransanction(date, tc, createdate, ledgerID)
{
	var delBtn_el = document.getElementById('delBtn');
	var urlToDel = 'delrec/'+date+'/'+tc+'/'+createdate+'/'+ledgerID;
	delBtn_el.innerHTML = '<a class="btn btn-primary" href="'+urlToDel+'">Delete</a>';

	$("#deleteModal").modal();
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

function switchCaryFwdWW()
{
	location.href = ci_base_url+'switch-carry-Foward-ww';

	//console.log(desc);
}