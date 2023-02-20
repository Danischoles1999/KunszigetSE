$(document).ready(function(){
    $("#livePlayerSearch").keyup(function(){
        var input = $(this).val();
        if(input != ""){
            $.ajax({
                url:"../includes/livePlayerSearch.inc.php",
                method:"POST",
                data:{input:input},
                success:function(data){
                    $("#searchPlayerResult").html(data);
                    $("#searchPlayerResult").css("display","inline");
                    $("#allPlayers").css("display","none");
                }
            })
        }else{
            $("#searchPlayerResult").css("display","none");
            $("#allPlayers").css("display","block");
        }
    })
})