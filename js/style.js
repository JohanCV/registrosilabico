(function () {
    //Time now function
    setInterval(function () {
        myTimer()
    }, 1000);

    function myTimer() {
        var d = new Date(),
            year = d.getFullYear(),
            month = d.getMonth(),
            months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            monthName = months[month],
            day = d.getDate(),
            dayName = d.getDay(),
            week = ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"],
            weekDay = week[dayName-1],
            hour = d.getHours(),
            minute = d.getMinutes(),
            second = d.getSeconds();
        if (day < 10) {
            day = "0" + day;
        }
        if (month < 10) {
            month = "0" + month;
        }
        if (hour < 10) {
            hour = "0" + hour;
        }
        if (minute < 10) {
            minute = "0" + minute;
        }
        if (second < 10) {
            second = "0" + second;
        }
        if (hour >= 12) {
            
            $("#after-past").html("pm");
        } else {
            $("#after-past").html("am");
        }
        
        var actualHour = hour + ":" + minute + ":" + second;
        $("#actual-hour").html(actualHour);
        // var actualDate = day + "-" + month + "-" + year;
        var actualDate = weekDay + " " + day + " de " + monthName + " del " + year;
        var actualDate2 = weekDay + " " + day + " de " + monthName;
        var periodoAcademico;
        if(month>6){
            periodoAcademico = "Periodo Académico " + year + "-II"
        } else{
            periodoAcademico = "Periodo Académico " + year + "-I"
        }
        
        $("#actual-date").html(actualDate);
        $(".fecha-cabecera").html(actualDate2);
        $(".periodo-academico").html(periodoAcademico);
    }
})();