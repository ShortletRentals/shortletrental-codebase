<!-- <link rel="stylesheet" href="http://localhost/shortletrental/assets/web/css/bootstrap.min.css"> -->
<!-- <script src="{{ URL::asset('assets/js/jquery.min.js')}}"></script> -->
<div class="card">
	<div class="card-header">
        <div class="row">
        
            
            <div class="col-md-2 mt-2"><input type="text" class="form-control start_date" placeholder="From Date"></div>
            <div class="col-md-2 mt-2"><input type="text" class="form-control end_date"  placeholder="To Date"></div>
            <div class="col-md-4 d-inline-flex mt-2">
                <button type="button" class="btn btn-primary filter me-3"></i>Block</button>
				<button type="button" class="btn btn-primary unblock me-3"></i>Unblock</button>
                
            </div>                  
        </div>
    </div>   
</div>
<div class="row">
	<div class="col-sm-4 col-md-4">
	  <div class="calendar calendar-first" id="calendar_first">
	    <div class="calendar_header">
	      <button class="switch-month switch-left">
	        <i class="glyphicon glyphicon-chevron-left"></i>
	      </button>
	      <h2></h2>
	      <button class="switch-month switch-right">
	        <i class="glyphicon glyphicon-chevron-right"></i>
	      </button>
	    </div>
	    <div class="calendar_weekdays"></div>
	    <div class="calendar_content"></div>
	  </div>
	</div>
	<div class="col-sm-4 col-md-4">
	  <div class="calendar calendar-second" id="calendar_second">
	    <div class="calendar_header">
	      <button class="switch-month switch-left">
	        <i class="glyphicon glyphicon-chevron-left"></i>
	      </button>
	      <h2></h2>
	      <button class="switch-month switch-right">
	        <i class="glyphicon glyphicon-chevron-right"></i>
	      </button>
	    </div>
	    <div class="calendar_weekdays"></div>
	    <div class="calendar_content"></div>
	  </div>           
	</div>
	<div class="col-sm-4 col-md-4">
	    <div class="calendar calendar-three" id="calendar_three">
	      <div class="calendar_header">
	        <button class="switch-month switch-left">
	          <i class="glyphicon glyphicon-chevron-left"></i>
	        </button>
	        <h2></h2>
	        <button class="switch-month switch-right">
	          <i class="glyphicon glyphicon-chevron-right"></i>
	        </button>
	      </div>
	      <div class="calendar_weekdays"></div>
	      <div class="calendar_content"></div>
	    </div>            
	</div>
</div> <!-- End Row -->
<div class="row">
	<div class="col-sm-4 col-md-4">
	  <div class="calendar calendar-four" id="calendar_four">
	    <div class="calendar_header">
	      <button class="switch-month switch-left">
	        <i class="glyphicon glyphicon-chevron-left"></i>
	      </button>
	      <h2></h2>
	      <button class="switch-month switch-right">
	        <i class="glyphicon glyphicon-chevron-right"></i>
	      </button>
	    </div>
	    <div class="calendar_weekdays"></div>
	    <div class="calendar_content"></div>
	  </div>
	</div>
	<div class="col-sm-4 col-md-4">
	  <div class="calendar calendar-five" id="calendar_five">
	    <div class="calendar_header">
	      <button class="switch-month switch-left">
	        <i class="glyphicon glyphicon-chevron-left"></i>
	      </button>
	      <h2></h2>
	      <button class="switch-month switch-right">
	        <i class="glyphicon glyphicon-chevron-right"></i>
	      </button>
	    </div>
	    <div class="calendar_weekdays"></div>
	    <div class="calendar_content"></div>
	  </div>            
	</div>
	<div class="col-sm-4 col-md-4">
	    <div class="calendar calendar-six" id="calendar_six">
	      <div class="calendar_header">
	        <button class="switch-month switch-left">
	          <i class="glyphicon glyphicon-chevron-left"></i>
	        </button>
	        <h2></h2>
	        <button class="switch-month switch-right">
	          <i class="glyphicon glyphicon-chevron-right"></i>
	        </button>
	      </div>
	      <div class="calendar_weekdays"></div>
	      <div class="calendar_content"></div>
	    </div>            
	</div>
</div> <!-- End Row -->
<style>
	.calendar-section {
	  margin-bottom: 20px;
	}
	.bookingCalender .calendar {
	  margin: auto;
	  font-weight: 400;
	  margin-bottom: 20px
	}
	.calendar_weekdays {
		color: #aaa;
		font-weight: lighter;
	}
	.calendar_content, .calendar_weekdays, .calendar_header {
	    position: relative;
	    display: flex;
	    flex-wrap: wrap;
	}
	.calendar_content:after, .calendar_weekdays:after, .calendar_header:after {
		content: ' ';
		display: table;
		clear: both;
	}
	.calendar_weekdays div, .calendar_content div {
	    flex: 0 0 calc(100% / 7);
	    height: 48px;
	    overflow: hidden;
	    background-color: transparent;
	    color: #000;
	    font-weight: 400;
	    line-height: 15pt;
	    text-align: center;
	    font-size: 14px;
	    text-transform: capitalize;
	    vertical-align: middle;
	    display: inline-flex;
	    align-items: center;
	    justify-content: center;
	    border: 0 !important;
	}
	.calendar_weekdays div {
	    background-color: transparent;
	    color: #6b7c84;
	    font-weight: bold;
	    line-height: normal;
	    text-align: center;
	    font-size: 11px;
	    text-transform: capitalize;
	    vertical-align: middle;
	    height: 24px;
	    display: flex;
	    align-items: center;
	}
	.bookingCalenderMain {
	    width: 100%;
	    padding: 25px 10px 25px 10px !important;
	    box-shadow: 0 0 20px 0 rgb(0 0 0 / 8%);
	    background-color: #fff;
	    position: relative;
	    margin-top: 20px;
	}
	.calendar_header h2 {
	    line-height: 1.7;
	    color: #2e424d;
	    font-size: 14px;
	    text-transform: uppercase;
	    font-weight: bold;
	    width: 100%;
	}
	.calendar_content .today {
		color: #3B8FC7;
	}
	.calendar_content div {
	  float: left;
	  margin-left: -1px;
	  margin-top: 2px;
	  color: #c7cbce;
	  border: 1px solid transparent;
	  background-color: #19dd91;
	  color: #57616a !important;
	}
	.calendar_content div.blank {
	  background: transparent;
	}
	.calendar_content div:hover {
	  border: 1px solid #777;
	  cursor: pointer;

	}
	.calendar_content div.blank:hover {
	  cursor: default;
	  border: none;
	}
	.calendar_content div.blank {
	    height: unset;
	}
	.calendar_content div.past-date {
		cursor: initial;
	  color: #d5d5d5;
	}
	.calendar_content div.past-date1 {
	  cursor: initial;
	  color: #c7cbce !important;
	  background-color: #FF0000;  /*Red- admin block date*/
	}
	.calendar_content div.past-date2 {
	  cursor: initial;
	  color: #c7cbce !important;
	  background-color: #c90076; /*purple- host block date*/
	}
	.calendar_content div.past-date3 {
	  cursor: initial;
	  color: #c7cbce !important;
	  background-color: #0000ff;  /*Blue- Booking paid date*/
	}
	.calendar_content div.past-date4 {
	  cursor: initial;
	  color: #c7cbce !important;
	  background-color: #808080;  /*Gray- subadmin block date*/
	}

	.calendar_content div.today{
	  font-weight: bold;
	  font-size: 14px;
	  color: #409EDD;
	}
	.calendar_content div.selected {
	  background-color: rgba(153, 153, 161, .2); /*rgba(170, 170, 176, .5) #aaaab0*/
	  border: 1px solid white;
	}
	.calendar_header {
	  width: 100%;
	  text-align: center;
	}
	button.switch-month {
	  background-color: transparent;
	  padding: 0;
	  outline: none;
	  border: none;
	  line-height: 52px;
	  height: 55px;
	  float: left;
	  width:15%;
	  transition: color .2s;
	  display: none;
	}
	.booking_right_icon {
	    margin-right: 25px;
	}
	.booking_left_icon {
	    margin-left: 15px;
	}
	button.switch-month:hover {
	  color: #5EADE2;
	}
	button.switch-month:active {
	  background-color: rgba(113, 113, 125, .4);
	}
	.calendar {
	  margin: 30px 0;
	}
</style>
<script>
    $( document ).ready(function() {
		function c(passed_month, passed_year, calNum) {
			var oneMonth = "{{$monthWiseData[0]}}";
			var twoMonth = "{{$monthWiseData[1]}}";
			var threeMonth = "{{$monthWiseData[2]}}";
			var fourMonth = "{{$monthWiseData[3]}}";
			var fiveMonth = "{{$monthWiseData[4]}}";
			var sixMonth = "{{$monthWiseData[5]}}";

			var oneMonthHost = "{{$hostMonthWiseData[0]}}";
			var twoMonthHost = "{{$hostMonthWiseData[1]}}";
			var threeMonthHost = "{{$hostMonthWiseData[2]}}";
			var fourMonthHost = "{{$hostMonthWiseData[3]}}";
			var fiveMonthHost = "{{$hostMonthWiseData[4]}}";
			var sixMonthHost = "{{$hostMonthWiseData[5]}}";

			var oneMonthAdmin = "{{$adminMonthWiseData[0]}}";
			var twoMonthAdmin = "{{$adminMonthWiseData[1]}}";
			var threeMonthAdmin = "{{$adminMonthWiseData[2]}}";
			var fourMonthAdmin = "{{$adminMonthWiseData[3]}}";
			var fiveMonthAdmin = "{{$adminMonthWiseData[4]}}";
			var sixMonthAdmin = "{{$adminMonthWiseData[5]}}";


			var oneMonthAdminSub = "{{$subAdminMonthWiseData[0]}}";
			var twoMonthAdminSub = "{{$subAdminMonthWiseData[1]}}";
			var threeMonthAdminSub = "{{$subAdminMonthWiseData[2]}}";
			var fourMonthAdminSub = "{{$subAdminMonthWiseData[3]}}";
			var fiveMonthAdminSub = "{{$subAdminMonthWiseData[4]}}";
			var sixMonthAdminSub = "{{$subAdminMonthWiseData[5]}}";


			var calendar = (calNum == 0 ? calendars.cal1 : (calNum==1 ? calendars.cal2 : (calNum ==2 ? calendars.cal3 : (calNum ==3 ? calendars.cal4 :  (calNum ==4 ? calendars.cal5 :calendars.cal6 )) )));
			makeWeek(calendar.weekline);
			calendar.datesBody.empty();
			var calMonthArray = makeMonthArray(passed_month, passed_year);
			var r = 0;
			var u = false;
			while(!u) {
				if(daysArray[r] == calMonthArray[0].weekday) { u = true } 
				else { 
					calendar.datesBody.append('<div class="blank"></div>');
					r++;
				}
			} 
			for(var cell=0;cell<42-r;cell++) { // 42 date-cells in calendar
				if(cell >= calMonthArray.length) {
					calendar.datesBody.append('<div class="blank"></div>');
				} else {
					var shownDate = calMonthArray[cell].day;
					// Later refactiroing -- iter_date not needed after "today" is found
					var iter_date = new Date(passed_year,passed_month,shownDate); 
					if ( 
						(
							( shownDate != today.getDate() && passed_month == today.getMonth() ) 
							|| passed_month != today.getMonth()
						) 
							&& iter_date < today) {						
						var m = '<div class="past-date">';
					} else {
						var m = checkToday(iter_date)?'<div class="today">':"<div>";
					}
					calendar.datesBody.append(m + shownDate + "</div>");
				}
			}

			// var color = o[passed_month];
			calendar.calHeader.find("h2").text(i[passed_month]+" "+passed_year);
						//.css("background-color",color)
						//.find("h2").text(i[passed_month]+" "+year);

			// find elements (dates) to be clicked on each time
			// the calendar is generated
			
			//clickedElement = bothCals.find(".calendar_content").find("div");
			var clicked = false;
			selectDates(selected);

			clickedElement = calendar.datesBody.find('div');
			clickedElement.on("click", function(){
				clicked = $(this);
				if (clicked.hasClass('past-date')) { return; }
				var whichCalendar = calendar.name;
				// console.log(whichCalendar, '-------whichCalendar');
				// Understading which element was clicked;
				// var parentClass = $(this).parent().parent().attr('class');
				if (firstClick && secondClick) {
					thirdClicked = getClickedInfo(clicked, calendar);
					var firstClickDateObj = new Date(firstClicked.year, 
												firstClicked.month, 
												firstClicked.date);
					var secondClickDateObj = new Date(secondClicked.year, 
												secondClicked.month, 
												secondClicked.date);
					var thirdClickDateObj = new Date(thirdClicked.year, 
												thirdClicked.month, 
												thirdClicked.date);
					if (secondClickDateObj > thirdClickDateObj
						&& thirdClickDateObj > firstClickDateObj) {
						secondClicked = thirdClicked;
						// then choose dates again from the start :)
						bothCals.find(".calendar_content").find("div").each(function(){
							$(this).removeClass("selected");
						});
						selected = {};
						selected[firstClicked.year] = {};
						selected[firstClicked.year][firstClicked.month] = [firstClicked.date];
						selected = addChosenDates(firstClicked, secondClicked, selected);
					} else { // reset clicks
						selected = {};
						firstClicked = [];
						secondClicked = [];
						firstClick = false;
						secondClick = false;
						bothCals.find(".calendar_content").find("div").each(function(){
							$(this).removeClass("selected");
						});	
					}
				}

				if (!firstClick) {
					firstClick = true;
					firstClicked = getClickedInfo(clicked, calendar);
					selected[firstClicked.year] = {};
					selected[firstClicked.year][firstClicked.month] = [firstClicked.date];
                  //  alert(selected);    
				} else {
					console.log('second click');
					secondClick = true;
					secondClicked = getClickedInfo(clicked, calendar);
					//console.log(secondClicked);

					// what if second clicked date is before the first clicked?
					var firstClickDateObj = new Date(firstClicked.year, 
												firstClicked.month, 
												firstClicked.date);
					var secondClickDateObj = new Date(secondClicked.year, 
												secondClicked.month, 
												secondClicked.date);

					if (firstClickDateObj > secondClickDateObj) {

						var cachedClickedInfo = secondClicked;
						secondClicked = firstClicked;
						firstClicked = cachedClickedInfo;
						selected = {};
						selected[firstClicked.year] = {};
						selected[firstClicked.year][firstClicked.month] = [firstClicked.date];

					} else if (firstClickDateObj.getTime() ==
								secondClickDateObj.getTime()) {
						selected = {};
						firstClicked = [];
						secondClicked = [];
						firstClick = false;
						secondClick = false;
						$(this).removeClass("selected");
					}


					// add between dates to [selected]

                    // console.log(firstClicked);
                    
                    // console.log(secondClicked,selected);

                    // console.log(firstClicked,secondClicked,selected);
				}
				// console.log(firstClicked, '--------', secondClicked);
               
                selected = addChosenDates(firstClicked, secondClicked, selected);
                selectDates(selected);

                //Block Calendar Code
                var clickedEventTriggered = getClickedInfo(clicked, calendar);
                var clickedEventTriggeredNew = clickedEventTriggered.year+'-'+(parseInt(clickedEventTriggered.month) + parseInt(1)) +'-'+clickedEventTriggered.date;
                //Call Block Calendar
                checkBlockCalender(clickedEventTriggeredNew);
                
				
			});	

			var start_day = parseInt("{{$start_day}}");
			var start_month = parseInt("{{$start_month}}")-1;
			var start_year = parseInt("{{$start_year}}");
			
			var end_day = parseInt("{{$end_day}}");
			var end_month = parseInt("{{$end_month}}")-1;
			var end_year = parseInt("{{$end_year}}");


			firstClicked = {"calNum":"first","date":start_day,"month":start_month,"year":start_year}
			secondClicked = {"calNum":"six","date":end_day,"month":end_month,"year":end_year}
				selected = {"{{$start_year}}": {"{{$start_month-1}}": [],"{{$end_year}}":{"{{$end_month-1}}":[]}}};
				
			selected = addChosenDates(firstClicked, secondClicked, selected);

			var setDate = [];
			setDate[0] = oneMonth.split(",");
			setDate[1] = twoMonth.split(",");
			setDate[2] = threeMonth.split(",");
			setDate[3] = fourMonth.split(",");
			setDate[4] = fiveMonth.split(",");
			setDate[5] = sixMonth.split(",");
			selectDates(selected,setDate);

			var setHostDate = [];
			setHostDate[0] = oneMonthHost.split(",");
			setHostDate[1] = twoMonthHost.split(",");
			setHostDate[2] = threeMonthHost.split(",");
			setHostDate[3] = fourMonthHost.split(",");
			setHostDate[4] = fiveMonthHost.split(",");
			setHostDate[5] = sixMonthHost.split(",");
			selectHostDates(selected,setHostDate);

			var setAdminDate = [];
			setAdminDate[0] = oneMonthAdmin.split(",");
			setAdminDate[1] = twoMonthAdmin.split(",");
			setAdminDate[2] = threeMonthAdmin.split(",");
			setAdminDate[3] = fourMonthAdmin.split(",");
			setAdminDate[4] = fiveMonthAdmin.split(",");
			setAdminDate[5] = sixMonthAdmin.split(",");
			selectAdminDates(selected,setAdminDate);
			
			var setSubAdminDate = [];
			setSubAdminDate[0] = oneMonthAdminSub.split(",");
			setSubAdminDate[1] = twoMonthAdminSub.split(",");
			setSubAdminDate[2] = threeMonthAdminSub.split(",");
			setSubAdminDate[3] = fourMonthAdminSub.split(",");
			setSubAdminDate[4] = fiveMonthAdminSub.split(",");
			setSubAdminDate[5] = sixMonthAdminSub.split(",");
			selectSubAdminDates(selected,setSubAdminDate);

		}
		function selectDates(selected,setDate = []) {
			if (!$.isEmptyObject(selected)) {
				var dateElements1 = datesBody1.find('div');
				var dateElements2 = datesBody2.find('div');
                var dateElements3 = datesBody3.find('div');
                var dateElements4 = datesBody4.find('div');
                var dateElements5 = datesBody5.find('div');
                var dateElements6 = datesBody6.find('div');


				function highlightDates(passed_year, passed_month, dateElements,selectedyear){
					if (passed_year in selected && passed_month in selected[passed_year]) {
						var daysToCompare = selectedyear
						// console.log(daysToCompare,'-------231');
						for (var d in daysToCompare) {
                           // console.log(dateElements);
							dateElements.each(function(index) {
								if (parseInt($(this).text()) == daysToCompare[d]) {``
									$(this).addClass('past-date3');
								}
							});	
						}
					}
				}

				highlightDates(year, month, dateElements1,setDate[0] ? setDate[0] : []);
				highlightDates(nextYear, nextMonth, dateElements2,setDate[1] ? setDate[1] : []);
		        highlightDates(threeYear, threeMonth, dateElements3,setDate[2] ? setDate[2] : []);
		        highlightDates(fourYear, fourMonth, dateElements4,setDate[3] ? setDate[3] : []);
		        highlightDates(fiveYear, fiveMonth, dateElements5,setDate[4] ? setDate[4] : []);
		        highlightDates(sixYear, sixMonth, dateElements6,setDate[5] ? setDate[5] : []);
			}
		}

		function selectHostDates(selected,setDate = []) {
			if (!$.isEmptyObject(selected)) {
				var dateElements1 = datesBody1.find('div');
				var dateElements2 = datesBody2.find('div');
                var dateElements3 = datesBody3.find('div');
                var dateElements4 = datesBody4.find('div');
                var dateElements5 = datesBody5.find('div');
                var dateElements6 = datesBody6.find('div');


				function highlightDates(passed_year, passed_month, dateElements,selectedyear){
					if (passed_year in selected && passed_month in selected[passed_year]) {
						var daysToCompare = selectedyear
						// console.log(daysToCompare,'-------231');
						for (var d in daysToCompare) {
                           // console.log(dateElements);
							dateElements.each(function(index) {
								if (parseInt($(this).text()) == daysToCompare[d]) {``
									$(this).addClass('past-date2');
								}
							});	
						}
					}
				}

				highlightDates(year, month, dateElements1,setDate[0] ? setDate[0] : []);
				highlightDates(nextYear, nextMonth, dateElements2,setDate[1] ? setDate[1] : []);
		        highlightDates(threeYear, threeMonth, dateElements3,setDate[2] ? setDate[2] : []);
		        highlightDates(fourYear, fourMonth, dateElements4,setDate[3] ? setDate[3] : []);
		        highlightDates(fiveYear, fiveMonth, dateElements5,setDate[4] ? setDate[4] : []);
		        highlightDates(sixYear, sixMonth, dateElements6,setDate[5] ? setDate[5] : []);
			}
		}

		function selectAdminDates(selected,setDate = []) {
			if (!$.isEmptyObject(selected)) {
				var dateElements1 = datesBody1.find('div');
				var dateElements2 = datesBody2.find('div');
                var dateElements3 = datesBody3.find('div');
                var dateElements4 = datesBody4.find('div');
                var dateElements5 = datesBody5.find('div');
                var dateElements6 = datesBody6.find('div');


				function highlightDates(passed_year, passed_month, dateElements,selectedyear){
					if (passed_year in selected && passed_month in selected[passed_year]) {
						var daysToCompare = selectedyear
						// console.log(daysToCompare,'-------231');
						for (var d in daysToCompare) {
                           // console.log(dateElements);
							dateElements.each(function(index) {
								if (parseInt($(this).text()) == daysToCompare[d]) {``
									$(this).addClass('past-date1');
								}
							});	
						}
					}
				}

				highlightDates(year, month, dateElements1,setDate[0] ? setDate[0] : []);
				highlightDates(nextYear, nextMonth, dateElements2,setDate[1] ? setDate[1] : []);
		        highlightDates(threeYear, threeMonth, dateElements3,setDate[2] ? setDate[2] : []);
		        highlightDates(fourYear, fourMonth, dateElements4,setDate[3] ? setDate[3] : []);
		        highlightDates(fiveYear, fiveMonth, dateElements5,setDate[4] ? setDate[4] : []);
		        highlightDates(sixYear, sixMonth, dateElements6,setDate[5] ? setDate[5] : []);
			}
		}

		function selectSubAdminDates(selected,setDate = []) {
			if (!$.isEmptyObject(selected)) {
				var dateElements1 = datesBody1.find('div');
				var dateElements2 = datesBody2.find('div');
                var dateElements3 = datesBody3.find('div');
                var dateElements4 = datesBody4.find('div');
                var dateElements5 = datesBody5.find('div');
                var dateElements6 = datesBody6.find('div');


				function highlightDates(passed_year, passed_month, dateElements,selectedyear){
					if (passed_year in selected && passed_month in selected[passed_year]) {
						var daysToCompare = selectedyear
						// console.log(daysToCompare,'-------231');
						for (var d in daysToCompare) {
                           // console.log(dateElements);
							dateElements.each(function(index) {
								if (parseInt($(this).text()) == daysToCompare[d]) {``
									$(this).addClass('past-date4');
								}
							});	
						}
					}
				}

				highlightDates(year, month, dateElements1,setDate[0] ? setDate[0] : []);
				highlightDates(nextYear, nextMonth, dateElements2,setDate[1] ? setDate[1] : []);
		        highlightDates(threeYear, threeMonth, dateElements3,setDate[2] ? setDate[2] : []);
		        highlightDates(fourYear, fourMonth, dateElements4,setDate[3] ? setDate[3] : []);
		        highlightDates(fiveYear, fiveMonth, dateElements5,setDate[4] ? setDate[4] : []);
		        highlightDates(sixYear, sixMonth, dateElements6,setDate[5] ? setDate[5] : []);
			}
		}


		function makeMonthArray(passed_month, passed_year) { // creates Array specifying dates and weekdays
			var e=[];
			for(var r=1;r<getDaysInMonth(passed_year, passed_month)+1;r++) {
				e.push({day: r,
						// Later refactor -- weekday needed only for first week
						weekday: daysArray[getWeekdayNum(passed_year,passed_month,r)]
					});
			}
			return e;
		}
		function makeWeek(week) {
			week.empty();
			for(var e=0;e<7;e++) { 
				week.append("<div>"+daysArray[e].substring(0,3)+"</div>") 
			}
		}

		function getDaysInMonth(currentYear,currentMon) {
			return(new Date(currentYear,currentMon+1,0)).getDate();
		}
		function getWeekdayNum(e,t,n) {
			return(new Date(e,t,n)).getDay();
		}
		function checkToday(e) {
			var todayDate = today.getFullYear()+'/'+(today.getMonth()+1)+'/'+today.getDate();
			var checkingDate = e.getFullYear()+'/'+(e.getMonth()+1)+'/'+e.getDate();
			return todayDate==checkingDate;

		}
		function getAdjacentMonth(curr_month, curr_year, direction) {
			var theNextMonth;
			var theNextYear;
			if (direction == "next") {
				theNextMonth = (curr_month + 1) % 12;
				theNextYear = (curr_month == 11) ? curr_year + 1 : curr_year;
			} else {
				theNextMonth = (curr_month == 0) ? 11 : curr_month - 1;
				theNextYear = (curr_month == 0) ? curr_year - 1 : curr_year;
			}
			return [theNextMonth, theNextYear];
		}
		function b() {
			//today = new Date('{{$start_date}}');
			d = new Date('{{$start_date}}');
			//alert(today);
			today = new Date(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate(), d.getUTCHours(), d.getUTCMinutes(), d.getUTCSeconds(), d.getUTCMilliseconds());
			
	
			year = today.getFullYear();
			month = today.getMonth();
			var nextDates = getAdjacentMonth(month, year, "next");
			nextMonth = nextDates[0];
			nextYear = nextDates[1];

            var threeDates = getAdjacentMonth(nextMonth, nextYear, "next");
			threeMonth = threeDates[0];
			threeYear = threeDates[1];

            var fourDates = getAdjacentMonth(threeMonth, threeYear, "next");
			fourMonth = fourDates[0];
			fourYear = fourDates[1];


            var fiveDates = getAdjacentMonth(fourMonth, fourYear, "next");
			fiveMonth = fiveDates[0];
			fiveYear = fiveDates[1];

            var sixDates = getAdjacentMonth(fiveMonth, fiveYear, "next");
			sixMonth = sixDates[0];
			sixYear = sixDates[1];


		}

		var e=480;

		var today;
		var year,
			month,
			nextMonth,
			nextYear,
            threeMonth,
			threeYear,
            fourMonth,
			fourYear,
            fiveMonth,
			fiveYear,
            sixMonth,
			sixYear;

		//var t=2013;
		//var n=9;
		var r = [];
		var i = ["JANUARY","FEBRUARY","MARCH","APRIL","MAY",
				"JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER",
				"NOVEMBER","DECEMBER"];
		var daysArray = ["Sunday","Monday","Tuesday",
						"Wednesday","Thursday","Friday","Saturday"];
		var o = ["#16a085","#1abc9c","#c0392b","#27ae60",
				"#FF6860","#f39c12","#f1c40f","#e67e22",
				"#2ecc71","#e74c3c","#d35400","#2c3e50"];
		
		var cal1=$("#calendar_first");
		var calHeader1=cal1.find(".calendar_header");
		var weekline1=cal1.find(".calendar_weekdays");
		var datesBody1=cal1.find(".calendar_content");

		var cal2=$("#calendar_second");
		var calHeader2=cal2.find(".calendar_header");
		var weekline2=cal2.find(".calendar_weekdays");
		var datesBody2=cal2.find(".calendar_content");


        var cal3=$("#calendar_three");
		var calHeader3=cal3.find(".calendar_header");
		var weekline3=cal3.find(".calendar_weekdays");
		var datesBody3=cal3.find(".calendar_content");


        var cal4=$("#calendar_four");
		var calHeader4=cal4.find(".calendar_header");
		var weekline4=cal4.find(".calendar_weekdays");
		var datesBody4=cal4.find(".calendar_content");



        var cal5=$("#calendar_five");
		var calHeader5=cal5.find(".calendar_header");
		var weekline5=cal5.find(".calendar_weekdays");
		var datesBody5=cal5.find(".calendar_content");



        var cal6=$("#calendar_six");
		var calHeader6=cal6.find(".calendar_header");
		var weekline6=cal6.find(".calendar_weekdays");
		var datesBody6=cal6.find(".calendar_content");


		var bothCals = $(".calendar");

		var switchButton = bothCals.find(".calendar_header").find('.switch-month');

		var calendars = { 
						"cal1": { 	"name": "first",
									"calHeader": calHeader1,
									"weekline": weekline1,
									"datesBody": datesBody1 },
						"cal2": { 	"name": "second",
									"calHeader": calHeader2,
									"weekline": weekline2,
									"datesBody": datesBody2	},
                        "cal3": { 	"name": "three",
									"calHeader": calHeader3,
									"weekline": weekline3,
									"datesBody": datesBody3	},
                        "cal4": { 	"name": "four",
									"calHeader": calHeader4,
									"weekline": weekline4,
									"datesBody": datesBody4	},
                        "cal5": { 	"name": "five",
									"calHeader": calHeader5,
									"weekline": weekline5,
									"datesBody": datesBody5	},
                        "cal6": { 	"name": "six",
									"calHeader": calHeader6,
									"weekline": weekline6,
									"datesBody": datesBody6	}
						}
                        
		

		var clickedElement;
		var firstClicked,
			secondClicked,
			thirdClicked;
		var firstClick = false;
		var secondClick = false;	
		var selected = {};

		b();
		c(month, year, 0);
		c(nextMonth, nextYear, 1);
        c(threeMonth, threeYear, 2);
        c(fourMonth, fourYear, 3);
        c(fiveMonth, fiveYear, 4);
        c(sixMonth, sixYear, 5);

		
        switchButton.on("click",function() {
				var clicked=$(this);
				var generateCalendars = function(e) {
				var nextDatesFirst = getAdjacentMonth(month, year, e);
				var nextDatesSecond = getAdjacentMonth(nextMonth, nextYear, e);
                var nextDatesThree = getAdjacentMonth(threeMonth, threeYear, e);
                var nextDatesFour = getAdjacentMonth(fourMonth, fourYear, e);
                var nextDatesFive = getAdjacentMonth(fiveMonth, fiveYear, e);
                var nextDatesSix = getAdjacentMonth(sixMonth, sixYear, e);

				month = nextDatesFirst[0];
				year = nextDatesFirst[1];

                nextMonth = nextDatesSecond[0];
				nextYear = nextDatesSecond[1];

                threeMonth = nextDatesThree[0];
				threeYear = nextDatesThree[1];

                fourMonth = nextDatesFour[0];
				fourYear = nextDatesFour[1];

                fiveMonth = nextDatesFive[0];
				fiveYear = nextDatesFive[1];

                sixMonth = nextDatesSix[0];
				sixYear = nextDatesSix[1];



				c(month, year, 0);
				c(nextMonth, nextYear, 1);
                c(threeMonth, threeYear, 2);
                c(fourMonth, fourYear, 3);
                c(fiveMonth, fiveYear, 4);
                c(sixMonth, sixYear, 5);
			};
			if(clicked.attr("class").indexOf("left")!=-1) { 
				generateCalendars("previous");
			} else { generateCalendars("next"); }
			clickedElement = bothCals.find(".calendar_content").find("div");
			console.log("checking");
		});


		//  Click picking stuff
		function getClickedInfo(element, calendar) {
			var clickedInfo = {};
			var clickedCalendar,
				clickedMonth,
				clickedYear;
			clickedCalendar = calendar.name;
			clickedMonth = (clickedCalendar == "first" ? month : (clickedCalendar == 'second' ? nextMonth : (clickedCalendar == 'three' ?  threeMonth : (clickedCalendar == 'four' ? fourMonth : (clickedCalendar == 'five' ? fiveMonth : sixMonth)))));
            clickedYear = (clickedCalendar == "first" ? year : (clickedCalendar == 'second' ? nextYear : (clickedCalendar == 'three' ?  threeYear : (clickedCalendar == 'four' ? fourYear : (clickedCalendar == 'five' ? fiveYear : sixYear)))));
			clickedInfo = {"calNum": clickedCalendar,
							"date": parseInt(element.text()),
							"month": clickedMonth,
							"year": clickedYear}
			//console.log(clickedInfo);
			return clickedInfo;
		}


		// Finding between dates MADNESS. Needs refactoring and smartening up :)
		function addChosenDates(firstClicked, secondClicked, selected) {
			if (secondClicked.date > firstClicked.date || 
				secondClicked.month > firstClicked.month ||
				secondClicked.year > firstClicked.year) {

				var added_year = secondClicked.year;
				var added_month = secondClicked.month;
				var added_date = secondClicked.date;
				// console.log(selected);

				if (added_year > firstClicked.year) {	
					// first add all dates from all months of Second-Clicked-Year
					selected[added_year] = {};
					selected[added_year][added_month] = [];
					for (var i = 1; 
						i <= secondClicked.date;
						i++) {
						selected[added_year][added_month].push(i);
					}
			
					added_month = added_month - 1;
					// console.log(added_month);
					while (added_month >= 0) {
						selected[added_year][added_month] = [];
						for (var i = 1; 
							i <= getDaysInMonth(added_year, added_month);
							i++) {
							selected[added_year][added_month].push(i);
						}
						added_month = added_month - 1;
					}

					added_year = added_year - 1;
					added_month = 11; // reset month to Dec because we decreased year
					added_date = getDaysInMonth(added_year, added_month); // reset date as well

					// Now add all dates from all months of inbetween years
					while (added_year > firstClicked.year) {
						selected[added_year] = {};
						for (var i=0; i < 12; i++) {
							selected[added_year][i] = [];
							for (var d = 1; d <= getDaysInMonth(added_year, i); d++) {
								selected[added_year][i].push(d);
							}
						}
						added_year = added_year - 1;
					}
				}
				if (added_month > firstClicked.month) {
					if (firstClicked.year == secondClicked.year) {
						// console.log("here is the month:",added_month);
						selected[added_year][added_month] = [];
						for (var i = 1; 
							i <= secondClicked.date;
							i++) {
							selected[added_year][added_month].push(i);
						}
						added_month = added_month - 1;
					}
					while (added_month > firstClicked.month) {
						selected[added_year][added_month] = [];
						for (var i = 1; 
							i <= getDaysInMonth(added_year, added_month);
							i++) {
							selected[added_year][added_month].push(i);
						}
						added_month = added_month - 1;
					}
					added_date = getDaysInMonth(added_year, added_month);
				}

				for (var i = firstClicked.date + 1; 
					i <= added_date;
					i++) {
					selected[added_year][added_month].push(i);
				}
			}
			return selected;
		}
});
$(document).on('click','.filter',function(){
	var from_date = $('.start_date').val();
	var to_date = $('.end_date').val();
	var id = "{{ $_GET['id'] }}";
	var routeUrl = "{{url('admin/property/blockDateRange')}}";
	if(from_date !== ''){
		if(to_date !== ''){
			$.ajax({
                url: routeUrl,
                type: 'post',
                headers: {
                    'X-CSRF-Token': `{{csrf_token()}}` 
                },
                dataType: 'json',
                data: {
                	id:id, 
                	from_date:from_date, 
                    to_date:to_date
                },
                success: function(result){
					if(result.status == 1){
						toastr.success(result.message);
					}else{
						toastr.error(result.message);
					}
					setTimeout(function () {
						location.reload(true);
					}, 3000);
				}
              });
		}else{
			alert('Please select to date');
		}
	}else{
		alert('Please select from date');
	}
});
$(document).on('click','.unblock',function(){
	var from_date = $('.start_date').val();
	var to_date = $('.end_date').val();
	var id = "{{ $_GET['id'] }}";
	var routeUrl = "{{url('admin/property/unBlockDateRange')}}";
	if(from_date !== ''){
		if(to_date !== ''){
			$.ajax({
                url: routeUrl,
                type: 'post',
                headers: {
                    'X-CSRF-Token': `{{csrf_token()}}` 
                },
                dataType: 'json',
                data: {
                	id:id, 
                	from_date:from_date, 
                    to_date:to_date
                },
                success: function(result){
					if(result.status == 1){
						toastr.success(result.message);
					}else{
						toastr.error(result.message);
					}
					setTimeout(function () {
						location.reload(true);
					}, 3000);
				}
              });
		}else{
			alert('Please select to date');
		}
	}else{
		alert('Please select from date');
	}
});
$(document).ready(function(){
    $(".start_date").datepicker({
         minDate: "-0D",
         maxDate: "+90D",
         numberOfMonths: 1,
         dateFormat:'yy-mm-dd',
         onSelect: function(selected) {
           $(".end_date").datepicker("option","minDate", selected)
         }
     });
     $(".end_date").datepicker({
         minDate:"-0D",
         maxDate:"+90D",
         numberOfMonths: 1,
         dateFormat:'yy-mm-dd',
         onSelect: function(selected) {
            $(".start_date").datepicker("option","maxDate", selected)
         }
     });
 });
</script>