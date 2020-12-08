window.onload = function () {
    
    var password = document.getElementById('inputPassword');
    password.addEventListener('change', function () {
        var value = this.value;
        var regexNumber = /\d/g;
        var regexLetter = /[a-z]/g;
        var regexLength = /^.{6,}/g;
        if(!regexNumber.test(value)){
            if(document.getElementById('digit').classList.contains('limit-hide')) {
            document.getElementById('digit').classList.remove('limit-hide');
            document.getElementById('digit').classList.add('limit-show')
            }
        }
        else {
            if(document.getElementById('digit').classList.contains('limit-show')) {
            document.getElementById('digit').classList.remove('limit-show');
            document.getElementById('digit').classList.add('limit-hide')
            }
        }
        if(!regexLetter.test(value)){
            if(document.getElementById('letter').classList.contains('limit-hide')) {
            document.getElementById('letter').classList.remove('limit-hide');
            document.getElementById('letter').classList.add('limit-show')
            }
        }
        else {
            if(document.getElementById('letter').classList.contains('limit-show')) {
            document.getElementById('letter').classList.remove('limit-show');
            document.getElementById('letter').classList.add('limit-hide')
            }
        }
        if(!regexLength.test(value)){
            if(document.getElementById('length').classList.contains('limit-hide')) {
            document.getElementById('length').classList.remove('limit-hide');
            document.getElementById('length').classList.add('limit-show')
            }
        }
        else {
            if(document.getElementById('length').classList.contains('limit-show')) {
            document.getElementById('length').classList.remove('limit-show');
            document.getElementById('length').classList.add('limit-hide')
            }
        }
    });
}