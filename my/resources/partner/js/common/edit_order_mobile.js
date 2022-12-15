$('#sortable').sortable()
$('#sortable').disableSelection()

function submit_hiddens() {
    $('#sortable').find('li').each(function(cnt, row) {
        $('<input>').attr({ type:'hidden', name:'selected[]', value:$(row).data('id') }).appendTo('#hidden_items');
    });
    return true;
}
