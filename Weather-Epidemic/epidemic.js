function epidemic(){
    $.post('https://apis.tianapi.com/ncov/index',
    {
        key: '6c794829c839775ea968264a3439207b'//KEY放这边
    },
    function(data, status){
        console.log(data);
        $('#currentConfirmedCount').html(data.result.desc.currentConfirmedCount+'</h>');
        $('#confirmedCount').html( data.result.desc.confirmedCount+'</h>');
        $('#suspectedCount').html(data.result.desc.suspectedCount+'</h>');
        $('#curedCount').html(data.result.desc.curedCount+'</h>');
        $('#seriousCount').html(data.result.desc.seriousCount+'</h>');
        $('#suspectedIncr').html( data.result.desc.suspectedIncr+'</h>');
        $('#curedIncr').html(data.result.desc.curedIncr+'</h>');
        $('#deadCount').html(data.result.desc.deadCount+'</h>');
    });

}
epidemic();