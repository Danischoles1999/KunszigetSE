$(document).ready(function(){
    $("#searchGameSeasonSelect").change(function(){
        var input = $('#searchGameSeasonSelect').val();
        if(input != ""){
            $.ajax({
                url:"../includes/liveGameSearch.inc.php",
                method:"POST",
                data:{input:input},
                success:function(data){
                    $("#searchGameResult").html(data);
                    $("#searchGameResult").css("display","inline");
                    $("#allGames").css("display","none");
                }
            })
        }else{
            $("#searchGameResult").css("display","none");
            $("#allGames").css("display","block");
        }
    })
})