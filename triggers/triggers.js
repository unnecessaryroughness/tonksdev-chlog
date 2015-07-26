$(function(){    
    $("#cmdNew").on("click", function() { 
        modalwin = getModal();
        modalwin.open({content: $("#modalDialog").html()}); 
        $("#modalcontent #cmdCancel").on("click", function() { modalwin.close(); });
        $("#modalcontent #cmdAdd").on("click", function() { 
            if ($("#modalcontent #txtNewTrigger").val().length > 0) {
                addRecord($("#modalcontent #txtNewTrigger").val());    
            }
            modalwin.close();
        });
        $("#modalcontent #txtNewTrigger").focus();
    });
    
    displayRecords();
});


function displayRecords(){
    $("#tblTriggers").html("");
    $("#tblTriggers").append('<tr class="header">' +
                                '<th class="fldNumeric">Hide</th>' +
                                '<th class="fldChar">Description</th>' +
                                '<th class="fldButton" colspan="2">Re-Order</th>' +
                            '</tr>');
    
    for (i=0; i<jso.record.length; i++) {
        sid = jso.record[i].id;
        hid = jso.record[i].hidden;
        des = jso.record[i].description;
        srt = jso.record[i].sortorder;
        dro = (isAdmin ? "" : "readonly");
        buttonUp = "<button onclick='flipRecords(" + (i>0 ? i-1 : 0) + ", " + i + ")'>Up</button>";
        buttonDown = "<button onclick='flipRecords(" + i + ", " + (i+1 < jso.record.length ? i+1 : i) + ")'>Dn</button>";

        $("#tblTriggers").append("<tr id='row_" + i + "' class='row'></tr>");
        $("#row_"+i).append("<td><input type='checkbox' class='fldChk' id='chkHid' name='hidden' onclick='hideRecord("+i+")'" + (hid ? " checked " : "") + "></td>");
        $("#row_"+i).append("<td><input type='text' " + dro + " class='fldWide' id='txtDesc name='description' onblur='updateDesc("+i+",this)' value='" + des + "'></td>");
        $("#row_"+i).append("<td>" + (i == 0 ? "" : buttonUp) + "</td>");
        $("#row_"+i).append("<td>" + (i == jso.record.length-1 ? "" : buttonDown) + "</td>");
    }
}



