$(document).ready(function() {
	//返回上一步
	$(".return-prev").click(function() {
		window.history.go(-1);
	});

	//广告栏无缝滚动
	$(function() {
		var $scroll = $(".scroll-message-ul");
		var $Length = $scroll.find('li').length;
		var $moveto;
		if($Length > 2) {
			$scroll.hover(
				function() {
					clearInterval($moveto);
				},
				function() {
					$moveto = setInterval(function() {
						var $height = $scroll.find('li').height() + 1;
						$scroll.find("li:first").animate({
								marginTop: -$height + 'px'
							}, 600,
							function() {
								$scroll.find("li:first").css('marginTop', 0).appendTo($scroll);
							});
					}, 5000);
				}
			).trigger('mouseleave');
		}
	});

	//支付方式
	$("input[name = 'pay-name']").click(function() {
		var li = $(this).parents(".pay-li");
		var sib = $(li).siblings(".pay-li");

		if(this.checked) {
			$(li).find(".pay-input-inner").addClass("active");
			$(sib).find(".pay-input-inner").removeClass("active");
		}
	});

	//元宝支付密码
	$(".submit-button button").click(function() {

		if($("#yuanbao").prop("checked") == true) {
			$(".shoe-shaped").addClass("active");
			$(".shoe-shaped-money span img").attr("src", "images/icon28.png");
		}
		if($("#yyb").prop("checked") == true) {
			$(".shoe-shaped").addClass("active");
			$(".shoe-shaped-money span img").attr("src", "images/icon16.png");
		}
		if($("#jinguo").prop("checked") == true) {
			$(".shoe-shaped").addClass("active");
			$(".shoe-shaped-money span img").attr("src", "images/icon29.png");
		}
	});

	//关闭支付密码
	$(".close-shoe-shaped").click(function() {
		$(".shoe-shaped").removeClass("active");
		$(".shoe-shaped-input input").val("");
	});

	//解绑银行卡
	$(".bank-numbers-btn").click(function() {
		if(confirm("您确定要解绑银行卡吗？") == true) {
			$(this).parents(".bank-wrap-li").remove();
		}
	});

	//金果转账密码
	$(".sub-pass").click(function() {
		$(".shoe-shaped").addClass("active");
		$(".shoe-shaped-money span img").attr("src", "images/icon29.png");
	});

	//收款账户银行
	$("input[name = 'bank']").click(function() {
		var li = $(this).parents(".account-form-li"),
			sib = $(li).siblings(".account-form-li");
		if(this.checked) {
			$(li).find(".account-form-input").addClass("active");
			$(sib).find(".account-form-input").removeClass("active");
		}
	});

	//删除上传的图片
	$(".certificate-img-li").click(function() {
		$(".close-certificate").toggleClass("active");
	});

	$(".close-certificate").click(function() {
		$(this).parent(".certificate-img-li").remove();
	});

	//查看大图
	$(".accounts-record-img img").click(function() {
		$(".max-img").attr("src", $(this).attr("src"));
		$(".max-image").addClass("active");
	});

	$(".close-max-image").click(function() {
		$(".max-image").removeClass("active");
	});

	//提现密码
	$(".extract-btn").click(function() {
		$(".shoe-shaped").addClass("active");
		$(".shoe-shaped-money span img").attr("src", "images/icon31.png");
	});

	//选项卡
	function tabs(e, l) {
		$(e).click(function() {
			var index = $(this).index();
			$(this).addClass("active").siblings().removeClass("active");
			$(l).eq(index).addClass("active").siblings().removeClass("active");
		});
	}
	tabs($(".filtrate-name"));
	tabs($(".more-kind-li"));
	tabs($(".classify-nav-li"));
	tabs($(".figure-li"));
	tabs($(".buy-li"));
	tabs($(".vegetables-li"));
	tabs($(".select-bank-li"));
	tabs($(".specification-span"));
	tabs($(".character-nav-name"), $(".character-content-tier"));
	tabs($(".discount-nav-name"), $(".discount-tier"));
	tabs($(".seckill-li"), $(".seckill-data-tier"));

	//更多分类
	function moreFun() {
		var sum = 0;
		$(".filtrate-more").click(function() {
			sum++;
			if(sum % 2 != 0) {
				$(".more-kind").show();
				$(this).find("i").addClass("fa-angle-up").removeClass("fa-angle-down");
			} else {
				$(".more-kind").hide();
				$(this).find("i").removeClass("fa-angle-up").addClass("fa-angle-down");
			}
			$(".filtrate-name").removeClass("active");
		});

		$(".more-kind-li").click(function() {
			sum = 0;
			$(".more-kind").hide();
			$(".filtrate-more i").removeClass("fa-angle-up").addClass("fa-angle-down");
		});
	}
	moreFun();

	//搜索
	function searchFun() {
		var _input = document.getElementById("search");
		$(".serach-tis").click(function() {
			$(this).hide();
			_input.focus();
		});

		$(_input).blur(function() {
			if(_input.value.length == 0) {
				$(".serach-tis").show();
			}

		});
	}
	searchFun();

	//排序
	function rankFun() {
		var one = 0;
		var two = 0;

		$(".commodity-li").click(function() {
			var index = $(this).index();
			var sib = $(this).siblings(".commodity-li");
			$(this).addClass("active").siblings().removeClass("active");
			if(index == 0) {
				one++;
				two = 0;
				if(one % 2 != 0) {
					$(this).find("i").addClass("fa-angle-up").removeClass("fa-angle-down");
					$(sib).find("i").removeClass("fa-angle-up").addClass("fa-angle-down");
				} else {
					$(this).find("i").removeClass("fa-angle-up").addClass("fa-angle-down");
				}
			} else if(index == 1) {
				one = 0;
				two++;
				if(two % 2 != 0) {
					$(this).find("i").addClass("fa-angle-up").removeClass("fa-angle-down");
					$(sib).find("i").removeClass("fa-angle-up").addClass("fa-angle-down");
				} else {
					$(this).find("i").removeClass("fa-angle-up").addClass("fa-angle-down");
				}
			} else if(index == 2) {
				one = 0;
				two = 0;
				$(sib).find("i").removeClass("fa-angle-up").addClass("fa-angle-down");
			}
		});
	}
	rankFun();

	//详情收藏
	$(".operation-box .collect").click(function() {
		$(this).toggleClass("active");
	});

	//详情加入购物车弹窗
	$(".add-indent").click(function() {
		$(".purchase-popup").addClass("active");
	});

	//关闭加入购物车弹窗
	$(".close-purchase").click(function() {
		$(".purchase-popup").removeClass("active");
	});

	$(".purchase-btn-add.join").click(function() {
		$(".purchase-popup").removeClass("active");
	});

	//详情数量加减
	$(".wicket-amount-btn.add").click(function() {
		var sib = $(this).siblings(".wicket-amount-input");
		var val = $(sib).find("input").val();
		var _int = parseInt(val) + 1;
		$(sib).find("input").val(_int);
	});

	$(".wicket-amount-btn.minus").click(function() {
		var sib = $(this).siblings(".wicket-amount-input");
		var val = $(sib).find("input").val();
		var _int = parseInt(val) - 1;
		if(val > 1)
			$(sib).find("input").val(_int);
		else return;
	});

	//购物车编辑
	function compile() {
		var sum = 0;
		$(".compile").click(function() {
			var txt1 = "编辑";
			var txt2 = "完成";
			sum++;
			if(sum % 2 != 0) {
				$(this).text(txt2);
				$(".station-btn.two").show();
				$(".station-btn.one").hide();
			} else {
				$(this).text(txt1);
				$(".station-btn.two").hide();
				$(".station-btn.one").show();
			}
		});
	}
	compile();

	//购物车
	function shoppingFun() {
		var li_input = $("input[name='check-single']"); //数据复选框
		var li = $(".shopping-li").length; //购物车数据
		var num = 0; //计算已选商品

		//遍历选中复选框
		for(var i = 0; i < li_input.length; i++) {
			if(li_input[i].checked) {
				num++;
			}
		}

		//删除
		$(".station-btn.two").click(function() {
			$("input[name = 'check-single']").each(function() {
				if(this.checked) {
					$(this).parents(".shopping-li").remove();
				}
			});
			li -= num;
			num = 0;
		});

		//单选
		$("input[name='check-single']").click(function() {
			if(this.checked) {
				$(this).parents(".station-check-box").addClass("active");
				$(this).parents(".station-check-box").find("i").addClass("fa-check-circle").removeClass("fa-circle-thin");
				num++;
			} else {
				$(this).parents(".station-check-box").removeClass("active");
				$(this).parents(".station-check-box").find("i").removeClass("fa-check-circle").addClass("fa-circle-thin");
				num--;
			}

			if(num != li) {
				$("input[name='check-all']").prop("checked", false);
				$(".station-check").find(".station-check-box").removeClass("active");
				$(".station-check").find(".station-check-box i").removeClass("fa-check-circle").addClass("fa-circle-thin");
			} else {
				$("input[name='check-all']").prop("checked", true);
				$(".station-check").find(".station-check-box").addClass("active");
				$(".station-check").find(".station-check-box i").addClass("fa-check-circle").removeClass("fa-circle-thin");
			}
		});

		//全选
		$("input[name='check-all']").click(function() {
			if(this.checked) {
				$(this).parents(".station-check-box").addClass("active");
				$(this).parents(".station-check-box").find("i").addClass("fa-check-circle").removeClass("fa-circle-thin");

				$(li_input).prop("checked", true);
				$(".shopping-li").find(".station-check-box").addClass("active");
				$(".shopping-li").find(".station-check-box i").addClass("fa-check-circle").removeClass("fa-circle-thin");
				num = li;
			} else {
				$(this).parents(".station-check-box").removeClass("active");
				$(this).parents(".station-check-box").find("i").removeClass("fa-check-circle").addClass("fa-circle-thin");

				$(li_input).prop("checked", false);
				$(".shopping-li").find(".station-check-box").removeClass("active");
				$(".shopping-li").find(".station-check-box i").removeClass("fa-check-circle").addClass("fa-circle-thin");
				num = 0;
			}
		});
	}
	shoppingFun();

	//设置默认地址
	$("input[name = 'location']").click(function() {
		var par = $(this).parents(".location-li"),
			sib = $(par).siblings(".location-li"),
			txt1 = "默认地址",
			txt2 = "设置为默认地址";

		if(this.checked) {
			$(par).find(".location-default").addClass("active");
			$(par).find(".location-default-txt").text(txt1);
			$(par).find(".location-default-input i").addClass("fa-check-circle").removeClass("fa-circle-thin");

			$(sib).find(".location-default").removeClass("active");
			$(sib).find(".location-default-txt").text(txt2);
			$(sib).find(".location-default-input i").removeClass("fa-check-circle").addClass("fa-circle-thin");
		} else {
			$(par).find(".location-default").removeClass("active");
		}
	});

	//删除地址
	$(".location-delete").click(function() {
		if(confirm("您确定要删除地址吗？") == true) {
			$(this).parents(".location-li").remove();
		}
	});

	//设置默认地址开关
	$(".set-location-input input").click(function() {
		if(this.checked) {
			$(".set-location").addClass("active");
			$(".set-location-input").animate({
				"left": "0.4rem"
			}, 100);
		} else {
			$(".set-location").removeClass("active");
			$(".set-location-input").animate({
				"left": "0.1rem"
			}, 100);
		}
	});

	//删除历史记录
	$(".records-delete").click(function() {
		$(this).parents(".records-li").remove();
	});

	//我的资料性别设置
	$("input[name = 'gender']").click(function() {
		var _par = $(this).parents(".gender-tier");
		var _sib = $(_par).siblings(".gender-tier");
		if(this.checked) {
			$(_par).addClass("active");
			$(_par).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");

			$(_sib).removeClass("active");
			$(_sib).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
		}
	});

	//意见反馈
	$(".tickling-textarea textarea").keyup(function() {
		var txt = $(this).val().length;
		for(var i = 0; i <= txt; i++) {
			$(".textarea-length span").text(300 - txt);
		}
	});

	//获取验证码倒计时
	var COUNTDOWN = 60; //计时
	function settime(obj) {
		if(COUNTDOWN == 0) {
			obj.value = "获取验证码";
			COUNTDOWN = 60;
			return;
		} else {
			obj.value = COUNTDOWN + "秒";
			COUNTDOWN--;
		}
		setTimeout(function() {
			settime(obj)
		}, 1000);
	}

	$(".verification").click(function() {
		if($(this).val() != "获取验证码") {
			return false;
		} else {
			settime(this);
		}
	});

	//取消订单
	$(".delete").click(function() {
		$(this).parents(".goods-tier-box").remove();
	});

	//评价删除上传图片
	$(".close-img").click(function() {
		$(".close-evaluate").toggleClass("active");
	});

	$(".close-evaluate").click(function() {
		$(this).parent(".close-img").remove();
	});

	//评价晒图
	$(".close-event").click(function() {
		$(".close-evaluate").toggleClass("active");
	});

	$(".close-evaluate").click(function() {
		$(this).parents(".evaluate-img-tier").remove();
	});

	//商品评价
	function discuss(e, n) {
		$(e).click(function() {
			var ele = $(e);
			var index = $(this).index();
			for(var i = 0; i < ele.length; i++) {
				if(index >= i) {
					$(e).eq(i).addClass("active");
					$(e).eq(i).find("i").addClass("fa-star").removeClass("fa-star-o");
				} else {
					$(e).eq(i).removeClass("active");
					$(e).eq(i).find("i").removeClass("fa-star").addClass("fa-star-o");
				}
			}
			$(n).text((index + 1) + "分");
		});
	}
	discuss($(".discuss-star"), $(".discuss-numbers"));

	//匿名
	$("input[name = 'niname']").click(function() {
		var par = $(this).parent(".niname-input");
		if(this.checked) {
			$(par).addClass("active");
		} else {
			$(par).removeClass("active");
		}
		console.log(11)
	});

	//删除收藏
	$(".enshrine-link-a.delete").click(function() {
		$(this).parents(".enshrine-li").remove();
	});

	//用户协议
	$("input[name = 'switch']").click(function() {
		if(this.checked) {
			$(this).parent(".agreement-input").addClass("active");
		} else {
			$(this).parent(".agreement-input").removeClass("active");
		}
	});

	//是否同意注册协议
	$(".enroll-deal-input input").click(function() {
		if(this.checked) {
			$(".enroll-deal-ok,.enroll-deal-input").addClass("active");
			$(".enter-btn button").attr("disabled", false);
			$(".enter-btn").removeClass("active");
			$(".enroll-deal-input i").addClass("fa-check-circle").removeClass("fa-circle-thin");
		} else {
			$(".enroll-deal-ok,.enroll-deal-input").removeClass("active");
			$(".enter-btn button").attr("disabled", true);
			$(".enter-btn").addClass("active");
			$(".enroll-deal-input i").removeClass("fa-check-circle").addClass("fa-circle-thin");
		}
	});

	//打开注册成功提示弹窗
	$(".open-succeed-win").click(function() {
		$(".succeed-win").addClass("active");
	});

	//支付方式
	$("input[name = 'pay']").click(function() {
		var _par = $(this).parents(".pay-select-li");
		var _sib = $(_par).siblings(".pay-select-li");

		if(this.checked) {
			$(_par).find(".pay-input-inner").addClass("active");
			$(_par).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");

			$(_sib).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
			$(_sib).find(".pay-input-inner").removeClass("active");
		} else {
			$(_sib).find(".pay-input-inner").removeClass("active");
		}
	});

	//余额支付弹窗
	$(".submit-button").click(function() {
		if($("#yue").prop("checked") == true) {
			$(".payment-window").addClass("active");
		} else {
			return false;
		}
	});

	//密码
	var ONTROL = 0; //密码位数
	var PASS_ONE = $(".pass-input-wrap input").eq(0); //第一位
	var PASS_TWO = $(".pass-input-wrap input").eq(1); //第二位
	var PASS_THREE = $(".pass-input-wrap input").eq(2); //第三位
	var PASS_FOUR = $(".pass-input-wrap input").eq(3); //第四位
	var PASS_FIVE = $(".pass-input-wrap input").eq(4); //第五位
	var PASS_SIX = $(".pass-input-wrap input").eq(5); //第六位
	$(".pay-button.figure").click(function() {
		var num = $(this).val();
		ONTROL++;
		if(ONTROL == 1) {
			$(PASS_ONE).val(num);
			$(PASS_ONE).siblings(".pass-input-dot").show();
			$(".pay-button.empty").attr("disabled", false);
		} else if(ONTROL == 2) {
			$(PASS_TWO).val(num);
			$(PASS_TWO).siblings(".pass-input-dot").show();
		} else if(ONTROL == 3) {
			$(PASS_THREE).val(num);
			$(PASS_THREE).siblings(".pass-input-dot").show();
		} else if(ONTROL == 4) {
			$(PASS_FOUR).val(num);
			$(PASS_FOUR).siblings(".pass-input-dot").show();
		} else if(ONTROL == 5) {
			$(PASS_FIVE).val(num);
			$(PASS_FIVE).siblings(".pass-input-dot").show();
		} else if(ONTROL == 6) {
			$(PASS_SIX).val(num);
			$(PASS_SIX).siblings(".pass-input-dot").show();
			$(".pay-button.figure").attr("disabled", true);
		}
	});

	//清空密码
	$(".pay-button.empty").click(function() {
		ONTROL--;
		if(ONTROL == 5) {
			$(PASS_SIX).val("");
			$(PASS_SIX).siblings(".pass-input-dot").hide();
			$(".pay-button.figure").attr("disabled", false);
		} else if(ONTROL == 4) {
			$(PASS_FIVE).val("");
			$(PASS_FIVE).siblings(".pass-input-dot").hide();
		} else if(ONTROL == 3) {
			$(PASS_FOUR).val("");
			$(PASS_FOUR).siblings(".pass-input-dot").hide();
		} else if(ONTROL == 2) {
			$(PASS_THREE).val("");
			$(PASS_THREE).siblings(".pass-input-dot").hide();
		} else if(ONTROL == 1) {
			$(PASS_TWO).val("");
			$(PASS_TWO).siblings(".pass-input-dot").hide();
		} else if(ONTROL == 0) {
			$(PASS_ONE).val("");
			$(PASS_ONE).siblings(".pass-input-dot").hide();
			$(".pay-button.empty").attr("disabled", true);
		}
	});

	//取消余额支付弹窗
	$(".pay-button.cancel").click(function() {
		$(".payment-window").removeClass("active");
		$(".pay-window").removeClass("active");
		ONTROL = 0;
		$(".pass-input-wrap input").val("");
		$(".pass-input-dot").hide();
		$(".pay-button.figure").attr("disabled", false);
		$(".pay-button.empty").attr("disabled", true);
	});

	//秒杀滑动
	function activityScroll() {
		var width = $(".activity-li").outerWidth();
		var len = $(".activity-li").length;
		var outwidth = width * len;
		$(".activity-ul").css({
			"width": outwidth
		});
	}
	activityScroll();

	//生鲜
	function vegetables() {
		var sun = 0;
		$(".vegetables-li").each(function() {
			var width = $(this).outerWidth();
			sun += width;
		});
		$(".vegetables-ul").css({
			"width": sun
		});
	}
	vegetables();
});