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
