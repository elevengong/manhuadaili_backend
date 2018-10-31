var path;
$(function(){
	path = $("#hid_path").val();
});

function uppage(nowpage){
	if( nowpage == 0 ){
		layer.msg("已经是第一页！");
	}else{
		$nowpage = $("#hid_nowpage").val( nowpage );
		$("#frm_seach").submit();
	}
}

function downpage(nowpage){
	if( nowpage == 0 ){
		layer.msg("已经是最后一页！");
	}else{
		$nowpage = $("#hid_nowpage").val( nowpage );
		$("#frm_seach").submit();
	}
}

function logout(token){
	layer.confirm("是否真的要退出系统？", function(){
        $.ajax({
            type:"post",
            url:"/backend/logout",
            dataType:'json',
            headers:{'X-CSRF-TOKEN':token},
            success:function(data){
                if(data.status == 0)
                {
                    layer.msg( data.msg );
                    return false;

                }else{
                    layer.msg( data.msg ,function () {
                        window.location.href = "/";
                    });
                }

                return false;
            },
            error:function (data) {
                layer.msg("注销失败");
                return false;

            }

        });

	});
}

function  uppwd(token){
	layer.prompt( { title: "请输入新密码", formType: 0 }, function( upwd, index ){
		if( !isUname(upwd) || !( upwd.length >= 5 && upwd.length <= 20 ) ){
		    alert(upwd);
			layer.msg("请输入字母、数字组成的5-20位的密码");
			return false;
		}

        $.ajax({
            type:"post",
            url:"/backend/changepwd",
            dataType:'json',
            headers:{'X-CSRF-TOKEN':token},
			data:{newpwd: upwd},
            success:function(data){
                if(data.status == 0)
                {
                    layer.msg( data.msg );
                    layer.close(index);

                }else{
                    layer.msg( data.msg );
                    layer.close(index);
                }

            },
            error:function (data) {
                layer.msg("修改失败");
                layer.close(index);

            }

        });

	});
}