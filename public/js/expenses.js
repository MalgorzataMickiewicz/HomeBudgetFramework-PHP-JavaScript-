$(document).ready(function () {

    // checked limit for category
    var coll = document.getElementsByClassName('btn-submit');
    var j;
    for (j = 0; j < coll.length; j++) {
        coll[j].addEventListener('click', function () {
            // value
            var valueDiv = document.getElementById('valueExpense');
            var value = $(valueDiv).val();

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

                            if (status == 'success') {
                                // category without limit
                                if (result == 'nolimit') {
                                    // hide old communicat 'warning'
                                    if (!document.getElementById('communicat-high').classList.contains('communicat-limit-hide')) {
                                        document.getElementById('communicat-high').classList.add('communicat-limit-hide');
                                    }
                                    // hide old communicat 'validation'
                                    if (!document.getElementById('communicat-validation').classList.contains('communicat-limit-hide')) {
                                        document.getElementById('communicat-validation').classList.add('communicat-limit-hide');
                                    }
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
                                            // show alert 'success'
                                            if (document.getElementById('alert-success').classList.contains('communicat-limit-hide')) {
                                                document.getElementById('alert-success').classList.remove('communicat-limit-hide');
                                            }
                                        }
                                        // expense wasn't added to base
                                        else {
                                            // show alert 'warning'
                                            if (document.getElementById('alert-warning').classList.contains('communicat-limit-hide')) {
                                                document.getElementById('alert-warning').classList.remove('communicat-limit-hide');
                                            }
                                        }
                                    });
                                }
                                // limit wasn't exceeded and validation is empty
                                else if (result >= 0) {
                                    // hide old communicat 'warning'
                                    if (!document.getElementById('communicat-high').classList.contains('communicat-limit-hide')) {
                                        document.getElementById('communicat-high').classList.add('communicat-limit-hide');
                                    }
                                    // hide old communicat 'validation'
                                    if (!document.getElementById('communicat-validation').classList.contains('communicat-limit-hide')) {
                                        document.getElementById('communicat-validation').classList.add('communicat-limit-hide');
                                    }
                                    // show communicat 'success'
                                    document.getElementById('communicat-low').classList.remove('communicat-limit-hide');

                                    // add insert to communicat 'success'
                                    document.getElementById('communicat-low').insertAdjacentHTML('afterbegin', '<h3 class="my-4 h4" style="color: black!important; display: inline-block;">Limit dla tej kategorii wydatku nie został przekroczony. Możesz jeszcze dodać: ' + result + '.</h3>');

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
                                            // show alert 'success'
                                            if (document.getElementById('alert-success').classList.contains('communicat-limit-hide')) {
                                                document.getElementById('alert-success').classList.remove('communicat-limit-hide');
                                            }
                                        }
                                        // expense wasn't added to base
                                        else {
                                            // show alert 'warning'
                                            if (document.getElementById('alert-warning').classList.contains('communicat-limit-hide')) {
                                                document.getElementById('alert-warning').classList.remove('communicat-limit-hide');
                                            }
                                        }
                                    });
                                }
                                // limit was exceeded or validation isn't empty
                                else if (result < 0) {
                                    // validation isn't empty
                                    if (result == 'false') {
                                        // show communicato 'validation'
                                        if (document.getElementById('communicat-validation').classList.contains('communicat-limit-hide')) {
                                            document.getElementById('communicat-validation').classList.remove('communicat-limit-hide');
                                        }
                                        // hide communicat 'success'
                                        if (!document.getElementById('communicat-low').classList.contains('communicat-limit-hide')) {
                                            document.getElementById('communicat-low').classList.add('communicat-limit-hide');
                                        }
                                        // hide communicat 'warning'
                                        if (!document.getElementById('communicat-high').classList.remove('communicat-limit-hide')) {
                                            document.getElementById('communicat-high').classList.add('communicat-limit-hide');
                                        }
                                        // hide alert 'warninig'
                                        if (!document.getElementById('alert-warning').classList.contains('communicat-limit-hide')) {
                                            document.getElementById('alert-warinig').classList.add('communicat-limit-hide');
                                        }
                                        // hide alert 'success'
                                        if (!document.getElementById('alert-success').classList.contains('communicat-limit-hide')) {
                                            document.getElementById('alert-success').classList.add('communicat-limit-hide');
                                        }
                                    }
                                    // limit was exceeded
                                    else {
                                        // hide communicat 'success'
                                        if (!document.getElementById('communicat-low').classList.contains('communicat-limit-hide')) {
                                            document.getElementById('communicat-low').classList.add('communicat-limit-hide');
                                        }
                                        // hide communicat 'validation'
                                        if (!document.getElementById('communicat-validation').classList.contains('communicat-limit-hide')) {
                                            document.getElementById('communicat-validation').classList.add('communicat-limit-hide');
                                        }
                                        result = -result;
                                        // clean communicat 'warning'
                                        document.getElementById('communicat-high').innerHTML = '';

                                        // hide alert 'warninig'
                                        if (!document.getElementById('alert-warning').classList.contains('communicat-limit-hide')) {
                                            document.getElementById('alert-warinig').classList.add('communicat-limit-hide');
                                        }

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
                                                // show alert 'success'
                                                if (document.getElementById('alert-success').classList.contains('communicat-limit-hide')) {
                                                    document.getElementById('alert-success').classList.remove('communicat-limit-hide');
                                                }

                                                // show communicat 'warning'
                                                document.getElementById('communicat-high').classList.remove('communicat-limit-hide');

                                                // add insert to communicat 'warning'
                                                document.getElementById('communicat-high').insertAdjacentHTML('afterbegin', '<h3 class="my-4 h4" style="color: black!important; display: inline-block;">Uwaga, limit dla tej kategorii wydatku został przekroczony o ' + result + '.</h3>');
                                            }
                                            // expense wasn't added to base
                                            else {
                                                // show alert 'warning'
                                                if (document.getElementById('alert-warning').classList.contains('communicat-limit-hide')) {
                                                    document.getElementById('alert-warning').classList.remove('communicat-limit-hide');
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        })
                            ;
                    }
                }
            }
        });
    }
});