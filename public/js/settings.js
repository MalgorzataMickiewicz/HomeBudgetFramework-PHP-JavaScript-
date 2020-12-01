window.onload = function () {
        // category limit show/hide
        var coll = document.getElementsByClassName('limit');
        var i;
        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function () {
                if (!this.classList.contains('active')) {
                    // show limit
                    this.classList.toggle('active');
                    var nextElement = this.nextElementSibling;
                    var element = nextElement.nextElementSibling;
                    element.classList.add('limit-show');
                    element.classList.remove('limit-hide');
                }
                else {
                    // hide limit
                    this.classList.toggle('active');
                    var nextElement = this.nextElementSibling;
                    var element = nextElement.nextElementSibling;
                    element.classList.add('limit-hide');
                    element.classList.remove('limit-show');

                    // set empty value in input
                    var r2 = element.getElementsByTagName('input')[0].value = '';
                    
                    var checkbox = this.nextElementSibling;
                    var div = checkbox.nextElementSibling;
                    var child = div.childNodes;
                    var child2 = child[1].childNodes;
                    var input = child2[3];
                    var value = 0;
                    var categoryId = input.getAttribute('name');
                    if(input.classList.contains('expense-input')){
                        $.post('setExpenseLimit', {
                        categoryId: categoryId,
                        value: value
                    }
                    );
                    }
                }
            });
        } // end limit

        // category set limit
        var coll = document.getElementsByClassName('limit-btn');
        var l;
        for (l = 0; l < coll.length; l++) {
            coll[l].addEventListener("click", function () {
                var input = this.previousElementSibling;
                var value = $(input).val(); // get new input value
                var categoryId = input.getAttribute('name');
                var child = this.nextElementSibling;
                child.classList.remove('limit-hide');
                child.classList.add('show-limit');
                child.innerHTML = 'Limit został dodany';
                if(input.classList.contains('expense-input')) {
                    $.post('setExpenseLimit', {
                    categoryId: categoryId,
                    value: value
                }
                );
                }
            })
        } //end set category limit

        //edit button
        var coll = document.getElementsByClassName('edit-btn');
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener('click', function () {
                if (!this.classList.contains('active')) {
                    this.classList.toggle('active'); // toggle -> przełącznik. Jeśli posiada klasę to ją usuwa, jeśli jej nie posiada to ją dodaje (klasę 'active')
                    var input = this.previousElementSibling;
                    if (input.classList.contains('input-disabled')) {
                        // change input class  
                        input.classList.remove('input-disabled');

                        // change button 
                        var elmnt = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-all" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992a.252.252 0 0 1 .02-.022zm-.92 5.14l.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486-.943 1.179z"/></svg>';
                        var button = this.childNodes[1].innerHTML = elmnt;
                    }
                }
                else {
                    this.classList.toggle("active");
                    var input = this.previousElementSibling;
                    if (!input.classList.contains('input-disabled')) {
                        // change input class
                        input.classList.add('input-disabled');
                        // set new category name
                        input.classList.add('active-input');
                        $('input').each(function () {
                            if ($(this).hasClass('active-input')) {
                                var categoryName = $(this).val(); // get new input value
                                input.classList.remove('active-input');
                                var categoryId = input.getAttribute('id');
                                var oldCategory = input.getAttribute('name');
                                if (categoryName != oldCategory) {
                                    if (input.classList.contains('income-input')) {
                                        $.post('updateNewCategoryIncome', {
                                            newCategory: categoryName,
                                            newCategoryId: categoryId
                                        }
                                        );
                                    }
                                    else if (input.classList.contains('expense-input')) {
                                        $.post('updateNewCategoryExpense', {
                                            newCategory: categoryName,
                                            newCategoryId: categoryId
                                        }
                                        );
                                    }
                                    else if (input.classList.contains('pay-input')) {
                                        $.post('updateNewMethodPay', {
                                            newCategory: categoryName,
                                            newCategoryId: categoryId
                                        }
                                        );
                                    }
                                }
                            }
                        });

                        // change button 
                        var elmnt = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pen" fill="currentColor" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" /> </svg>';
                        var button = this.childNodes[1].innerHTML = elmnt;
                    }
                }
            });
        } // end edit

        //delete button
        var coll = document.getElementsByClassName('delete-btn');
        var j;
        for (j = 0; j < coll.length; j++) {
            coll[j].addEventListener('click', function () { 
                if(!this.classList.contains('active')){
                    this.classList.toggle('active');
                    var p = this.nextElementSibling;
                    var parent = this.parentElement;
                    parent.style.height = '140px';
                    p.classList.add('red');
                    p.classList.add('limit-show');
                    p.classList.remove('limit-hide');
                    var edit = this.previousElementSibling;
                    var input = edit.previousElementSibling;
                        if(input.classList.contains('income-input')){
                            p.innerHTML = 'Czy na pewno? Przychody z tej kategorii zostaną usunięte. Potwierdź klikając ponownie przycisk usunięcia.';
                        }
                        else if(input.classList.contains('expense-input')) {
                            p.innerHTML = 'Czy na pewno? Wydatki z tej kategorii zostaną usunięte. Potwierdź klikając ponownie przycisk usunięcia.';
                        }
                        else if(input.classList.contains('pay-input')) {
                            p.innerHTML = 'Czy na pewno? Wydatki z tą metodą płatności zostaną usunięte. Potwierdź klikając ponownie przycisk usunięcia.';
                        }
                    }
                    else {
                        this.classList.toggle('active');
                        var p = this.nextElementSibling;
                        var parent = this.parentElement;
                        parent.style.height = '90px';
                        p.innerHTML = 'Kategoria przychodu została usunięta.';
                        var edit = this.previousElementSibling;
                        var input = edit.previousElementSibling;
                        var categoryId = input.getAttribute('id');
                        if (input.classList.contains('income-input')) {
                            $.post('deleteCategoryIncome', {
                                categoryId: categoryId
                                }
                            );
                        }
                        else if(input.classList.contains('expense-input')){
                            $.post('deleteCategoryExpense', {
                                categoryId: categoryId
                                }
                            );
                        }
                        else if(input.classList.contains('pay-input')){
                            $.post('deleteMethodPay', {
                                categoryId: categoryId
                                }
                            );
                        }
                    }
            });
        } // end delete
    } //end ready 
