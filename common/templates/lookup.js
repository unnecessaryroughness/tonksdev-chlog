function flipRecords(r_a, r_b) {
    if (r_b - r_a != 1) {
        return false;
    }
    jso.record.splice(r_a, 0, jso.record[r_b]);
    jso.record.splice(r_b+1, 1);
    $("#jsoString").val(JSON.stringify(jso));
    displayRecords();   
}

function hideRecord(r) {
    jso.record[r].hidden = ! jso.record[r].hidden;
    $("#jsoString").val(JSON.stringify(jso));
    displayRecords();
}
    
function updateDesc(r, dfld) {
    jso.record[r].description = $(dfld).val();     
    $("#jsoString").val(JSON.stringify(jso));
    displayRecords();
}

function maxSort() {
    var max=0;
    for (i=0; i<jso.record.length; i++) {
        if (jso.record[i].sortorder > max) {
            max = jso.record[i].sortorder;   
        }
    }
    return max+1;
}

function addRecord(desc) {
    jso.record.push({id: 0, description: desc, originaldescription: desc, 
                    sortorder: maxSort(), hidden: 0, sequence: 999});
    $("#jsoString").val(JSON.stringify(jso));
    displayRecords();
}


$(function(){    
    $("#cmdNew").on("click", function() { 
        modalwin = getModal();
        modalwin.open({content: $("#modalDialog").html()}); 
        $("#modalcontent #cmdCancel").on("click", function() { modalwin.close(); });
        $("#modalcontent #cmdAdd").on("click", function() { 
            if ($("#modalcontent #txtNewRecord").val().length > 0) {
                addRecord($("#modalcontent #txtNewRecord").val());    
            }
            modalwin.close();
        });
        $("#modalcontent #txtNewRecord").focus();
        $("#modalcontent #txtNewRecord").keypress(function(e){
            switch (e.keyCode) {
                case 13:
                    $("#modalcontent #cmdAdd").click();   
                    break;
                case 27:
                    $("#modalcontent #cmdCancel").click();
                    break;
                default:
                    break;
            }
        });
    });
    
    displayRecords();
});


function displayRecords(){
    $("#tblLookups").html("");
    $("#tblLookups").append('<tr class="header">' +
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

        $("#tblLookups").append("<tr id='row_" + i + "' class='row'></tr>");
        $("#row_"+i).append("<td><input type='checkbox' class='fldChk' id='chkHid' name='hidden' onclick='hideRecord("+i+")'" + (hid ? " checked " : "") + "></td>");
        $("#row_"+i).append("<td><input type='text' " + dro + " class='fldWide' id='txtDesc name='description' onblur='updateDesc("+i+",this)' value='" + des + "'></td>");
        $("#row_"+i).append("<td>" + (i == 0 ? "" : buttonUp) + "</td>");
        $("#row_"+i).append("<td>" + (i == jso.record.length-1 ? "" : buttonDown) + "</td>");
    }
}



