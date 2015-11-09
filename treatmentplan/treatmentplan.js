google.load("visualization", "1.1", {"packages": ["line"], callback: refreshChart});

$(function() {
    
    refreshRenderValues(planjso);
    populateTreatmentList();
    
    $("#selTreatment").on("change", function(i, v) {
        populateDoseList(planjso, $("#selTreatment option:selected").val());
    });
    
    $("#selTreatment").val($("#selTreatment option:first").val());
    populateDoseList(planjso, $("#selTreatment option:selected").val());
    
    $("#btnAddDos").on("click", function(e) {
        addDosRecord(planjso);
    });
        
    $("#btnRefresh").on("click", function(e) {
        planjso = refreshRenderValues(planjso);
        $("#hidJSO").val(JSON.stringify(planjso));
        refreshChart(); 
    });
    
    
    $("#btnAddTre").on("click", function(e) {
        modalwin = getModal();
        modalwin.open({content: $("#modalDialog").html()}); 
        $("#modalcontent #cmdCancel").on("click", function() { modalwin.close(); });
        $("#modalcontent #cmdAdd").on("click", function() { 
            if ($("#modalcontent #selNewTre").val().length > 0) {
                addTreRecord($("#modalcontent #selNewTre option:selected").val(), 
                             $("#modalcontent #selNewTre option:selected").text());    
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
    
});
  
  
function refreshRenderValues(jso) {

    var mindate = new Date();
    var maxdate = new Date();
    mindate.setDate(mindate.getDate() - 1); 
    maxdate.setDate(maxdate.getDate() + 1);
    
    //loop through the jso treatments
    for (var t=0; t<jso.treatments.length; t++) { 
        
        var treatment = jso.treatments[t];
        var maxdose = 0;
        
        //loop through the doses
        for (var d=0; d<treatment.doses.length; d++) {
            var dose = treatment.doses[d];
            dose.totaldose = dose.dosage * dose.timesperday;
            
            maxdose = (dose.totaldose > maxdose) ? dose.totaldose : maxdose;
            
            var mid = new Date(dose.dfrom);
            var mad = new Date(dose.dto);
            mindate = (mid < mindate) ? mid : mindate;
            maxdate = (mad > maxdate) ? mad : maxdate;
        }
        
        for (var d=0; d<treatment.doses.length; d++) {
            var dose = treatment.doses[d];
            dose.maxdosevalue = maxdose;
            dose.rendervalue = (dose.totaldose / maxdose);
        }
            
    }
    
    mindate.setDate(mindate.getDate() - 1); 
    maxdate.setDate(maxdate.getDate() + 1);
    
    jso.mindate = mindate;
    jso.maxdate = maxdate;
    jso.dayspread = Math.round((maxdate - mindate)/(1000*60*60*24));
    
    //$("#rawJSO").text(JSON.stringify(planjso));
    return jso;
}


function prepareChartData(idata) {
    
    var idata = planjso; 
    var aData = new google.visualization.DataTable();
    aData.addColumn("string", "Day");
    
    var tlen = idata.treatments.length;
    
    for (var i=0; i<tlen; i++) {
        aData.addColumn("number", idata.treatments[i].name);
    }
    
    //for each day in the chart, add a data row with "tlen" columns
    for (var d=0; d<idata.dayspread; d++) {
        
        var diq = new Date(idata.mindate);
        diq.setDate(diq.getDate() + d);
        
        aRow = [diq.getMonth()+1 + "/" + diq.getDate()];
        
        //create the columns
        for (var c=0; c<tlen; c++) {
            //the value pushed to the column must be the return value
            //of a function that takes the date (dayspread + d) and the 
            //treatment index (c) and returns the rendervalue of that treatment 
            //on that date
            aRow.push( getRenderValue(idata, diq, c) );        
        }
        aData.addRows([aRow]);
    }
    
    //$("#rawJSO").text(JSON.stringify(aData));
    return aData;
}


function refreshChart() {
    var today = new Date();
    var cData = prepareChartData();
    
    var cOptions = {
            chart: {
              title: 'Treatment Plan Chart',
              subtitle: 'Today\'s Date: ' + (today.getMonth()+1) + "/" + today.getDate()
            },
            width: "100%",
            height: 300
          };

    var chart = new google.charts.Line($("#divChart")[0]);
    chart.draw(cData, cOptions);
}



function getRenderValue(idata, dayinquestion, treatmentindex) {

    for (var d=0; d<idata.treatments[treatmentindex].doses.length; d++) {
        var thisdose = idata.treatments[treatmentindex].doses[d];
        var dfrom = new Date(thisdose.dfrom);
        var dto = new Date(thisdose.dto);
        dto.setHours(23);
        dto.setMinutes(59);
        
        if (dayinquestion >= dfrom && dayinquestion <= dto) {
            return thisdose.rendervalue;   
        }
    }
    
    return 0;
}


function populateTreatmentList() {
    for (var t=0; t<planjso.treatments.length; t++) {
        var thisTre = planjso.treatments[t];
        $("#selTreatment").append($("<option/>", {value: thisTre.id, text: thisTre.name} ));   
    }
}


function populateDoseList(jso, doseid) {
    var oTable = $("#tblDosages");
    var oTableRows = $("#tblDosages tr[class != 'trHeader']");
    oTable.html($("#tblDosages tr[class = 'trHeader']"));
    
    
    $.each(jso.treatments, function(i, o) {
        if (o.id == doseid) {
            $.each(o.doses, function(ddi, ddo) {
                var fldSelect = "<input type='checkbox' id='chkSelect_" + ddi + "'></input>";
                var fldDfrom = "<input type='text' class='txtDate' id='txtDfrom_" + ddi + "' value='" + ddo.dfrom + "'></input>";
                var fldDto = "<input type='text' class='txtDate' id='txtDto_" + ddi + "' value='" + ddo.dto + "'></input>";
                var fldUnits = "<input type='text' class='txtText' id='txtUnits_" + ddi + "' value='" + ddo.units + "'></input>";
                var fldDosage = "<input type='text' class='txtInt' id='txtDosage_" + ddi + "' value='" + ddo.dosage + "'></input>";
                var fldXday = "<input type='text' class='txtInt' id='txtXday_" + ddi + "' value='" + ddo.timesperday + "'></input>";
                var fldDFCal = "<button type='button' id='btnDFCal_" + ddi + "' class='calpicker'>...</button>";
                var fldDTCal = "<button type='button' id='btnDTCal_" + ddi + "' class='calpicker'>...</button>";
                
                oTable.append("<tr><td>" + fldSelect + "</td>" + 
                              "<td class='tdleft'>" + fldDfrom + fldDFCal + "</td>" + 
                              "<td class='tdleft'>" + fldDto + fldDTCal + "</td>" + 
                              "<td>" + fldUnits + "</td>" + 
                              "<td>" + fldDosage + "</td>" + 
                              "<td>" + fldXday + "</td>" + 
                              "</tr>");
            });
        }
    });
    
    $(".txtDate").attr("pattern", "^[0-9]{4}-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1])$")
                 .attr("oninvalid", "setCustomValidity('Use format yyyy-mm-dd')");
    
    $(".txtText").attr("pattern", "^.{0,45}$")
                 .attr("oninvalid", "setCustomValidity('Maximum 45 characters')");
    
    $(".txtInt").attr("pattern", "^[0-9]{0,11}$")
                 .attr("oninvalid", "setCustomValidity('Maximum 11 whole numbers')");
    
    $(".calpicker").on("click", function(e) {
        var oCalElem = $("#divTempCal .dtpCalendar");
        if ($(oCalElem).is(":visible")){
            $(oCalElem).hide();
        } else {
            var dToday = new Date();
            var mytextbox = $(this).siblings("input");
            var tcal = new Chlog_Calendar("tc1", $(mytextbox));
            $("#divTempCal").html(tcal.renderMe());
            tcal.populateCalendar(dToday.getFullYear(), dToday.getMonth());
            if ($(mytextbox).val().length > 0) {
                tcal.highlightDate($(mytextbox).val());
            }
            tcal.setEventHandlers();
            $("#divTempCal .dtpCalendar")
                .css({
                    position: "absolute",
                    left: $(mytextbox).position().left,
                    top: $(mytextbox).position().top + $(mytextbox).outerHeight(),
                })
                .show();
        }
    });
    
    $("#tblDosages input").on("blur", function(e) {
        updateTreatmentList(jso);
        $("#hidJSO").val(JSON.stringify(jso));
    });
}


function updateTreatmentList(jso) {

    var selOpt = $("#selTreatment option:selected"); 
    var idTre = $(selOpt).val();
    var oTre = getTreatmentWithID(idTre, jso);
    
    if (!oTre) {
        oTre = {id: $(selOpt).val(), name: $(selOpt).text(), doses: []};
        planjso.treatments.push(oTre);
    }

    //oTre is now pointing at the treatment object shown on screen
    oTre.doses = [];
    
    $("#tblDosages tr[class!='trHeader']").each(function(i, o) {
        oTre.doses.push({dfrom: $("#txtDfrom_" + i).val(), 
                           dto: $("#txtDto_" + i).val(),
                           units: $("#txtUnits_" + i).val(),
                           dosage: $("#txtDosage_" + i).val(),
                           timesperday: $("#txtXday_" + i).val(),
                           totaldose: 0,
                           maxdosevalue: 0,
                           rendervalue: 0});
    });
}

function getTreatmentWithID(id, tjso) {
    var rtnval = false;
    $.each(tjso.treatments, function() {
        if ($(this).attr("id") == id) {
            rtnval = this;
        }
    });
    return rtnval;
}


function addTreRecord(id, name) {
    $("#selTreatment").append($("<option/>", {value: id, text: name}));
}


function addDosRecord(jso) {
    var idTre = $("#selTreatment option:selected").val(); 
    var oTre = getTreatmentWithID(idTre, jso);
    
    if (!oTre) {
        var selOpt = $("#selTreatment option:selected"); 
        oTre = {id: $(selOpt).val(), name: $(selOpt).text(), doses: []};
        jso.treatments.push(oTre);
    }
   
    if (oTre) {
        oTre.doses.push({dfrom: "", 
                           dto: "",
                           units: "",
                           dosage: "",
                           timesperday: "",
                           totaldose: 0,
                           maxdosevalue: 0,
                           rendervalue: 0});
    }
    
    populateDoseList(jso, idTre);
}