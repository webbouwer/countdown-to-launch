jQuery(document).ready(function ($) {



var countDownCloak = function(options){

	var root = this;

	var vars = {
  	cloakDivID 		: 'cloak-page-container',
    pageDivID		: 'page-container',
  	deadline		: new Date('2021-01-01 00:00:00'),
  };

  this.construct = function(options){

  	$.extend(vars , options);
    this.initClockworks(vars.deadline);

  };

  this.getcurrentTime = function(){
  	return Date.parse(new Date());
  };

  this.getTimeRemaining = function(endtime) {
    var t = Date.parse(endtime) - Date.parse(new Date());
    var seconds = Math.floor((t / 1000) % 60);
    var minutes = Math.floor((t / 1000 / 60) % 60);
    var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    return {
      'total': t,
      'days': days,
      'hours': hours,
      'minutes': minutes,
      'seconds': seconds
    };
	}
  this.initializeClock = function(id, endtime) {

    var clock = document.getElementById(id);
    var daysSpan = clock.querySelector('.days');
    var hoursSpan = clock.querySelector('.hours');
    var minutesSpan = clock.querySelector('.minutes');
    var secondsSpan = clock.querySelector('.seconds');

    function updateClock() {
      var t = root.getTimeRemaining(endtime);
      daysSpan.innerHTML = t.days;
      hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
      minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
      secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
      if (t.total <= 0) { // exit countdown
        clearInterval(timeinterval);
        root.removeCloak();
      }
    }
    updateClock();
    var timeinterval = setInterval(updateClock, 1000);
  }

  this.removeCloak = function(){
    $('#'+vars.pageDivID).show();
    $('#'+vars.cloakDivID).fadeOut(600, function(){
      $(this).hide().remove();
    });
  }

  this.insertCloack = function(){

    var box = $('<div id='+vars.cloakDivID+'>');
    var content = '<h1 class="cloak-page-title" style="color:'+vars.titlecolor+'">'+vars.titletext+'</h1>';
      content += '<p class="cloak-page-desc">'+vars.desctext+'</p>';
      content += '<div id="clockdiv" style="color:'+vars.timertextcolor+';">';
      content += '<div style="background-color:'+vars.timerboxoutercolor+'"><span class="days" style="color:'+vars.timernumbercolor+';background-color:'+vars.timerboxinnercolor+'"></span><div class="smalltext">Days</div></div>';
      content += '<div style="background-color:'+vars.timerboxoutercolor+'"><span class="hours" style="color:'+vars.timernumbercolor+';background-color:'+vars.timerboxinnercolor+'"></span><div class="smalltext">Hours</div></div>';
      content += '<div style="background-color:'+vars.timerboxoutercolor+'"><span class="minutes" style="color:'+vars.timernumbercolor+';background-color:'+vars.timerboxinnercolor+'"></span><div class="smalltext">Minutes</div></div>';
      content += '<div style="background-color:'+vars.timerboxoutercolor+'"><span class="seconds" style="color:'+vars.timernumbercolor+';background-color:'+vars.timerboxinnercolor+'"></span><div class="smalltext">Seconds</div></div>';
      content += '</div>';
      content += '<p class="cloak-page-desc2" style="color:'+vars.desc2color+'">'+vars.desctext2+'</p>';


    box.css({
    	"position":"absolute",
        "text-align":"center",
        "top":"0px",
    	"left":"0px",
   	 	"width":"100%",
    	"height":"100vh",
    	"background-color": vars.bgcolor,
    	"color": vars.desc1color,
    });

    box.html(content);
    $('body').prepend(box);

  }

  this.initClockworks = function(deadline){

    // check time to countdown
  	var currentTime = root.getcurrentTime();
    var chk = Date.parse(deadline) - Date.parse(new Date());
    $('#'+vars.pageDivID).hide();
    if( chk > 0 ){
      root.insertCloack();
      root.initializeClock('clockdiv', deadline);
    }else{
	  $('#'+vars.pageDivID).fadeIn();
      root.removeCloak();
    }
  }

  this.construct(options);

}

/*
$.ajax({

        url: params.ajaxurl,
        type: 'get',
        data: {
            'action':'states_city_filter'
        },
        success: function( response ) {
            console.log(response);
        },
});
*/

 var setTime = params.date +' '+ params.hours +':'+ params.minutes +':'+ params.seconds;

  // var deadline  new Date('2021-01-01 00:00:00') // new Date('2020-12-23 17:05:00') //new Date(Date.parse(new Date()) + 3000)
  var countdown = new countDownCloak({
  	deadline : new Date( setTime ), //new Date('2021-01-01 00:00:00'), // new Date(Date.parse(new Date()) + 15000), //
    pageDivID		: 'page-container',
  	cloakDivID 		: 'cloak-page-container',
    titletext: params.title,
    desctext: params.desc,
    desctext2: params.desc2,
    bgcolor: params.bgcolor,
    titlecolor: params.titlecolor,
    desc1color: params.desc1color,
    desc2color: params.desc2color,
    timernumbercolor: params.timernumbercolor,
    timertextcolor: params.timertextcolor,
    timerboxinnercolor: params.timerboxinnercolor,
    timerboxoutercolor: params.timerboxoutercolor,
  });


});
