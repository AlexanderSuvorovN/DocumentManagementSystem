$(function()
{
    let news_table_container = $("#news-table-container");
    function displayTable()
    {
        $.post('news-ajax.php', {}, "html")
            .done(
                function(data)
                {
                    news_table_container.html(data);
                    let query = news_table_container.find("input[type='hidden'][name='query']").val().trim();
                    let records_count = news_table_container.find("input[type='hidden'][name='records_count']").val().trim();
                    $("#records_count").html(records_count + " records found");
                });
    }
    displayTable();
});