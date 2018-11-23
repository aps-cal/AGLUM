/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    $.ajax({
        type: 'GET',
        url: '//aglum.lnx.warwick.ac.uk/it/computers',
        //data: {field: 'value'},
        dataType: 'jsonp',
        crossDomain: true,
        timeout: 0,
        jsonp: 'data',
        jsonpCallback: 'data',
        xhrFields: {// If you want to carry over the SSO token
                withCredentials: true 
        },
        success: function(data){
            //alert(JSON.stringify(data,'',2));
        //	user = data.UserData;
        //	$("#ITS").append('<b>'+user.user+'</b>');
                $.fn.loaddata(data);},	
        fail: function(responseTxt, statusTxt, xhr){
            if(statusTxt === "success")
                alert("External content loaded successfully!");
            if(statusTxt === "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        }
    });
});
	
$.fn.loaddata = function(data) {
    var rowSummary = '', rowDetail = '';
    var YesNo = ['Y','N'];
    //alert(JSON.stringify(data,'',2));
    //$("#Academic_Year").val(data.Academic_Year);
    //$("#Academic_Term").val(data.Academic_Term);
    $("#ListOrder").val(data.ListOrder);
    //$("#ITS_User").append('.');
    //$("#SQL").empty().append('<p> SQL: '+data.sql+'</p>');
    if(data.message){
       $("#Message").empty().append('<p>'+data.message+'</p>');
       //.append('<p> Error: '+data.error+'</p>');
    }
    // Load Filter Dropdowns 
    $html = Options("Status",data.statuses,data.Status);
    $("#Status").empty().append($html).val(data.Status);
    $html = Options("Location",data.locations,data.Location);
    $("#Location").empty().append($html).val(data.Location);
    $html = Options("User",data.users,data.User);
    $("#User").empty().append($html).val(data.User);
    $html = Options("Type",data.types,data.Type);
    $("#Type").empty().append($html).val(data.Type);
    $html = Options("Make",data.makes,data.Make);
    $("#Make").empty().append($html).val(data.Make);
    $html = Options("Model",data.models,data.Model);
    $("#Model").empty().append($html).val(data.Model);
    $html = Options("Serial_No",data.serials,data.Serial_No);
    $("#Serial_No").empty().append($html).val(data.Serial_No);
    $html = Options("Computer_Name",data.compnames,data.Computer_Name);
    $("#Computer_Name").empty().append($html).val(data.Computer_Name);
    //$html = Options("Tag_No",data.tags,data.Tag_No);
    //$("#Tag_No").empty().append($html).val(data.Tag_No);
    $('#Computers').empty();
    for (index = 0; index < data.computers.length; ++index) {
    cpu = data.computers[index];
            rowSummary = '<tr id="Summary">'
            +'<td align="left"><input name="AL_Tag" value="'+cpu['ALTAG']+'" type="radio"/>'
            + '  '+cpu['Status'] + '</td>' 
            + '<td>'+cpu['Location'] + '</td>'
            + '<td>'+cpu['User'] + '</td>'
    + '<td>'+cpu['Type'] + '</td>'
            + '<td>'+cpu['Make'] + '</td>'
    + '<td>'+cpu['Model'] + '</td>'
            + '<td>'+cpu['Serial_No'] + '</td>'
            + '<td>'+cpu['Computer_Name'] + '</td>'
    //+ '<td>'+cpu['Tag_No'] + '</td>'		
    +'</tr>';
            $('#Computers').append(rowSummary);	
    }
    $("input:radio").click(function(){getEquip();});
};

function Reorder($cols){
    if($('#ListOrder').val() === $cols){
        if($cols.indexOf(',')>0){
            $cols = $cols.substring(0,$cols.indexOf(','));
        }
        $cols+=' DESC';
    } 
    $('#ListOrder').val($cols);
    $.fn.submitForm();
}
$(".Filter").change(function(e){
        $.fn.submitForm();					 
});
	
	
$.fn.submitForm = function(){
    var formdata = $("#computers").serialize();
    //alert($("#computers").serializeObject());
    //var formData = JSON.stringify($("#computers").serializeObject());
    formdata = '&'+formdata;
    $.ajax({
            type: 'GET',
            url: '//aglum.lnx.warwick.ac.uk/it/computers',
            data: formdata, //'&ListOrder=Serial_No&Location=S1.84', //&User=&Type=&Make=&Model=&Serial_No=&Tag_No=&Status=', //'', //formdata,
            // Note: it looks like there is a problem caused by empty entries being returned
            contentType: 'application/x-www-form-urlencoded',
            dataType: 'jsonp',
            crossDomain: true,
            jsonp: 'data',
            jsonpCallback: 'data',
            xhrFields: {// If you want to carry over the SSO token
                    withCredentials: true 
            },
    success: function(data){
            //alert(JSON.stringify(data,'',2));
            $.fn.loaddata(data);	
            },
    fail: function(responseTxt, statusTxt, xhr){
            if(statusTxt === "success")
                    alert("External content loaded successfully!");
            if(statusTxt === "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            }
    });
};
	
$("#New").click(function(e){
        //Clear the Selected Record
        $("input[name='AL_Tag']").prop("checked",false);
        $("input[name='AL_Tag']").val(0);
        getEquip();
});

$("#ExportData").click(function(e){
        //Clear the Selected Record
        //$("input[name='AL_Tag']").prop("checked",false);
        //$("input[name='AL_Tag']").val(0);
        //alert("Clicked");
        getExport();
});

function getExport(){
    //var data = {}, user = {};

    var formdata = $("#computers").serialize();
    //alert($("#computers").serializeObject());
    //var formData = JSON.stringify($("#computers").serializeObject());
    formdata = '&'+formdata;

    //alert(formdata);
    $.ajax({
            type: 'GET',
            url: '//aglum.lnx.warwick.ac.uk/it/export',
            data: formdata, //'&ListOrder=Serial_No&Location=S1.84', //&User=&Type=&Make=&Model=&Serial_No=&Tag_No=&Status=', //'', //formdata,
            // Note: it looks like there is a problem caused by empty entries being returned
            contentType: 'application/x-www-form-urlencoded',
            dataType: 'jsonp',
            crossDomain: true,
            jsonp: 'data',
            jsonpCallback: 'data',
            xhrFields: {// If you want to carry over the SSO token
                    withCredentials: true 
            },
    success: function(data){
            //alert(JSON.stringify(data,'',2));
            //$.fn.exportData(data);
            exportData(data);
            //$("#computers").hide();	
            //$.fn.loaddata(data);	
            },
    fail: function(responseTxt, statusTxt, xhr){
            if(statusTxt === "success")
                    alert("External content loaded successfully!");
            if(statusTxt === "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            }
    });
};

function exportData(data){
//$.fn.exportData = function( data ) {
   //$csvfile = fopen("php://IT_Inventory_Export_".date("Ymd").".csv",'w');
   //$csvfile = fopen("php://IT_Inventory_Export.csv",'w');

   $csv = "";
   $firstLineFields = '';
   for (index = 0; index < data.computers.length; ++index) {
      $line = data.computers[index];
   //$lines = data.computers;
   //foreach($lines as $line){
      if($firstLineFields===''){
         $firstLineFields = array_keys($line);
         $csv+=implode(", ",$firstLineFields);
         //fputcsv($csvfile,firstLineFields);
         $firstLineFields = array_flip($firstLineFields);
         // Alternative to the above might be ... 
         // using reset to move array pointer back to start.
         // fputcsv($csvfile,array_keys(reset($line));
         // But would still need to add $firstLineFields = true;
      }
      $csv+=implode(", ",array_merge($firstLineFields,$line));
      //fputscv($csvfile, array_merge($firstLineFields,$line));
   }
   window.open("data:text/csv;charset=utf-8,"+escape($csv));
};


function setHeader($filename, $filesize){
   // disable caching
   $now = gmdate("D, d M Y H:i:s");
   header("Expires: Tue, 01 Jan 2001 00:00:01 GMT");
   header("Cache-Control: max-age-0, no-cache, must-revalidate, proxy-revalidate");
   header("Last-Modified: {$now} GMT");

   // force download
   header("Content-Type: application/force-download");
   header("Content-Type: application/octet-stream");
   header("Content-Type: application/download");
   header("Content-Type: text/x-csv");

   // disposition / encoding on response body
   if(isset($filename) && strlen($filename) > 0)
      header("Content-Disposition: attachment; filename={$filename}");
   if(isset($filesize))
      header("Content-Length: ".$filesize);
   header("Content-Transfer-Encoding: binary");
   header("Connection: close");
};

function ShowOnlyRow(TableID,RowID){
   //alert(TableID);
   var t = document.getElementById(TableID);
   //alert(t.id);
   var l = t.rows.length;
   //alert(l);
   for(i=0; i<=l; i++){
      //alert(t.rows[i].id);
      if(String(t.rows[i].id).substring(0,6)=='Detail'){
         t.rows[i].style.display='none';
         //alert(String(t.rows[i].id).substring(6));
         if(String(t.rows[i].id).substring(6)==RowID){
            t.rows[i].style.display='';
         }
      }
   }
}
function Left(str, n){
	if (n <= 0)
	    return "";
	else if (n > String(str).length)
	    return str;
	else
	    return String(str).substring(0,n);
}
function Right(str, n){
    if (n <= 0)
       return "";
    else if (n > String(str).length)
       return str;
    else {
       var iLen = String(str).length;
       return String(str).substring(iLen, iLen - n);
    }   
}