$(document).ready(function () {

    /**
     * Validate the form
     */
    var save = document.getElementById('btn-submit');
    save.addEventListener('click', function () {

        var value = document.getElementById('valueExpense').value;
        var date = document.getElementById('dateExpense').value;

        if ((value == 0) || (value == '' )) {
           document.getElementById('tip-value').setAttribute('data-tip', 'Wprowadź liczbę dodatnią, różną od zera');
        }
        else{
            document.getElementById('tip-value').removeAttribute('data-tip');
        }
        if(date == '') {
            document.getElementById('tip-date').setAttribute('data-tip', 'Wprowadź datę');
        }
        else{
            document.getElementById('tip-date').removeAttribute('data-tip');
        }
    });
});