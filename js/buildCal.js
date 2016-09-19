// function $(id)  
// {  
//     return document.getElementById(id);  
// }  
  
var nowDay = new Date();  
var year=parseInt(nowDay.getFullYear());  
var month=parseInt(nowDay.getMonth());  

function clearDays(){
    //clear all events in cal
    $('.event-table').find('.day-events-container').empty();
}



function addDayHandler(){

}  
/*
build the cal with dates
*/
function makeDays(year,month,flag)  
{  
    var firstDay = new Date(year,month,1);  
    //获得每月的前面空余的天数  
    var firstDayBefore = parseInt(firstDay.getDay());  
    //显示每月前面空余的天数
    var null_day_number = firstDayBefore-1
    var total_day_in_month = getDayCountByYearAndMonth(year,month);
    var day = 1;
    //显示每月的天数
    $(".event-table tbody tr").each(function(){
        $(this).find('td').each(function(){
            if (day>total_day_in_month) { return false;};
            if (null_day_number>0) { 
                $(this).attr({"class":"null_day"}).empty();
                null_day_number--;
                return true;
            }
            /*
            should be mouch elegant way to check the inner div status
            */
            if (flag == true){
            $(this).attr({"class":"day"}).empty().prepend($("<div/>",{"class":"day-events-container", "id":(new Date(year,month,day)).getTime()}).html("+"));}
            else{
                $(this).attr({"class":"day"}).empty().prepend($("<div/>",{"class":"day-events-container", "id":(new Date(year,month,day)).getTime()}));
            }
            $(this).prepend("<span>"+day+"</span>");
            day++;
        });
    });
    addDayHandler();
}  
/*
bind user's event to cal
*/
function bindEvents(year, month){
    var year_month = "year="+encodeURIComponent(year)+"&month=" +encodeURIComponent(month);
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "findEvent.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!) send username and password to login.php
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
    xmlHttp.addEventListener("load", function(){

        console.log(year+" "+month)
        var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
        
        if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
            console.log(jsonData);
            //add events to container
            /*
            display event, dim background
            TODO:
            how to sort the event when display
            */
            // console.log(JSON.parse(jsonData));
            jQuery.each(jsonData.events, function() {
                var event_time = new Date(this.event_time);
                var event_day = new Date(event_time.getFullYear(), event_time.getMonth(), event_time.getDate());
                //get date by id
                console.log(event_day+" app2 "+event_day.getTime());
                $('#'+event_day.getTime()).append($('<div/>',{"class":"day-event-wrapper"}).append(
                    $('<div/>',{"class":"event_name"}).html(this.event_name)).append(
                        $('<div/>',{"class":"event_time"}).html(this.event_time)).after(
                            '<div/>',{"class":"devent_detail"}).html(this.detail));

                // 
            });
            $(".day-events-container div.day-event-wrapper").on("click", function(){
                $("#creat-event-form").dialog({
                    autoOpen: false,
                    modal: true,
                    buttons: {
                        "Ok": function() {

                            $(this).dialog(function(){
                                console.log("ok!");

                            });
                            $(this).dialog("close");
                        },
                        "Cancel": function() {
                            $(this).dialog("close");
                        }
                    }
                });
                $( "#creat-event-form" ).dialog('open');
                // $(this).dialog('opne');
            });
            // console.log(JSON.stringify($('#mv-event-container')));
            // $('#day-event-wrapper').click($(function() {console.log("wow");}));
            // $('#day-event-wrapper').on('click',$(function() {
            //     $( "#day-event-wrapper" ).dialog({
            //         autoOpen: false
            //       });
            //     $( "#day-event-wrapper" ).dialog();
            // }));

            // $(function() {
            //     $("#creat-event-form").dialog({
            //         autoOpen: false,
            //         modal: true,
            //         buttons: {
            //             "Ok": function() {
            //                 $(this).dialog(function(){
            //                     //send event
            //                 });
            //             },
            //             "Cancel": function() {
            //                 $(this).dialog("close");
            //             }
            //         }
            //     });


            //     $('#day-event-wrapper').click(function() {
            //         $("#creat-event-form").dialog("open");
            //     });

            // });

           
        }else{
            console.log(jsonData.message);
            console.log(jsonData.session);
        }
    }, false); // Bind the callback to the load event
    xmlHttp.send(year_month);
}
//计算该年该月的天数  
function getDayCountByYearAndMonth(year,month)  
{  
    month++;  
    if(month==4 || month==6 || month==7 || month==9 || month==11)  
        return 30;  
    if(month==2)  
    {  
        if(((year%4==0)&&(year%100!=0)) || (year%400 == 0))  
            return 28;  
        return 29;  
    }  
    return 31;  
}  
//初始化年月选择器  
function builtSetYearAndMonth(yearNum)  
{  
    for(var i=-yearNum; i<yearNum;i++){  
        yearObj = document.createElement("option");  
        yearObj.innerHTML =parseInt(nowDay.getFullYear())+i;  
        yearObj.value =parseInt(nowDay.getFullYear())+i;      
        $("setYear").appendChild(yearObj);  
    }  
    for(var i=0;i<12;i++)  
    {  
        monthObj = document.createElement("option");  
        monthObj.innerHTML=i+1;  
        monthObj.value =i;  
        $("setMonth").appendChild(monthObj);  
    }  
    $("setYear").selectedIndex = yearNum;  
    $("setMonth").selectedIndex = parseInt(nowDay.getMonth());  
    makeDays(parseInt(year),parseInt(month));  
      
}  
function chose(ele)  
{  
    if(ele.id == "setYear"){  
        year = ele.value;     
    }  
    if(ele.id == "setMonth"){  
        month = ele.value;  
    }  
    $("days").innerHTML="";  
    makeDays(year,month);  
}