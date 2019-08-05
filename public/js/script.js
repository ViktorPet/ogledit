$(document).ready(function () {

    // Initializes navigation menu highlights.
    $('.nav > li > a').each(function () {
        if ($(this).prop('href') == window.location.href) {
            $(this).parents('li').addClass('active');
        }
    });

});

function loadJSON(url) {
    var request;

    // create the request
    if (window.XMLHttpRequest) {
        // IE7+, Firefox, Chrome, Opera, Safari
        request = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        request = new ActiveXObject('Microsoft.XMLHTTP');
    }

    // load it
    // the last "false" parameter ensures that our code will wait before the
    // data is loaded
    request.open('GET', url, false);
    request.send();

    // parse and return the output.
    return eval(request.responseText);
}

function confirmDelete(title) {
    $(document).on('click', '.confirm-delete', function () {
        var deleteURL = $(this).attr('value');
        console.log(deleteURL);
        swal({
            title: title,
//                text: "",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
        },
                function (isConfirm) {
                    if (isConfirm) {
                        window.location.assign(deleteURL);
                    }
                });

    });
}

function dataGrid(id, url, datafields, columns, exportUrl, updateData, showstatusbar, gridId, width, showheader, formatData, localization, virtualmode) {
    if (showheader !== false) {
        showheader = true;
    }
    if (showstatusbar !== true) {
        showstatusbar = false;
    }
    if (gridId === undefined) {
        gridId = "#jqxgrid";
    }
    if (width === undefined) {
        width = '100%';
    }
    if (virtualmode !== false) {
        virtualmode = true;
    }
    // prepare the data
    var theme = 'light';

    // Main page source and adapter.
    var source =
            {
                datatype: "json",
                datafields: datafields,
                id: id,
                url: url,
                cache: false,
                filter: function () {
                    // update the grid and send a request to the server.             
                    $(gridId).jqxGrid('updatebounddata', 'filter');
                },
                sort: function () {
                    // update the grid and send a request to the server.
                    $(gridId).jqxGrid('updatebounddata', 'sort');
                },
                root: 'Rows',
                beforeprocessing: function (data) {
                    if ((data != null) && (data[0])) {
                        source.totalrecords = data[0].TotalRows;
                    } else {
                        source.totalrecords = 0;
                    }
                },
            };

    var dataAdapter = new $.jqx.dataAdapter(source, {
        loadError: function (xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
        },
        formatData: formatData || $.noop
    }
    );

    var editrow = -1;

    var localizationobj = {};
    if (localization === undefined) {
        localizationobj.currencysymbol = "&pound;";
    } else
    {
        localizationobj.currencysymbol = localization;
    }
    localizationobj.decimalseparator = ".";
    localizationobj.thousandsseparator = ",";


    // initialize jqxGrid
    $(gridId).jqxGrid(
            {
//            editable: true,
                source: dataAdapter,
                theme: theme,
                filterable: true,
                sortable: true,
                autoheight: true,
                autorowheight: true,
                rowsheight: 40,
                pageable: true,
                pagerheight: 60,
                virtualmode: virtualmode,
                columnsresize: true,
                width: width,
                pagesizeoptions: ['20', '30', '50', '100', '200', '500'],
                pagesize: 20,
                localization: localizationobj,
                showaggregates: true,
                showstatusbar: showstatusbar,
                showheader: showheader,
                rendergridrows: function (obj) {
                    return obj.data;
                },
                columns: columns
            });

    $("#excelExport").click(function () {
        excelExport(gridId, exportUrl);
    });
}

function excelExport(gridId, exportUrl) {
    var filterGroups = $(gridId).jqxGrid('getfilterinformation');
    var pageSize = $(gridId).jqxGrid('pagesize');
    var pageInfo = $(gridId).jqxGrid('getpaginginformation');
    var pageNum = pageInfo["pagenum"];
    var sortInfo = $(gridId).jqxGrid('getsortinformation');
    var sortFieldName = sortInfo["sortcolumn"];

    if (typeof sortInfo.sortdirection != 'undefined') {
        var sortOrder;
        var sortOrderAsc = sortInfo.sortdirection["ascending"];

        if (sortOrderAsc == true) {
            sortOrder = 'asc';
        } else {
            sortOrder = 'desc';
        }
    }
    
    var groups = $(gridId).jqxGrid('getrootgroupscount');
    
    if (typeof sortFieldName == 'undefined' && typeof sortInfo.sortdirection == 'undefined') {
        var info = '?pagesize=' + pageSize + '&pagenum=' + pageNum;
    } else {
        var info = '?pagesize=' + pageSize + '&pagenum=' + pageNum + '&sortdatafield=' + sortFieldName + '&sortorder=' + sortOrder;
    }

    var filterscount = 0;
    for (var i = 0; i < filterGroups.length; i++) {
        var filterGroup = filterGroups[i];
        info += "&filterdatafield" + i + "=" + filterGroup.filtercolumn;
        info += '&' + filterGroup.filtercolumn + 'operator=' + filterGroup.filter.operator;

        var filters = filterGroup.filter.getfilters();
        for (var j = 0; j < filters.length; j++) {
            info += "&filtercondition" + i + "=" + filters[j].condition;
            info += "&filteroperator" + i + "=" + filters[j].operator;
            info += "&filtervalue" + i + "=" + filters[j].value;
        }

        filterscount++;
        info += '&filterscount=' + filterscount + '&groupscount=' + groups;
    }

    document.location.href = exportUrl + info;

}
