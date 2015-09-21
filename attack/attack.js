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
        jsoTre.record.push({id: jsoTlist.record[0].id, description: "", preparation: "", 
                            dosage: "", administered: $("#dtp1_txtFullDateTime").val()});
        renderAllTreatments();
    });
    
    $("#cmdDelete").on("click", function(e) {
        if (!window.confirm("Are you sure you want to delete this attack record?")) {
            e.preventDefault();
        }
    });
    
    //size boxes
    resizeAttackGroups(); 
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
        $(this).text( ($(this).text() == "More >>>" ? "Less <<<" : "More >>>") );
    });
    $(".remTre").on("click", function() {
        if (window.confirm("Are you sure you want to remove this treatment?")) {
            var mySeq = $(this).attr("myseq");
            jsoTre.record.splice(mySeq, 1);
            renderAllTreatments();
        }
    });
}

function renderTreatment(eTable, oRec, seq) {
    var row = '<tr><td class="treatmentcell">' + 
                    '<select id="selTre_' + seq + '" name="selTre_' + seq + '" myseq="' + seq + ' " class="selTre">'+ renderTreatmentTypes(oRec.id) + '</select>' + 
                    '<span class="dropdownlink">More >>></span>' + 
                    '<div class="dropdownrows">' +
                    '<label for="selPre_' + seq + '">Preparation</label>' + 
                    '<select id="selPre_' + seq + '" name="selPre_' + seq + '">' + renderPreparationTypes(oRec.preparation) + '</select>'+
                    '<label for="txtDos_' + seq + '">Dosage</label>' + 
                    '<input type="text" id="txtDos_' + seq + '" name="txtDos_' + seq + '" class="fldWidest" value="' + oRec.dosage + '">'+
                    '<label for="txtAdm_' + seq + '">Administered</label>' + 
                    '<input type="text" id="txtAdm_' + seq + '" name="txtAdm_' + seq + '" class="fldWidest" value="' + oRec.administered + '">'+
                    '<input type="hidden" id="txtTreSeq_' + seq + '" name="txtTreSeq[]" class="fldWidest" value="' + seq + '">'+
                    '<button type="button" id="cmdRemoveTre_' + seq + '" myseq="' + seq + '" class="remTre">Remove</button>' +
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
    var pTypes = ["Injection", "Spray", "Gas", "Tablet", "Liquid"];
    for (var i=0; i<pTypes.length; i++) {
        var seltext = (pTypes[i] == selectedval ? " selected " : "");
        rtnVal += '<option value="' + pTypes[i] + '"' + seltext + '>' + pTypes[i] + '</option>'; 
    }
    return rtnVal;
}


function resizeAttackGroups() {
    var TrigH = $("#tblTriggers").parent().height();
    var LocH = $("#tblLocations").parent().height();
    
    if (TrigH > LocH) {
        $("#tblLocations").parent().height(TrigH+1);   
    } else {
        $("#tblTriggers").parent().height(LocH-1);   
    }
    
    
    
}