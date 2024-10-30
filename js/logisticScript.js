jQuery("#logisticButton").click(function(){

    var url_string = window.location;
    var url = new URL(url_string);
    var checkstatus = url.searchParams.get("checkstatus");
    
    
    if(checkstatus){
        window.location.href = window.location;
    }else{
        window.location.href = window.location+"&checkstatus=true";
    }
});

