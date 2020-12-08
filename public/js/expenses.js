function hideCommunicats() {
    // hide old communicat 'warning'
    if (!document.getElementById('communicat-high').classList.contains('communicat-limit-hide')) {
        document.getElementById('communicat-high').classList.add('communicat-limit-hide');
    }
    // hide old communicat 'validation'
    if (!document.getElementById('communicat-validation').classList.contains('communicat-limit-hide')) {
        document.getElementById('communicat-validation').classList.add('communicat-limit-hide');
    }
    // hide old communicat 'success'
    if (!document.getElementById('communicat-low').classList.contains('communicat-limit-hide')) {
        document.getElementById('communicat-low').classList.add('communicat-limit-hide');
    }
    // hide old alert 'warninig'
    if (!document.getElementById('alert-warning').classList.contains('communicat-limit-hide')) {
        document.getElementById('alert-warning').classList.add('communicat-limit-hide');
    }
    // hide old alert 'success'
    if (!document.getElementById('alert-success').classList.contains('communicat-limit-hide')) {
        document.getElementById('alert-success').classList.add('communicat-limit-hide');
    }
}

function resultPlus(result) {
    // show communicat 'success'
    document.getElementById('communicat-low').classList.remove('communicat-limit-hide');
    // add insert to communicat 'success'
    document.getElementById('communicat-low').innerHTML = '<h3 class="my-4 h4" style="color: black!important; display: inline-block;">Limit dla tej kategorii wydatku nie został przekroczony. Możesz jeszcze dodać: ' + result + '.</h3>';
}

function resultValidation() {
    // show communicat 'validation'
    if (document.getElementById('communicat-validation').classList.contains('communicat-limit-hide')) {
        document.getElementById('communicat-validation').classList.remove('communicat-limit-hide');
    }
}

function resultMinus(result) {
     // show communicat 'warning'
     document.getElementById('communicat-high').classList.remove('communicat-limit-hide');
     // add insert to communicat 'warning'
     document.getElementById('communicat-high').innerHTML = '<h3 class="my-4 h4" style="color: black!important; display: inline-block;">Uwaga, limit dla tej kategorii wydatku został przekroczony o ' + result + '.</h3>';
}

function alertSuccess() {
    // show alert 'success'
    if (document.getElementById('alert-success').classList.contains('communicat-limit-hide')) {
        document.getElementById('alert-success').classList.remove('communicat-limit-hide');
    }
}

function alertFalse() {
    // show alert 'warning'
    if (document.getElementById('alert-warning').classList.contains('communicat-limit-hide')) {
        document.getElementById('alert-warning').classList.remove('communicat-limit-hide');
    }
}

$(document).ready(function () {

    // checked limit for category ver. value
    var coll10 = document.getElementsByClassName('input-value');
    var e;
    for (e = 0; e < coll10.length; e++) {
        coll10[e].addEventListener('change', function () {
            // value
            var inputValue = this.value;
            if(inputValue != 0){
                inputValue = inputValue.toString();
                inputValue = inputValue.replace(',', '.');  
                if(!inputValue.includes('.')){
                    inputValue = inputValue + '.00';
                }
                inputValue = parseFloat(inputValue);
             }

            //categoryId
            var parent = this.parentElement;
            var dateInput = parent.nextElementSibling;
            var methodPayInput = dateInput.nextElementSibling;
            var categoryInput = methodPayInput.nextElementSibling;
            var categoriesChildren = categoryInput.children;
            var select = categoriesChildren[1];
            var number = select.childElementCount;
            var tab = select.children;

            //date
            var dateDiv = document.getElementById('dateExpense');
            var date = $(dateDiv).val();

            for (var r = 0; r < number; r++) {
                if (tab[r].localName == 'input') {
                    if (tab[r].checked == true) {
                        var checkedCategory = tab[r];
                        var categoryId = checkedCategory.getAttribute('id');
                        $.post('checkCategoryLimit', {
                            categoryId: categoryId,
                            value: inputValue,
                            date: date
                        }, function (data, status, xhr) {
                            var result = data;
                            // hide all communicats
                            hideCommunicats();

                            if (status == 'success') {
                                // category without limit
                                if (result == 'nolimit') {
                                }
                                // limit wasn't exceeded and validation is empty
                                else if (result >= 0) {
                                    result = parseFloat(result);
                                    result = result.toFixed(2);
                                    result = result.toString();
                                    result = result.replace('.', ',');
                                    resultPlus(result);
                                }
                                // limit was exceeded or validation isn't empty
                                else if (result < 0) {
                                    // validation isn't empty
                                    if (result == 'false') {
                                        resultValidation();
                                    }
                                    // limit was exceeded
                                    else {
                                        result = parseFloat(result);
                                        result = -result;
                                        result = result.toFixed(2);
                                        result = result.toString();
                                        result = result.replace('.', ',');
                                        resultMinus(result);
                                    }
                                }
                            }
                        });
                    }
                }
            }
        });
    }
     // checked limit for category ver. date
     var coll10 = document.getElementById('dateExpense');
         coll10.addEventListener('change', function () {

            //categoryId
             var parent = this.parentElement;
             var methodPayInput = parent.nextElementSibling;
             var categoryInput = methodPayInput.nextElementSibling;
             var categoriesChildren = categoryInput.children;
             var select = categoriesChildren[1];
             var number = select.childElementCount;
             var tab = select.children;

             // value
             var inputValue = document.getElementById('valueExpense').value;
             if(inputValue != 0){
                inputValue = inputValue.toString();
                inputValue = inputValue.replace(',', '.');  
                if(!inputValue.includes('.')){
                    inputValue = inputValue + '.00';
                }
                inputValue = parseFloat(inputValue);
             }

 
             //date
             var dateDiv = document.getElementById('dateExpense');
             var date = $(dateDiv).val();

             for (var r = 0; r < number; r++) {
                if (tab[r].localName == 'input') {
                    if (tab[r].checked == true) {
                        var checkedCategory = tab[r];
                        var categoryId = checkedCategory.getAttribute('id');
                        $.post('checkCategoryLimit', {
                            categoryId: categoryId,
                            value: inputValue,
                            date: date
                        }, function (data, status, xhr) {
                            var result = data;
                            // hide all communicats
                            hideCommunicats();

                            if (status == 'success') {
                                // category without limit
                                if (result == 'nolimit') {
                                }
                                // limit wasn't exceeded and validation is empty
                                else if (result >= 0) {
                                    result = parseFloat(result);
                                    result = result.toFixed(2);
                                    result = result.toString();
                                    result = result.replace('.', ',');
                                    resultPlus(result);
                                }
                                // limit was exceeded or validation isn't empty
                                else if (result < 0) {
                                    // validation isn't empty
                                    if (result == 'false') {
                                      resultValidation();
                                    }
                                    // limit was exceeded
                                    else {
                                        result = parseFloat(result);
                                        result = -result;
                                        result = result.toFixed(2);
                                        result = result.toString();
                                        result = result.replace('.', ',');
                                        resultMinus(result);
                                    }
                                }
                            }
                        });
                    }
                }
            }
        });

    // checked limit for category ver. category
     var coll = document.getElementsByClassName('categories');
     var j;
     for (j = 0; j < coll.length; j++) {
         coll[j].addEventListener('click', function () {
             //categoryId
            var categoryId = this.getAttribute('id');
            
            //value
            var inputValue = document.getElementById('valueExpense').value;
            if(inputValue != 0){
                inputValue = inputValue.toString();
                inputValue = inputValue.replace(',', '.');  
                if(!inputValue.includes('.')){
                    inputValue = inputValue + '.00';
                }
                inputValue = parseFloat(inputValue);
             }


            //date
            var dateDiv = document.getElementById('dateExpense');
            var date = $(dateDiv).val();

            $.post('checkCategoryLimit', {
                categoryId: categoryId,
                value: inputValue,
                date: date
            }, function (data, status, xhr) {
                var result = data;
                // hide all communicats
                hideCommunicats();

                if (status == 'success') {
                    // category without limit
                    if (result == 'nolimit') {
                    }
                    // limit wasn't exceeded and validation is empty
                    else if (result >= 0) {
                        result = parseFloat(result);
                        result = result.toFixed(2);
                        result = result.toString();
                        result = result.replace('.', ',');
                        resultPlus(result);
                    }
                    // limit was exceeded or validation isn't empty
                    else if (result < 0) {
                        // validation isn't empty
                        if (result == 'false') {
                            resultValidation();
                        }
                        // limit was exceeded
                        else {
                            result = parseFloat(result);
                            result = -result;
                            result = result.toFixed(2);
                            result = result.toString();
                            result = result.replace('.', ',');
                            resultMinus(result);
                        }
                    }
                }
            });
        });
    }
              
    // submit
    var coll = document.getElementsByClassName('btn-submit');
    var j;
    for (j = 0; j < coll.length; j++) {
        coll[j].addEventListener('click', function () {
            hideCommunicats();
            // value
            var value = document.getElementById('valueExpense').value;
            if(value != 0){
                value = value.toString();
                value = value.replace(',', '.');  
                value = parseFloat(value);
                value = value.toFixed(2);
             }

            // date
            var dateDiv = document.getElementById('dateExpense');
            var date = $(dateDiv).val();

            //methodPay
            var methodPayDiv = document.getElementsByClassName('method-pay');
            var numberOfElement = methodPayDiv[0].childElementCount;
            var tab = methodPayDiv[0].children;
            for (var b = 0; b < numberOfElement; b++) {
                if (tab[b].localName == 'input') {
                    if (tab[b].checked == true) {
                        var methodPay = tab[b].getAttribute('id');
                    }
                }
            }

            //comment
            var commentDiv = document.getElementById('comment');
            var commentExpense = $(commentDiv).val();

            //categoryId
            var parent = this.parentElement;
            var comment = parent.previousElementSibling;
            var category = comment.previousElementSibling;
            var categories = category.children;
            var select = categories[1];
            var number = select.childElementCount;
            var tab = select.children;

            for (var a = 0; a < number; a++) {
                if (tab[a].localName == 'input') {
                    if (tab[a].checked == true) {
                        var checkedCategory = tab[a];
                        var categoryId = checkedCategory.getAttribute('id');
                        $.post('checkCategoryLimit', {
                            categoryId: categoryId,
                            value: value,
                            date: date
                        }, function (data, status, xhr) {
                            var result = data;
                            hideCommunicats();
                            if (status == 'success') {
                                // category without limit
                                if (result == 'nolimit') {
                                    console.log(value);
                                      // add expense to base
                                      $.post('saveExpense', {
                                        categoryExpense: categoryId,
                                        payMethodExpense: methodPay,
                                        valueExpense: value,
                                        dateExpense: date,
                                        commentExpense: commentExpense
                                    }, function (data, status, xhr) {
                                        var response = data;
                                        // expense was added to base
                                        if (response == 1) {
                                            alertSuccess();
                                        }
                                        // expense wasn't added to base
                                        else {
                                            alertFalse();
                                            resultValidation();
                                        }
                                    });
                                }
                                // limit wasn't exceeded and validation is empty
                                else if (result >= 0) {
                                    result = parseFloat(result);
                                    result = result.toFixed(2);
                                    result = result.toString();
                                    result = result.replace('.', ',');
                                    resultPlus(result);
        
                                    // add expense to base
                                    $.post('saveExpense', {
                                        categoryExpense: categoryId,
                                        payMethodExpense: methodPay,
                                        valueExpense: value,
                                        dateExpense: date,
                                        commentExpense: commentExpense
                                    }, function (data, status, xhr) {
                                        var response = data;
                                        // expense was added to base
                                        if (response == 1) {
                                            alertSuccess();
                                        }
                                        // expense wasn't added to base
                                        else {
                                            alertFalse();
                                            resultValidation();
                                        }
                                    });
                                }
                                // limit was exceeded or validation isn't empty
                                else if (result < 0) {
                                    // validation isn't empty
                                    if (result == 'false') {
                                        resultValidation();
                                    }
                                    // limit was exceeded
                                    else {
                                        // add expense to database
                                        $.post('saveExpense', {
                                            categoryExpense: categoryId,
                                            payMethodExpense: methodPay,
                                            valueExpense: value,
                                            dateExpense: date,
                                            commentExpense: commentExpense
                                        }, function (data, status, xhr) {
                                            var response = data;
                                            // expense was added to base
                                            if (response == 1) {
                                                alertSuccess();
                                                result = parseFloat(result);
                                                result = -result;
                                                result = result.toFixed(2);
                                                result = result.toString();
                                                result = result.replace('.', ',');
                                                resultMinus(result);
                                            }
                                            // expense wasn't added to base
                                            else {
                                                resultValidation();
                                                alertFalse();
                                            }
                                        });
                                    }
                                }
                                // validation isn't empty
                                else if (result == 'false') {
                                    resultValidation();
                                    alertFalse();
                                }
                            }
                            else {
                                resultValidation();
                                alertFalse();
                            }
                        });
                    }
                }
            }
        });
    }
});
