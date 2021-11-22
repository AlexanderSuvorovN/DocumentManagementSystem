var table;
$(function()
{
    var filter = {};
    var pagination = {};
    window.addEventListener(
        "pageshow",
        function(e)
        {
            if(e.persisted)
            {
                // console.log("reloading table...");
                table.setData();
            }
        });
    table = new Tabulator(
        "#career-opportunities-table", 
        {
            ajaxURL: "./ajax-career-opportunities",
            ajaxFiltering: true,
            ajaxSorting: true,
            placeholder: "No data set",
            layout: "fitColumns",
            pagination: "remote",
            paginationSize: 50, //allow 7 rows per page of data
            movableColumns: true, //allow column order to be changed
            persistenceMode: true,
            persistentLayout: false,
            persistentSort: true,
            persistentFilter: true,
            paginationSizeSelector: true,
            columns:
                [                 //define the table columns
                    {title:"Id", field:"id", align:"right", sorter: "number", widthGrow: 1},
                    {title:"Role", field:"role", align: "left", sorter: "string", widthGrow: 5},
                    {title:"Seniority", field:"seniority", align: "left", sorter: "string", widthGrow: 2},
                    {title:"Location", field:"location", align: "right", sorter: "string", widthGrow: 2}
                ],
            rowClick: 
                function(e, row)
                {
                    let opportunity_id = row.getData().id;
                    window.location.href = "./view?id="+opportunity_id;
                },
        });
    // console.log("careers.js ready complete.")
});
