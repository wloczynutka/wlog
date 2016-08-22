$(document).ready(function(){
    $( "#carbundle_carfueling_litresTanked" ).keyup(function() {
        autoCalculateAmount();
    });
    $( "#carbundle_carfueling_pricePerLiter" ).keyup(function() {
        autoCalculateAmount();
    });
});

function autoCalculateAmount(){
    var pricePerLiter = parseFloat($('#carbundle_carfueling_pricePerLiter').val().replace(',', '.'));
    var litresTanked = parseFloat($('#carbundle_carfueling_litresTanked').val().replace(',', '.'));
    var amount =  pricePerLiter  * litresTanked;
    $('#carbundle_carfueling_amount').val(amount.toFixed(2));
}