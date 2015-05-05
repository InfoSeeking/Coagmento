//2-21-13: DEPRECATED.  Replaced with jQuery validation
function isCheckboxSelected(checkbox, obj) {
    if (checkbox.checked) {
        document.getElementById(obj).style.backgroundColor = okColor;
        return true;
    }

    document.getElementById(obj).style.backgroundColor = alertColor;

    return false;
}



function validateField(field) {
    if (field.value == "") {
        changeColor(field, alertColor);
        return false;
    } else {
        changeColor(field, okColor);
        return true;
    }
}

function validateSelectField(field) {

    if (field.value == "") {
        changeColor(field, alertColor);
        return false;
    } else {
        changeColor(field, okColor);
        return true;
    }
}

function isRadioSelected(radioButtons, obj) {

    for (i = radioButtons.length - 1; i > -1; i--) {
        if (radioButtons[i].checked) {
            document.getElementById(obj).style.backgroundColor = okColor;
            return true;
        }
    }

    document.getElementById(obj).style.backgroundColor = alertColor;

    return false;
}

function radioSelectedValue(radioButtons) {

    for (i = radioButtons.length - 1; i > -1; i--) {
        if (radioButtons[i].checked) {
            // alert(radioButtons[i].value);
            return radioButtons[i].value;
        }
    }

    return false;
}

function showHideRadio(radioButtons, showdiv) {
    for (i = radioButtons.length - 1; i > -1; i--) {
        if (radioButtons[i].checked) {
            if (radioButtons[i].value == "Yes") {
                document.getElementById(showdiv).style.display = "block";
            } else if (radioButtons[i].value == "No") {
                document.getElementById(showdiv).style.display = "none";
            }
        }
    }
}

function validateEmail(field1, field2) {
    if (field1.value != field2.value) {
        changeColor(field1, alertColor);
        changeColor(field2, alertColor);
        return false;
    } else
    if (!isValidadEmail(field1.value)) {
        changeColor(field1, alertColor);
        changeColor(field2, alertColor);
        return false;
    } else {
        changeColor(field1, okColor);
        changeColor(field2, okColor);
        return true;
    }
}

function validateEmail2(field1) {
    if ((field1.value.length != 0) && !isValidadEmail(field1.value)) {
        changeColor(field1, alertColor);
        return false;
    } else {
        changeColor(field1, okColor);
        return true;
    }
}

function changeColor(field, color) {
    field.style.background = color;
}

function isValidadEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validatePwd(field1, field2) {
    if (field1.value != field2.value || field1.value.length < 1) {
        changeColor(field1, alertColor);
        changeColor(field2, alertColor);
        return false;
    } else {
        changeColor(field1, okColor);
        changeColor(field2, okColor);
        return true;
    }
}

function isRankedOrderValid(divid) {
    var inputs = document.getElementById(divid).getElementsByTagName('input');
    var count = 0;
    for (i = inputs.length - 1; i > -1; i--) {
        if (inputs[i].value != "") {
            count += 1;
        }
    }

    if (count != 3) {
        return false;
    }

    for (i = inputs.length - 1; i > -1; i--) {
        for (j = i - 1; j > -1; j--) {
            if (inputs[i].value == inputs[j].value && inputs[i].value != "" && inputs[i].value != "") {
                return false;
            }
        }
    }

    return true;

}
