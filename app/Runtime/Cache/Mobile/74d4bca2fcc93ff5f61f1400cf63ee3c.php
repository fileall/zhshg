<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title></title>
        <script src="/theme/mobile/js/fontSize.js"></script>
        <link rel="stylesheet" href="/theme/mobile/css/index.css" />
        <script src="/theme/mobile/js/jquery-1.11.2.min.js"></script>
        <script src="/theme/mobile/js/custom.js"></script>
        <script src="/theme/mobile/js/layer/layer.js"></script>
        <script src="/theme/mobile/js/layui/layui.js"></script>
        <link rel="stylesheet" href="/theme/mobile/js/layui/css/layui.css">
    </head>

    <body class="body-bgColor">

        <div class="header-wrap">
            <div class="header-inner">
                <div class="header-title">我的订单</div>
                <div class="header-left return-prev"><i class="fa fa-angle-left"></i></div>
                <div class="header-right"></div>
            </div>
        </div>
        <div class="header-space"></div>


        <div class="state-wrap-box">
            <div class="state-nav row-box">
                <?php if(is_array($array)): $i = 0; $__LIST__ = $array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nice): $mod = ($i % 2 );++$i; if($i == $status): ?><div  class="row-flex state-nav-name y-state-nav-name active" data-status="<?php echo ($i); ?>"><?php else: ?> <div  class="row-flex state-nav-name y-state-nav-name" data-status="<?php echo ($i); ?>"><?php endif; ?>
                        <?php echo ($nice); ?></div></a><?php endforeach; endif; else: echo "" ;endif; ?>


            </div>
        </div>
        <div style="width: 100%; height: 0.8rem;"></div>

        <!--content-->
        <div id="LAY_demo1" class="content">

            <!--空购物车-->

        </div>


        <!--content-->

        <!--footer-->
        <!--footer-->
    </body>

    <script>
        $(function () {
            $(".y-goldShop-item:even").addClass("y-border-right")
            function flowAction(type){
                layui.use('flow', function(){
                    var flow = layui.flow;
                    var status = type || 1;
                    $("#LAY_demo1").empty();
                    flow.load({
                        elem: '#LAY_demo1' //流加载容器
                        ,done: function(page, next){ //执行下一页的回调
                            $.get("/index.php?s=/Orderlist/y_order/status/1.html",{p:page,status:status},function (res) {
                                next(res.list, page < res.length); //假设总页数为 10
                                if(res.length==0){
                                    $(".layui-flow-more").empty();
                                }
                            });
                        }
                    });
                });
            }
            flowAction("<?php echo ($_GET['status']); ?>");
            $(".y-state-nav-name ").click(function() {
                $(this).addClass('active').siblings().removeClass('active');
                var status = $(this).data('status') || 1;
                flowAction(status);
            })

        })

        function delete1(){
            var id = $('#de').attr("value");
            $.ajax({
                type:'post',
                url:'<?php echo U("Orderlist/y_order");?>',
                data:{type1:1,oid:id},
                dataType:'json',
                success:function(msg){
                    if(msg.status == 1){
                        layer.msg(msg['msg'],{time:1000},function () {
                            location.href = "<?php echo U('Orderlist/y_order',array('status'=>$status));?>";
                        });
                    }else{
                        layer.msg(msg['msg'],{time:1000});
                    }
                }
            })
        }
        function shouhuo(){
            var id = $('#shou').attr("value");
            $.ajax({
                type:'post',
                url:'<?php echo U("Orderlist/y_order");?>',
                data:{type1:2,oid:id},
                dataType:'json',
                success:function(msg){
                    if(msg.status == 1){
                        layer.msg(msg['msg'],{time:1000},function(){
                            location.href = "<?php echo U('Orderlist/y_order',array('status'=>$status));?>";
                        });
                    }else{
                        layer.msg(msg['msg'],{time:1000});
                    }
                }
            })
        }



    </script>

</html>