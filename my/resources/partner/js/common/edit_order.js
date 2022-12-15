$('#add_box1').click(function (e) {
    $('select').moveToListAndDelete('#from_box1', '#to_box');
    e.preventDefault();
});
$('#add_box2').click(function (e) {
    $('select').moveToListAndDelete('#from_box2', '#to_box');
    e.preventDefault();
});
$('#item_up').click(function (e) {
    $('select').moveUpDown('#to_box', true, false);
    e.preventDefault();
});
$('#item_down').click(function (e) {
    $('select').moveUpDown('#to_box', false, true);
    e.preventDefault();
});

function submit_hiddens() {
    $('#to_box option').each(function() {
        console.log(this.text + ' ' + this.value);
        $('<input>').attr({ type:'hidden', name:'selected[]', value:this.value }).appendTo('#hidden_items');
    });
    return true;
}
