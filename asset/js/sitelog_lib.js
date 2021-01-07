function sendLog(messege){
    var data = {
        log:messege
    };
    var url = siteurl + "/sitelog"; 
    $.post(url, data,function( res ) {
        console.log(res);
      });    
}

/*
 * enter event
 */
function enterPage(){
    var url = window.location.pathname;
    var event = 'enter_page';
    var messege_text = '{"event_type":"'+ event +'","url":"'+ url +'"}';
    sendLog(messege_text);
}

/*
 * click item event
 */
function clickItemEvent(){
    $('.resource-list > .item.resource').click(function () {
        var url = $(this).find('a').attr('href')
        var item_id = url.split('/').pop();
        var event = 'click_item';
        var messege_text = '{"event_type":"'+ event +'","url":"'+ url +'",item_id:"'+ item_id +'"}';
        sendLog(messege_text);
    });   
}

/*
 * click media event
 */
function clickMediaEvent(){
    $('.media-list > .media.resource').click(function () {
        var url = $(this).find('a').attr('href')
        var item_id = url.split('/').pop();
        var event = 'click_media';
        var messege_text = '{"event_type":"'+ event +'","url":"'+ url +'",item_id:"'+ item_id +'"}';
        sendLog(messege_text);
    });
}

/*
 * click metadataBrowsw event
 */
function clickMetadataBrowseEvent(){
    $('.metadata-browse-direct-link').click(function () {
        var event = 'click_metadata_browse';
        var metaDataText = $(this).text();
        var messege_text = '{"event_type":"'+ event +'",text:"'+metaDataText+'"}';
        sendLog(messege_text);
    });   
    $('.metadata-browse-link').click(function () {
        var event = 'click_metadata_browse';
        var metaDataText = $(this).parent().text();
        var messege_text = '{"event_type":"'+ event +'",text:"'+metaDataText+'"}';
        sendLog(messege_text);
    });   
}

/*
 * click Link event
 */
function clickLinkEvent(){
    $('a:not(.item.resource > a):not(.media.resource > a)').click(function () {
        var url = $(this).attr('href');
        var item_id = url.split('/').pop();
        var event = 'click_link';
        var messege_text = '{"event_type":"'+ event +'","url":"'+ url +'"}';
        sendLog(messege_text);
    });   
}

/*
 * search event
 */
function searchSubmitEvent(){
    
}