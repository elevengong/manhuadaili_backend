/**************  验证方法 *************/

function isUrl(str){
    var p = /^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/;
    return baseFun( str, p );
}
function isChinese(str){
    var p = /^[\u4e00-\u9fa5]+$/;
    return baseFun( str, p );
}
function isEnglish(str){
    var p = /^[A-Za-z0-9 ]+$/;
    return baseFun( str, p );
}
function isInt(str){
    var p = /^[0-9]*[0-9][0-9]*$/;
    return baseFun( str, p );
}
function isDatetime(str){
    var p = /^[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s+(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/;
    return baseFun( str, p );
}
function isDate(str){
    var p = /^[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\+$/;
    return baseFun( str, p );
}
function isUname_(str){
    var p = /^[a-zA-Z0-9]+$/;
    return baseFun( str, p );
}
function isUname(str){
    var p = /^[a-zA-Z0-9]+$/;
    return baseFun( str, p );
}
function isEmail(str){
    var p = /^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/;
    return baseFun( str, p );
}
function isTel(str){
    var p = /^[1][3,4,5,7,8][0-9]{9}$/;
    return baseFun( str, p );
}
function isQQ(str){
    var p = /^[1-9][0-9]{4,9}$/;
    return baseFun( str, p );
}
function isIP(str){
    var p = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
    return baseFun(str, p);
}

function baseFun(str,p){
    if (str == '') {
        return false;
    };
    return p.test(str);
}