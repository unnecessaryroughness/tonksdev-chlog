$(function(){
    displayRecords();
});


function displayRecords(){
    $("#tblSymptoms").html("");
    $("#tblSymptoms").append('<tr class="header">' +
                                //'<th class="fldNumeric">id</th>' +
                                '<th class="fldNumeric">Hide</th>' +
                                '<th class="fldChar">Description</th>' +
                                //'<th class="fldNumeric">sortorder</th>' +
                                '<th class="fldButton" colspan="2">Re-Order</th>' +
                            '</tr>');
    
    for (i=0; i<jso.symptoms.length; i++) {
        sid = jso.symptoms[i].symptomid;
        hid = jso.symptoms[i].hidden;
        des = jso.symptoms[i].description;
        srt = jso.symptoms[i].sortorder;
        buttonUp = "<button onclick='flipRecords(" + (i>0 ? i-1 : 0) + ", " + i + ")'>Up</button>";
        buttonDown = "<button onclick='flipRecords(" + i + ", " + (i+1 < jso.symptoms.length ? i+1 : i) + ")'>Dn</button>";
        
        $("#tblSymptoms").append("<tr id='row_" + i + "' class='row'></tr>");
        //$("#row_"+i).append("<td>" + sid + "</td>");
        $("#row_"+i).append("<td><input type='checkbox' class='fldChk' id='chkHid' name='hidden' onclick='hideRecord("+i+")'" + (hid ? " checked " : "") + "></td>");
        $("#row_"+i).append("<td><input type='text' class='fldWide' id='txtDesc' name='description' value='" + des + "'></td>");
        //$("#row_"+i).append("<td>" + srt + "</td>");
        $("#row_"+i).append("<td>" + (i == 0 ? "" : buttonUp) + "</td>");
        $("#row_"+i).append("<td>" + (i == jso.symptoms.length-1 ? "" : buttonDown) + "</td>");
    }
}


function flipRecords(r_a, r_b) {
    if (r_b - r_a != 1) {
        return false;
    }
    jso.symptoms.splice(r_a, 0, jso.symptoms[r_b]);
    jso.symptoms.splice(r_b+1, 1);
    $("#jsoSymptoms").val(JSON.stringify(jso));
    displayRecords();   
}

function hideRecord(r) {
    jso.symptoms[r].hidden = ! jso.symptoms[r].hidden;
    $("#jsoSymptoms").val(JSON.stringify(jso));
    displayRecords();
}