/**
 * Created by User on 07.04.2018.
 */
var timer;
function upadateDate() {
    $.ajax({
        url: '/currency/' +
        $('.currency').val() +
        '/rate',
        method: 'GET'
    }).done(function (data) {
        console.log(data);
        console.log('done1');
        $('.data-container').html('Дата: ' + data['date'] + "<br>Курс: " + data['rate'])
    }).fail(function () {
        console.log('fail1');
        clearInterval(timer);
    });
}

$(function () {
    console.log("ready!");

    $('button.start').click(function () {
        console.log('test');
        upadateDate();
        timer = setInterval(upadateDate, 10000);
    });
    $('button.stop').click(function () {
        clearInterval(timer);
    })
});
