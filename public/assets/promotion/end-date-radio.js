var $endDate;

$(function () {
    $endDate = $('#end_date');

    $('input[type=radio][name=infinite]').change(onChangeInfinite);
});

function onChangeInfinite() {
    if (this.value == 1) {
        $endDate.slideUp();
    }
    else if (this.value == 0) {
        $endDate.slideDown();
    }
}
