function sendLog(text){
    $.post(siteurl + "/sitelog", { log: text },function( data ) {
        console.log(data);
      });
}

function enterPage(){
    var _text = '{action:"enter page"}';
    sendLog(_text);
}