function checkPW(pw){
    let error = "";
    const regex = /^[a-zA-Z0-9\!\?\#\^\*]+$/;
    if (pw.trim() === ""){
        error += "Your password is empty. ";
    }else{
        if (!regex.test(pw)){
            error += "Your password contains unauthorized characters. ";
        }
        if (pw.length < 10 || pw.length > 20){
            error += "Your password does not meet the length requirement. ";
        }
    }
    return error;
}

function checkUN(un){
    let error = "";
    const regex = /^[a-zA-Z0-9]+$/;
    if (un.trim() === ""){
        error += "Your password is empty. ";
    }else{
        if (!regex.test(un)){
            error += "Your username contains unauthorized charcters. ";
        }
        if (un.length < 4 || un.length > 15){
            error += "Your username does not meet the length requirement. ";
        }
    }
    return error;
}

function checkName(fullname){
    let error = "";
    const regex = /^[a-zA-Z0-9 ]+$/;
    if (fullname.trim() === ""){
        error += "Your password is empty. ";
    }else{
        if (!regex.test(fullname)){
            error += "Your name contains unauthorized charcters. ";
        }
        if (fullname.length > 25){
            error += "Your name is too long. ";
        }
    }
    return error;
}

function checkEmail(email) {
    let error = "";
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        error = "Please enter a valid email address.";
    }
    return error;
}

function checkDescription(text) {
    var bol = true;
    const DesPattern = /^[a-zA-Z0-9.?_%+-:, ]+$/;
    if (!DesPattern.test(text)) {
        bol = false;
    }
    return [bol, "Can't contain any unusual characters such as:  \' , \" , \(\) "];
}