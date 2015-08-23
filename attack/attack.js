//onload
$(function() {
    resetKipShading();
    
    $("#kipscale td").each(function() {
        $(this).on("click", function() {
            $("#rngLevel").val($(this).text());
            resetKipShading();
        });
    });

    $(".chkTreatment").each(function() {
        toggleTreatmentXtra($(this));
    });
    
    $(".chkcol input[type='checkbox']").on("click", function() {
        toggleTreatmentXtra($(this));
    });

    //CODE TO POPULATE TREATMENTS USING VARIABLE jsoTre
    renderAllTreatments();
    
    
    $("#cmdAddTreatment").on("click", function() {
        jsoTre.record.push({id: 3, description: "Ibuprofen", preparation: "Tablet", dosage: "2x", administered: ""});
        renderAllTreatments();
    });
    
    
});


function resetKipShading() {
    $("#kipscale td").each(function() {
        $(this).css("background-color", "rgba(255, 0, 0, " + ($(this).text() * 0.1) + ")"); 
        $(this).css("color", "#000"); 
        
        if ($(this).text() == $("#rngLevel").val()) {
            $(this).css("background-color", "#3a515f");
            $(this).css("color", "#ede859");
        }
    });
}
                           

function renderAllTreatments() {
    $("#tblTreatments").html("");
    for (var i=0; i<jsoTre.record.length; i++) {
        renderTreatment("#tblTreatments", jsoTre.record[i], i);
    }
    $(".dropdownlink").on("click", function() {
        $(this).siblings(".dropdownrows").toggle();
    });
    $(".remTre").on("click", function() {
        var myId = $(this).attr("rmid");
        window.alert("removing treatment #" + myId + " for attack #" + $("#txtID").val()); 
    });
}

function renderTreatment(eTable, oRec, seq) {
    var row = '<tr><td class="treatmentcell"><label for="selTre_' + seq + '">Treatment</label>' + 
                    '<select id="selTre_' + seq + '" name="selTre_' + seq + '">'+ renderTreatmentTypes(oRec.id) + '</select>' + 
                    '<span class="dropdownlink">More >>></span>' + 
                    '<div class="dropdownrows">' +
                    '<label for="selPre_' + seq + '">Preparation</label>' + 
                    '<select id="selPre_' + seq + '" name="selPre_' + seq + '">' + renderPreparationTypes(oRec.preparation) + '</select>'+
                    '<label for="txtDos_' + seq + '">Dosage</label>' + 
                    '<input type="text" id="txtDos_' + seq + '" name="txtDos_' + seq + '" class="fldWidest" value="' + oRec.dosage + '">'+
                    '<label for="txtAdm_' + seq + '">Administered</label>' + 
                    '<input type="text" id="txtAdm_' + seq + '" name="txtAdm_' + seq + '" value="' + oRec.administered + '">'+
                    '<button type="button" id="cmdRemoveTre_' + seq + '" rmid="' + oRec.id + '" class="remTre">Remove</button>' +
                    '</div>';
    
    $(eTable).append(row);
}


function renderTreatmentTypes(selectedval) {
    var rtnVal = "";
    for (var i=0; i<jsoTlist.record.length; i++) {
        var oList = jsoTlist.record[i];
        var seltext = (oList.id == selectedval ? " selected " : "");
        rtnVal += '<option value="' + oList.id + '"' + seltext + '>' + oList.description + '</option>'; 
    }
    return rtnVal;
}


function renderPreparationTypes(selectedval) {
    var rtnVal = "";
    var pTypes = ["Tablet", "Injection", "Spray", "Gas"];
    for (var i=0; i<pTypes.length; i++) {
        var seltext = (pTypes[i] == selectedval ? " selected " : "");
        rtnVal += '<option value="' + pTypes[i] + '"' + seltext + '>' + pTypes[i] + '</option>'; 
    }
    return rtnVal;
}