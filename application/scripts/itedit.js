/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




/*********
* Toggle *
*********/
function Toggle(field,value) {
    var item, opts = {};
    var result='<select class="Toggle" id="txt'+field+'" >';	
    result+='<option value="N">No</option><option value="Y">Yes</option></select>';
    return(result);
}
/********
* Alist * Little pair of functions to create and work a Combo class 
********/
function Alist(field,options,value) {
    var item, opts = {};
    if(typeof(value) == "undefined"){value='';}
    var result='<input class="Alist" type="text" id="txt'+field+'" name="txt'+field+'" value="'+value+'" onchange="oAlist(this);" style="display:none;"/>'
            +'<select class="Alist" id="lst'+field+'" onChange="oAlist(this);" >';
    opts = options;
    for (index = 0; index < opts.length; ++index) {
            opt = opts[index];
            fld = opt[field];
            result+="<option>"+fld+"</option>";
    }
    result+="<option>Other...</option>";
    result+='</select>';
    return(result);
}
function oAlist(obj) {
    $obj = obj.id;
    btn = "#btn"+$obj.substr(3);
    txt = "#txt"+$obj.substr(3);
    lst = "#lst"+$obj.substr(3);
    // Which object called the function?
    if(obj.id.substr(0,3) === 'txt'){
        if($(txt).val()!='Other...' && $(txt).val()!=''){
            //$(btn).height($(txt).height());
            //$(lst).width($(txt).width()+$(btn).width()+2);
            //$(lst).children().height($(txt).height());
            $(lst).show();
            $val = $(txt).val();
            $(lst).append("<option>"+$val+"</option>");
            $(lst).val($val);
            $(lst).focus();
            $(txt).hide();
            //$(btn).hide();
        }		
    }
    if(obj.id.substr(0,3) === 'lst'){
        if($(lst).val()=='Other...'){
            $(txt).height($(lst).height());
            $(txt).width($(lst).width());
            $(txt).show();
            $val = $(lst).val();
            $(txt).val($val);
            $(txt).focus();
            $(lst).hide();
            //$(btn).show();
        } else {
            $val = $(lst).val();
            $(txt).val($val);
        }
    }
}	
/********
* COMBO * Little pair of functions to create and work a Combo class 
********/
function Combo(field,options,value) {
    var item, opts = {};
    if(typeof(value) == "undefined"){value='';}
    var result='<input class="Combo" type="text" id="txt'+field+'" name="txt'+field+'" value="'+value+'"/>'
               +'<input class="Combo" type="button" id="btn'+field+'" value="V" onclick="oCombo(this);" />'
    +'<select class="Combo"id="lst'+field+'" onchange="oCombo(this);" style="display:none;">';	
    opts = options;
    for (index = 0; index < opts.length; ++index) {
            opt = opts[index];
            fld = opt[field];
            result+="<option>"+fld+"</option>";
    }
    result+='</select>';
    return(result);
}
function oCombo(obj) {
    $obj = obj.id;
    btn = "#btn"+$obj.substr(3);
    txt = "#txt"+$obj.substr(3);
    lst = "#lst"+$obj.substr(3);
    // Which object called the function?
    if(obj.id.substr(0,3) === 'btn'){
        $(btn).height($(txt).height());
        $(lst).width($(txt).width()+$(btn).width()+2);
        $(lst).children().height($(txt).height());
        $(lst).show();
        $val = $(txt).val();
        $(lst).val($val);
        $(lst).focus();
        $(txt).hide();
        $(btn).hide();
    }
    if(obj.id.substr(0,3) === 'lst'){
        $(btn).height($(lst).height());
        //$(txt).width($(lst).width()-$(btn).width());
        $(txt).show();
        $val = $(lst).val();
        $(txt).val($val);
        $(txt).focus();
        $(lst).hide();
        $(btn).show();
    }
}	
function Options(field,options,value) {
        var item, opts = {};
//		var result='<input class="Combo" type="text" id="txt'+field+'" name="'+field+'" value="'+value+'"/>'
//			   +'<input class="Combo" type="button" id="btn'+field+'" value="V" onclick="oCombo(this);" />'
//		+'<select class="Combo"id="lst'+field+'" onchange="oCombo(this);" style="display:none;">';	
    opts = options;
    var result = '<option/>';
    for (index = 0; index < opts.length; ++index) {
        opt = opts[index];
        fld = opt[field];
        result+="<option>"+fld+"</option>";
    }
//		result+='</select>';
        return(result);
}


function getEquip(){
    var alTag = $("input[name='AL_Tag']:checked").val();
    if(alTag){//alert("alTag = "+alTag+" ");
    } else {alTag = 0;}

    var formdata = $("#computers").serialize();
    formdata = '&'+formdata+'&ALTag='+alTag;
    //alert(formdata);
    $.ajax({
            type: 'GET',
            url: '//aglum.lnx.warwick.ac.uk/it/getequipment',
            data: formdata, 
            contentType: 'application/x-www-form-urlencoded',
            dataType: 'jsonp',
            crossDomain: true,
            jsonp: 'data',
            jsonpCallback: 'data',
            xhrFields: {// If you want to carry over the SSO token
                    withCredentials: true 
            },
    success: function(data){
            $("#computers").hide();
            $("#equipment").show();
            $("fieldset#EditIT").show();
            $.fn.loadequip(data);	
            },
    fail: function(responseTxt, statusTxt, xhr){
            $("#computers").show();
            $("#equipment").show();
            $("fieldset#EditIT").show();
            if(statusTxt === "success")
                    alert("External content loaded successfully!");
            if(statusTxt === "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            }
    });
};

$.fn.loadequip = function(data) {
    var rowSummary = '', rowDetail = '';
    var equip = data.equip;
    $("#ALTag").val(data.equip.ALTag);

    // Combos
    $html = Combo("Model",data.models,data.equip.Model);
    $("input#txtModel").parent().html($html);
    $html = Combo("Location",data.locations,data.equip.Location);
    $("input#txtLocation").parent().html($html);
    $html = Combo("User",data.users,data.equip.User);
    $("input#txtUser").parent().html($html);
    $html = Combo("Serial_No",data.serials,data.equip.Serial_No);
    $("input#txtSerial_No").parent().html($html);
    $html = Combo("Tag_No",data.tags,data.equip.Tag_No);
    $("input#txtTag_No").parent().html($html);
    $html = Combo("Computer_Name",data.compnames,data.equip.Computer_Name);
    $("input#txtComputer_Name").parent().html($html);
    // AddLists
    $html = Alist("Type",data.types,data.equip.Type);
    $("input#txtType").parent().html($html);
    $("#lstType").val(data.equip.Type).prop('disabled', true);
    $html = Alist("Make",data.makes,data.equip.Make);
    $("input#txtMake").parent().html($html);
    $("#lstMake").val(data.equip.Make).prop('disabled', true);
    $html = Alist("Status",data.statuses,data.equip.Status);
    $("input#txtStatus").parent().html($html);
    $("select#lstStatus.Alist").val(data.equip.Status).prop('disabled', true);
    $html = Alist("OS",data.oss,data.equip.OS);
    $("input#txtOS").parent().html($html);
    $("#lstOS").val(data.equip.OS).prop('disabled', true);


    // Toggles
    $html = Toggle("Std_Image",data.Std_Image);
    $("input#txtStd_Image").parent().html($html);
    $html = Toggle("ITS",data.ITS);
    $("input#txtITS").empty().parent().html($html);
    $("#Description").val(data.equip.Description).prop('disabled', true);
    $("#Notes").val(data.equip.Notes).prop('disabled', true);
    $("#EnteredDate").val(data.equip.Entered_Date).prop('disabled', true);
    $("#EnteredBy").val(data.equip.Entered_By).prop('disabled', true);
    $("#DumpedDate").val(data.equip.Dumped_Date).prop('disabled', true);
    $("#DumpedBy").val(data.equip.Dumped_By).prop('disabled', true);
    $("#CheckedDate").val(data.equip.Checked_Date).prop('disabled', true);
    $("#CheckedBy").val(data.equip.Checked_By).prop('disabled', true);
    $("#UpdatedDate").val(data.equip.Updated_Date).prop('disabled', true);
    $("#UpdatedBy").val(data.equip.Updated_By).prop('disabled', true);
    $(".Combo").prop('disabled', true);
    $(".Alist").prop('disabled', true);
    $(".Toggle").prop('disabled', true);
};

$("input#Move").click(function(e){
    $("#txtLocation").prop('disabled', false);
    $("#btnLocation").prop('disabled', false);
    $("#lstLocation").prop('disabled', false);
    $("#txtUser").prop('disabled', false);
    $("#btnUser").prop('disabled', false);
    $("#lstUser").prop('disabled', false);
    $("#txtLocation").focus();
});

$("input#ToDump").click(function(e){
    if(confirm('Are you sure this item is to be dumped?')){
        $(".Combo").prop('disabled', false);
        $(".Alist").prop('disabled', false);
        $(".Toggle").prop('disabled', false);
        $(".Notes").prop('disabled', false);	
        $("#txtStatus").val('To Dump');
        $(":radio").prop('checked',false);
        $.fn.submitEdit();
    }
});

$("input#Dumped").click(function(e){
    if(confirm('Are you sure this item HAS ALREADY been dumped?')){
        $(".Combo").prop('disabled', false);
        $(".Alist").prop('disabled', false);
        $(".Toggle").prop('disabled', false);
        $(".Notes").prop('disabled', false);	
        $("#txtStatus").val('Dumped');
        $(":radio").prop('checked',false);
        $.fn.submitEdit();
    }
});
$("input#Edit").click(function(e){
    $(".Combo").prop('disabled', false);
    $(".Alist").prop('disabled', false);
    $(".Toggle").prop('disabled', false);
    $(".Notes").prop('disabled', false);	
});

$("input#Cancel").click(function(e){
    $(":radio").prop('checked',false);
    $("#computers").show();
    $("#equipment").hide();
});

$("input#Save").click(function(e){
    $(":radio").prop('checked',false);
    $(".Combo").prop('disabled', false);
    $(".Alist").prop('disabled', false);
    $(".Toggle").prop('disabled', false);
    $(".Notes").prop('disabled', false);
    //$(".Logging").prop('disabled', false);
    $.fn.submitEdit();
    //$("#computers").show();
    //$("#equipment").hide();
});

$.fn.submitEdit = function(){
    var equipdata = '&'+ $("form#equipment input").serialize();
    //alert(equipdata);
    $.ajax({
        type: 'GET',
        url: '//aglum.lnx.warwick.ac.uk/it/setequipment',
        data: equipdata, 
        contentType: 'application/x-www-form-urlencoded',
        dataType: 'jsonp',
        crossDomain: true,
        jsonp: 'data',
        jsonpCallback: 'data',
        xhrFields: {// If you want to carry over the SSO token
                withCredentials: true 
        },
        success: function(data){
            $("#computers").show();
            //$("#equipment").hide();
            $("fieldset#EditIT").hide();
            $.fn.loaddata(data);
        }, 
        fail: function(responseTxt, statusTxt, xhr){
            $("#computers").hide();
            $("fieldset#EditIT").show();
            //$("#equipment").show();
            if(statusTxt === "success")
                alert("External content loaded successfully!");
            if(statusTxt === "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        }
    });
};
