// Ajax Class
function myAjax( arguments, callback ) {
    this.xhr = new XMLHttpRequest();
    this.xhr.open( arguments.requestType, arguments.requestUrl  + arguments.requestParameters, true);
    this.xhr.onreadystatechange = function() {
        if( this.readyState === 4 && this.status === 200 ) {
            responseData = this.responseText;
            callback(responseData);
        }
    }
    this.xhr.send();
}

var btnParser = document.getElementById("parse");
btnParser.addEventListener('click', function() {
    var feedULR = document.getElementById("feedUrl").value;

    myReq = new myAjax({
        requestType: 'POST',
        requestUrl: http_base + '/parse/index/',
        requestParameters: ''
    }, function(data) {
        console.log(data);
    })

},false);