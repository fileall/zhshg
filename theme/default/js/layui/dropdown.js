$(function () {

    var itemIndex = 0;

    var tabLoadEndArray = [false, false, false];
    var tabLenghtArray = [28, 15, 47];
    var tabScroolTopArray = [0, 0, 0];
    
    // dropload
    var dropload = $('.khfxWarp').dropload({
        scrollArea: window,
        domDown: {
            domClass: 'dropload-down',
            domRefresh: '<div class="dropload-refresh">上拉加载更多</div>',
            domLoad: '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
            domNoData: '<div class="dropload-noData">已无数据</div>'
        },
        loadDownFn: function (me) {
            setTimeout(function () {
                if (tabLoadEndArray[itemIndex]) {
                    me.resetload();
                    me.lock();
                    me.noData();
                    me.resetload();
                    return;
                }
                var result = '';
                for (var index = 0; index < 10; index++) {
                    if (tabLenghtArray[itemIndex] > 0) {
                        tabLenghtArray[itemIndex]--;
                    } else {
                        tabLoadEndArray[itemIndex] = true;
                        break;
                    }
                     result
                        += ''
                        + '    <li class="filtrate-list-li">'
                        + '      <a href="mobile.php?m=Mobile&amp;c=Index&amp;a=shop_details&amp;id=16">'
                        + '      <div class="filtrate-box row-box"><div class="filtrate-list-left">'
                        + '        <div class="filtrate-list-img vertical-center"><div class="vertical-auto">'
                        + '        <img src="data/attachment/merchant/1710/09/59db6354735ae.jpg"></div></div> '
                        + '        </div>'
                        + '        <div class="row-flex">'
                        + '        <div class="store-name">臻怡家造型丰和店</div>'
                        + '      <div class="store-date">工作时间：13:00-22:00</div>'
                        + '      <div class="store-txt">南昌市青山湖区丰和新城南苑10号商铺</div>'
						+'</div>'
                        + '    <div class="filtrate-list-right">0.5km</div>'
						+'</div>';
                }
                $('.khfxPane').eq(itemIndex).append(result);
                me.resetload();
            }, 500);
        }
    });


    $('.tabHead span').on('click', function () {

        tabScroolTopArray[itemIndex] = $(window).scrollTop();
        var $this = $(this);
        itemIndex = $this.index();
        $(window).scrollTop(tabScroolTopArray[itemIndex]);
        
        $(this).addClass('active').siblings('.tabHead span').removeClass('active');
        $('.tabHead .border').css('left', $(this).offset().left + 'px');
        $('.khfxPane').eq(itemIndex).show().siblings('.khfxPane').hide();

        if (!tabLoadEndArray[itemIndex]) {
            dropload.unlock();
            dropload.noData(false);
        } else {
            dropload.lock('down');
            dropload.noData();
        }
        dropload.resetload();
    });
});