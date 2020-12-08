function hideCommunicats() {
    // hide communicat about 0
    if (document.getElementById('communicat-zero').classList.contains('communicat-limit-hide')) {
    }
    else {
        document.getElementById('communicat-zero').classList.add('communicat-limit-hide');
    }

    //hide communicat about limit
    if (document.getElementById('communicat-low').classList.contains('communicat-limit-hide')) {
    }
    else {
        document.getElementById('communicat-low').classList.add('communicat-limit-hide');
    }

    if (document.getElementById('communicat-high').classList.contains('communicat-limit-hide')) {
    }
    else {
        document.getElementById('communicat-high').classList.add('communicat-limit-hide');
    }
}

function resultPlus(result) {
    // show communicat 'success'
    document.getElementById('communicat-low').classList.remove('communicat-limit-hide');
    // add insert to communicat 'success'
    document.getElementById('communicat-low').innerHTML = '<h3 class="my-4 h4" style="color: black!important; display: inline-block;">Limit dla tej kategorii wydatku nie został przekroczony. Możesz jeszcze dodać: ' + result + '.</h3>';
}

function resultMinus(result) {
    // show communicat 'warning'
    document.getElementById('communicat-high').classList.remove('communicat-limit-hide');
    // add insert to communicat 'warning'
    document.getElementById('communicat-high').innerHTML = '<h3 class="my-4 h4" style="color: black!important; display: inline-block;">Uwaga, limit dla tej kategorii wydatku został przekroczony o ' + result + '.</h3>';
}

function communicatZero() {
    if (document.getElementById('communicat-zero').classList.contains('communicat-limit-hide')) {
        document.getElementById('communicat-zero').classList.remove('communicat-limit-hide');
    }
}

function noDate() {
    document.getElementById('final-communicat').innerHTML = 'Nie posiadasz jeszcze żadnych danych</p>';
}

function communicatPlus() {
    document.getElementById('final-communicat').innerHTML = 'Gratulacje! Nie przekroczyłeś swojego budżetu';
}

function communicatMinus() {
    document.getElementById('final-communicat').innerHTML = 'Uwaga! Przekroczyłeś swój budżet';
}

//Income chart
var datasetValueIncome = [];
for (var j = 0; j < counterIncome; j++) {
    datasetValueIncome[j] = {
        y: tabValueIncome[j],
        indexLabel: tabNameIncome[j]
    }
}

window.onload = function () {

    var chart = new CanvasJS.Chart("chartContainerIncome",
        {
            animationEnabled: true,
            theme: "light2", // "light1", "light2", "dark1", "dark2"
            title: {
                text: "Przychody"
            },
            legend: {
                maxWidth: 350,
                itemWidth: 120
            },
            data: [
                {
                    type: "pie",
                    showInLegend: true,
                    legendText: "{indexLabel}",
                    dataPoints: [
                    ]
                }
            ]
        }
    );
    for (var l = 0; l < datasetValueIncome.length; l++) {
        chart.options.data[0].dataPoints.push(datasetValueIncome[l]);
    }
    chart.render();

    //Expense chart
    var datasetValueExpense = [];
    for (var n = 0; n < counterExpense; n++) {
        datasetValueExpense[n] = {
            y: tabValueExpense[n],
            indexLabel: tabNameExpense[n]
        }
    }
    var chart = new CanvasJS.Chart("chartContainerExpense", {
        animationEnabled: true,
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        title: {
            text: "Wydatki"
        },
        legend: {
            maxWidth: 350,
            itemWidth: 120
        },
        data: [
            {
                type: "pie",
                showInLegend: true,
                legendText: "{indexLabel}",
                dataPoints: [
                ]
            }
        ]
    }
    );

    for (var m = 0; m < datasetValueExpense.length; m++) {
        chart.options.data[0].dataPoints.push(datasetValueExpense[m]);
    }
    chart.render();

    //Column chart
    var chart = new CanvasJS.Chart("chartContainer", {

        animationEnabled: true,
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        title: {
            text: "Przychody vs Wydatki"
        },
        axisY: {
            title: "Kwota [zł]"
        },
        data: [{
            type: "column",
            showInLegend: true,
            legendMarkerColor: "grey",
            legendText: "Przychody vs Wydatki",
            dataPoints: [
            ]
        }]
    });

    var sumExpense = document.getElementById('expense-sum').textContent;
    var numberSumExpense = Number(sumExpense);

    var sumIncome = document.getElementById('income-sum').textContent;
    var numberSumIncome = Number(sumIncome);

    var datasetSum = [];
    datasetSum[0] = {
        y: numberSumIncome,
        label: 'Przychody'
    }
    datasetSum[1] = {
        y: numberSumExpense,
        label: 'Wydatki'
    }
    chart.options.data[0].dataPoints.push(datasetSum[0]);
    chart.options.data[0].dataPoints.push(datasetSum[1]);

    chart.render();
}

// edit button
var coll = document.getElementsByClassName('edit-btn');
var j;

for (j = 0; j < coll.length; j++) {
    coll[j].addEventListener('click', function () {
        var button = this;
        var date = button.getAttribute('buttondate');
        var categoryId = button.getAttribute('buttoncategoryid');
        var id = button.getAttribute('buttonid');
        var parent = this.parentElement;
        var td = parent.previousElementSibling;
        var currentValue = td.getAttribute('buttonvalue');
        var currentCategoryName = button.getAttribute('buttonname');

        hideCommunicats();

        // nonactive button
        if (!this.classList.contains('active')) {
            this.classList.toggle('active');
            td.innerHTML = '<input placeholder="' + currentValue + '" value="' + currentValue + '" class="form-control input-value" aria-label="' + currentCategoryName + '" aria-describedby="' + currentCategoryName + '" />';

            // change button 
            var elmnt = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-all" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992a.252.252 0 0 1 .02-.022zm-.92 5.14l.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486-.943 1.179z"/></svg>';
            button.childNodes[1].innerHTML = elmnt;
        }
        // active button
        else {
            this.classList.toggle("active");
            var input = td.firstElementChild;
            var newValue = $(input).val(); // get new input value
            newValue = newValue.toString();
            newValue = newValue.replace(',', '.');  
            newValue = parseFloat(newValue);
            newValue = newValue.toFixed(2);

            // new value different then 0
            if (newValue != 0) { 

                if (button.classList.contains('income-button')) {
                    td.innerHTML = newValue;
                    td.setAttribute('buttonvalue', newValue);

                    // change sum
                    var sumIncome = 0;
                    var k;
                    var coll2 = document.getElementsByClassName('values')
                    for (k = 0; k < coll2.length; k++) {
                        var name = coll2[k].getAttribute('name');
                        var check = coll2[k].childNodes[0].localName;
                        if ((name == 'income') && (check == 'input')) {
                            var value = coll2[k].childNodes[0].defaultValue
                            sumIncome = parseFloat(sumIncome) + parseFloat(value);
                        }
                        else if (name == 'income') {
                            var children = coll2[k].childNodes;
                            var value = children[0].nodeValue;
                            sumIncome = parseFloat(sumIncome) + parseFloat(value);
                        }
                    }
                    if (button.classList.contains('income-button')) {
                        document.getElementById('income-sum').innerHTML = sumIncome;
                        var sumInc = sumIncome;
                        var sumExp = document.getElementById('expense-sum').textContent;

                        if ((sumExp == 0) && (sumInc == 0)) {
                            noDate();
                        }
                        else if (sumExp <= sumInc) {
                            communicatPlus();
                        }
                        else {
                            communicatMinus();
                        }
                        // conect with data base
                        $.post('editIncome', {
                            newValue: newValue,
                            idIncome: id
                        }
                        );
                        columnChart();
                        incomeChart();
                        expenseChart();
                    }
                }
                else if (button.classList.contains('expense-button')) {
                    // check if category has limit
                    $.post('checkCategoryLimitEdit', {
                        categoryId: categoryId,
                        value: newValue,
                        date: date,
                        idExpense: id
                    }, function (data, status, xhr) {
                        var result = data;
                        // no limit
                        if (result == 'nolimit') {
                            td.setAttribute('buttonvalue', newValue);
                            td.innerHTML = newValue;
                            // change sum
                            var sumExpense1 = 0;
                            var i;
                            var coll6 = document.getElementsByClassName('values')
                            for (i = 0; i < coll6.length; i++) {
                                var name1 = coll6[i].getAttribute('name');
                                var check1 = coll6[i].childNodes[0].localName;
                                if ((name1 == 'expense') && (check1 == 'input')) {
                                    var value1 = coll6[i].childNodes[0].defaultValue
                                    sumExpense1 = parseFloat(sumExpense1) + parseFloat(value1);
                                    sumExpense1 = sumExpense1.toFixed(2);
                                }
                                else if (name1 == 'expense') {
                                    var children1 = coll6[i].childNodes;
                                    var value1 = children1[0].nodeValue;
                                    sumExpense1 = parseFloat(sumExpense1) + parseFloat(value1);
                                    sumExpense1 = sumExpense1.toFixed(2);
                                }
                            }
                            document.getElementById('expense-sum').innerHTML = sumExpense1;
                            var sumExp = sumExpense1;
                            var sumInc = document.getElementById('income-sum').textContent;

                            if ((sumExp == 0) && (sumInc == 0)) {
                                noDate();
                            }
                            else if (sumExp <= sumInc) {
                                communicatPlus();
                            }
                            else {
                                communicatMinus();
                            }
                            // conect with data base
                            $.post('editExpense', {
                                newValue: newValue,
                                idExpense: id
                            }
                            );
                            columnChart();
                            incomeChart();
                            expenseChart();
                        }
                        // limit wasn't exceeded and validation is empty
                        else if (result >= 0) {
                            td.setAttribute('buttonvalue', newValue);
                            td.innerHTML = newValue;
                            // change sum
                            var sumExpense = 0;
                            var k;
                            var coll2 = document.getElementsByClassName('values')
                            for (k = 0; k < coll2.length; k++) {
                                var name = coll2[k].getAttribute('name');
                                var check = coll2[k].childNodes[0].localName;
                                if ((name == 'expense') && (check == 'input')) {
                                    var value = coll2[k].childNodes[0].defaultValue
                                    sumExpense = parseFloat(sumExpense) + parseFloat(value);
                                    sumExpense = sumExpense.toFixed(2);
                                }
                                else if (name == 'expense') {
                                    var children = coll2[k].childNodes;
                                    var value = children[0].nodeValue;
                                    sumExpense = parseFloat(sumExpense) + parseFloat(value);
                                    sumExpense = sumExpense.toFixed(2);
                                }
                            }
                            document.getElementById('expense-sum').innerHTML = sumExpense;
                            var sumExp = sumExpense;
                            var sumInc = document.getElementById('income-sum').textContent;
                            if ((sumExp == 0) && (sumInc == 0)) {
                                noDate();
                            }
                            else if (sumExp <= sumInc) {
                                communicatPlus();
                            }
                            else {
                                communicatMinus();
                            }
                            // conect with data base
                            $.post('editExpense', {
                                newValue: newValue,
                                idExpense: id
                            }
                            );
                            resultPlus(result);

                            columnChart();
                            incomeChart();
                            expenseChart();
                        }
                        // limit was exceeded and validation is empty
                        else if (result < 0) {
                            result = -result;
                            td.setAttribute('buttonvalue', newValue);
                            td.innerHTML = newValue;
                            resultMinus(result);

                            td.setAttribute('buttonvalue', newValue);
                            td.innerHTML = newValue;
                            // change sum
                            var sumExpense = 0;
                            var k;
                            var coll2 = document.getElementsByClassName('values')
                            for (k = 0; k < coll2.length; k++) {
                                var name = coll2[k].getAttribute('name');
                                var check = coll2[k].childNodes[0].localName;
                                if ((name == 'expense') && (check == 'input')) {
                                    var value = coll2[k].childNodes[0].defaultValue
                                    sumExpense = parseFloat(sumExpense) + parseFloat(value);
                                    sumExpense = sumExpense.toFixed(2);
                                }
                                else if (name == 'expense') {
                                    var children = coll2[k].childNodes;
                                    var value = children[0].nodeValue;
                                    sumExpense = parseFloat(sumExpense) + parseFloat(value);
                                    sumExpense = sumExpense.toFixed(2);
                                }
                            }
                            document.getElementById('expense-sum').innerHTML = sumExpense;
                            var sumExp = sumExpense;
                            var sumInc = document.getElementById('income-sum').textContent;
                            if ((sumExp == 0) && (sumInc == 0)) {
                                noDate();
                            }
                            else if (sumExp <= sumInc) {
                                communicatPlus();
                            }
                            else {
                                communicatMinus();
                            }

                            // conect with data base
                            $.post('editExpense', {
                                newValue: newValue,
                                idExpense: id
                            }
                            );

                            columnChart();
                            incomeChart();
                            expenseChart();
                        }
                    });
                }
            }
            // new value eqaul 0
            else {
                var parent = button.parentElement;
                var previous = parent.previousElementSibling;
                td.innerHTML = previous.getAttribute('buttonvalue');
                communicatZero();
            }

            // change button 
            var elmnt = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pen" fill="currentColor" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" /> </svg>';
            var button = this.childNodes[1].innerHTML = elmnt;
        }
    });
}

//delete button
var coll3 = document.getElementsByClassName('delete-btn');
var l;
for (l = 0; l < coll3.length; l++) {
    coll3[l].addEventListener('click', function () {
        var idDelete = this.getAttribute('buttonid');
        var nameIncome = this.classList.contains('income-button');
        var nameExpense = this.classList.contains('expense-button');
        var parentButton = this.previousElementSibling;
        var parentTd = parentButton.parentElement;
        var parentTr = parentTd.parentElement;
        var buttonTest = this;

        var z;
        var coll4 = document.getElementsByClassName('delete-submit');
        for (z = 0; z < coll4.length; z++) {
            coll4[z].addEventListener('click', function () {

                if (nameIncome == true) {
                    // conect with data base
                    $.post('deleteIncome', {
                        idIncome: idDelete
                    }
                    );
                    parentTr.remove();
                }
                else if (nameExpense == true) {
                    // conect with data base
                    $.post('deleteExpense', {
                        idExpense: idDelete
                    }
                    );
                    parentTr.remove();
                }

                // change sum
                var sumIncomeDelete = 0;
                var sumExpenseDelete = 0;
                var t;
                var coll5 = document.getElementsByClassName('values')
                for (t = 0; t < coll5.length; t++) {
                    var nameDelete = coll5[t].getAttribute('name');
                    var checkDelete = coll5[t].childNodes[0].localName;

                    if ((nameDelete == 'income') && (checkDelete == 'input')) {
                        var valueDelete = coll5[t].childNodes[0].defaultValue
                        sumIncomeDelete = parseFloat(sumIncomeDelete) + parseFloat(valueDelete);
                    }
                    else if ((nameDelete == 'expense') && (checkDelete == 'input')) {
                        var valueDelete = coll5[t].childNodes[0].defaultValue
                        sumExpenseDelete = parseFloat(sumExpenseDelete) + parseFloat(valueDelete);
                        sumExpenseDelete = sumExpenseDelete.toFixed(2);
                    }
                    else if (nameDelete == 'income') {
                        var childrenDelete = coll5[t].childNodes;
                        var valueDelete = childrenDelete[0].nodeValue;
                        sumIncomeDelete = parseFloat(sumIncomeDelete) + parseFloat(valueDelete);
                    }
                    else if (nameDelete == 'expense') {
                        var childrenDelete = coll5[t].childNodes;
                        var valueDelete = childrenDelete[0].nodeValue;
                        sumExpenseDelete = parseFloat(sumExpenseDelete) + parseFloat(valueDelete);
                        sumExpenseDelete = sumExpenseDelete.toFixed(2);
                    }
                }
                if (buttonTest.classList.contains('income-button')) {
                    document.getElementById('income-sum').innerHTML = sumIncomeDelete;
                    var sumInc = sumIncomeDelete;
                    var sumExp = document.getElementById('expense-sum').textContent;
                    if ((sumExp == 0) && (sumInc == 0)) {
                        noDate();
                    }
                    else if (sumExp <= sumInc) {
                        communicatPlus();
                    }
                    else {
                        communicatMinus();
                    }
                }
                else if (buttonTest.classList.contains('expense-button')) {
                    document.getElementById('expense-sum').innerHTML = sumExpenseDelete;
                    var sumExp = sumExpenseDelete;
                    var sumInc = document.getElementById('income-sum').textContent;
                    if ((sumExp == 0) && (sumInc == 0)) {
                        noDate();
                    }
                    else if (sumExp <= sumInc) {
                        communicatPlus();
                    }
                    else {
                        communicatMinus();
                    }
                }
                incomeChart();
                expenseChart();
                columnChart();

            });
        }
    });
}

// JS chart, edit and delete incomes/expenses

//Column chart
function columnChart() {
    var chart = new CanvasJS.Chart("chartContainer", {

        animationEnabled: true,
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        title: {
            text: "Przychody vs Wydatki"
        },
        axisY: {
            title: "Kwota [zł]"
        },
        data: [{
            type: "column",
            showInLegend: true,
            legendMarkerColor: "grey",
            legendText: "Przychody vs Wydatki",
            dataPoints: [
            ]
        }]
    });

    var sumExpense = document.getElementById('expense-sum').textContent;
    var numberSumExpense = Number(sumExpense);

    var sumIncome = document.getElementById('income-sum').textContent;
    var numberSumIncome = Number(sumIncome);

    var datasetSum = [];
    datasetSum[0] = {
        y: numberSumIncome,
        label: 'Przychody'
    }
    datasetSum[1] = {
        y: numberSumExpense,
        label: 'Wydatki'
    }
    chart.options.data[0].dataPoints.push(datasetSum[0]);
    chart.options.data[0].dataPoints.push(datasetSum[1]);

    chart.render();
}

//Income chart
function incomeChart() {
    var counterIncomeNew = 0;
    var counterIncomeSameCategoryNew = 0;
    const tabValueIncomeNew = [];
    const tabNameIncomeNew = [];

    var k;
    var coll2 = document.getElementsByClassName('values')
    for (k = 0; k < coll2.length; k++) {
        var name = coll2[k].getAttribute('name');
        var check = coll2[k].childNodes[0].localName;
        var categoryName = coll2[k].previousElementSibling.textContent;
        if ((name == 'income') && (check == 'input')) {
            var value = coll2[k].childNodes[0].defaultValue;

        }
        else if (name == 'income') {
            var children = coll2[k].childNodes;
            var value = children[0].nodeValue;
        }
        if ((counterIncomeNew > 0) && (name == 'income')) {
            for (var q = 0; q < counterIncomeNew; q++) {
                if (categoryName == tabNameIncome[q]) {
                    tabValueIncomeNew[q] = Number(tabValueIncomeNew[q]) + Number(value);
                    counterIncomeSameCategoryNew = 1;
                }
            }
            if (counterIncomeSameCategoryNew == 0) {
                tabValueIncomeNew[counterIncomeNew] = value;
                tabNameIncomeNew[counterIncomeNew] = categoryName;
                counterIncomeNew++;
            }
            counterIncomeSameCategoryNew = 0;
        }
        else if (name == 'income') {
            tabValueIncomeNew[counterIncomeNew] = value;
            tabNameIncomeNew[counterIncomeNew] = categoryName;
            counterIncomeNew++;
        }
    }

    var datasetValueIncomeNew = [];
    for (var j = 0; j < counterIncomeNew; j++) {
        datasetValueIncomeNew[j] = {
            y: tabValueIncomeNew[j],
            indexLabel: tabNameIncomeNew[j]
        }
    }

    var chart = new CanvasJS.Chart("chartContainerIncome",
        {
            animationEnabled: true,
            theme: "light2", // "light1", "light2", "dark1", "dark2"
            title: {
                text: "Przychody"
            },
            legend: {
                maxWidth: 350,
                itemWidth: 120
            },
            data: [
                {
                    type: "pie",
                    showInLegend: true,
                    legendText: "{indexLabel}",
                    dataPoints: [
                    ]
                }
            ]
        }
    );
    for (var l = 0; l < datasetValueIncomeNew.length; l++) {
        chart.options.data[0].dataPoints.push(datasetValueIncomeNew[l]);
    }
    chart.render();

}

//Expense chart
function expenseChart() {
    var counterExpenseNew = 0;
    var counterExpenseSameCategoryNew = 0;
    const tabValueExpenseNew = [];
    const tabNameExpenseNew = [];

    var k;
    var coll2 = document.getElementsByClassName('values')
    for (k = 0; k < coll2.length; k++) {
        var name = coll2[k].getAttribute('name');
        var check = coll2[k].childNodes[0].localName;
        var categoryName = coll2[k].previousElementSibling.textContent;
        if ((name == 'expense') && (check == 'input')) {
            var value = coll2[k].childNodes[0].defaultValue;

        }
        else if (name == 'expense') {
            var children = coll2[k].childNodes;
            var value = children[0].nodeValue;
        }
        if ((counterExpenseNew > 0) && (name == 'expense')) {
            for (var q = 0; q < counterExpenseNew; q++) {
                if (categoryName == tabNameExpense[q]) {
                    tabValueExpenseNew[q] = Number(tabValueExpenseNew[q]) + Number(value);
                    counterExpenseSameCategoryNew = 1;
                }
            }
            if (counterExpenseSameCategoryNew == 0) {
                tabValueExpenseNew[counterExpenseNew] = value;
                tabNameExpenseNew[counterExpenseNew] = categoryName;
                counterExpenseNew++;
            }
            counterExpenseSameCategoryNew = 0;
        }
        else if (name == 'expense') {
            tabValueExpenseNew[counterExpenseNew] = value;
            tabNameExpenseNew[counterExpenseNew] = categoryName;
            counterExpenseNew++;
        }
    }

    var datasetValueExpenseNew = [];
    for (var n = 0; n < counterExpenseNew; n++) {
        datasetValueExpenseNew[n] = {
            y: tabValueExpenseNew[n],
            indexLabel: tabNameExpenseNew[n]
        }
    }

    var chart = new CanvasJS.Chart("chartContainerExpense", {
        animationEnabled: true,
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        title: {
            text: "Wydatki"
        },
        legend: {
            maxWidth: 350,
            itemWidth: 120
        },
        data: [
            {
                type: "pie",
                showInLegend: true,
                legendText: "{indexLabel}",
                dataPoints: [
                ]
            }
        ]
    }
    );

    for (var m = 0; m < datasetValueExpenseNew.length; m++) {
        chart.options.data[0].dataPoints.push(datasetValueExpenseNew[m]);
    }
    chart.render();
}