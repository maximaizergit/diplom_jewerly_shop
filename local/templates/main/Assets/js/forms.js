$(document).ready(function() {
	//FORMS
	function forms(){
		$('input,textarea').focus(function(){
			if($(this).val() == $(this).attr('data-value')){
					$(this).addClass('focus');
					$(this).parent().addClass('focus');
					$(this).removeClass('err');
					$(this).parent().removeClass('err');
				if($(this).attr('data-type')=='pass'){
					$(this).attr('type','password');
				};
				$(this).val('');
			};
		});
		$('input[data-value], textarea[data-value]').each(function() {
			if (this.value == '' || this.value == $(this).attr('data-value')) {
				this.value = $(this).attr('data-value');
				if($(this).hasClass('l')) {
					$(this).parent().append('<div class="form__label">'+$(this).attr('data-value')+'</div>');
				}
			}
			$(this).click(function() {
				if (this.value == $(this).attr('data-value')) {
					if($(this).attr('data-type')=='pass'){
						$(this).attr('type','password');
					};
					this.value = '';
				};
			});
			$(this).blur(function() {
				if (this.value == '') {
					this.value = $(this).attr('data-value');
						$(this).removeClass('focus');
						$(this).parent().removeClass('focus');
					if($(this).attr('data-type')=='pass'){
						$(this).attr('type','text');
					};
				};
			});
		});
		//MASKS//
		//'+7(999) 999 9999'
		//'+375(99)999-99-99'
		//'a{3,1000}' только буквы минимум 3
		//'9{3,1000}' только цифры минимум 3

		$.each($('input.phone'), function(index, val) {
			$(this).focus(function(){
				$(this).inputmask('+7(999) 999 9999',{clearIncomplete: true,clearMaskOnLostFocus: true,
					"onincomplete": function(){maskclear($(this));}
				});
				$(this).addClass('focus');
				$(this).parent().addClass('focus');
				$(this).parent().removeClass('err');
				$(this).removeClass('err');
			});
		});
		$('input.phone').focusout(function(event) {
			maskclear($(this));
		});
		//SELECT

		//CHECK
		$.each($('.check'), function(index, val) {
			if($(this).find('input').prop('checked')==true){
				$(this).addClass('active');
			}
		});
		$('.check').click(function(event) {
			if(!$(this).hasClass('disable')){
					var target = $(event.target);
				if (!target.is("a")){
					$(this).toggleClass('active');
					if($(this).hasClass('active')){
						$(this).find('input').prop('checked', true);
					}else{
						$(this).find('input').prop('checked', false);
					}
				}
			}
		});
		//OPTION
		$.each($('.option.active'), function(index, val) {
			$(this).find('input').prop('checked', true);
		});
		$('.option').click(function(event) {
			if(!$(this).hasClass('disable')){
				if($(this).hasClass('active') && $(this).hasClass('order') ){
					$(this).toggleClass('orderactive');
				}
					$(this).parents('.options').find('.option').removeClass('active');
					$(this).toggleClass('active');
					$(this).children('input').prop('checked', true);
			}
		});
		//RATING
		$('.rating.edit .star').hover(function() {
				var block=$(this).parents('.rating');
			block.find('.rating__activeline').css({width:'0%'});
				var ind=$(this).index()+1;
				var linew=ind/block.find('.star').length*100;
			setrating(block,linew);
		},function() {
				var block=$(this).parents('.rating');
			block.find('.star').removeClass('active');
				var ind=block.find('input').val();
				var linew=ind/block.find('.star').length*100;
			setrating(block,linew);
		});
		$('.rating.edit .star').click(function(event) {
				var block=$(this).parents('.rating');
				var re=$(this).index()+1;
				block.find('input').val(re);
				var linew=re/block.find('.star').length*100;
			setrating(block,linew);
		});
		$.each($('.rating'), function(index, val) {
				var ind=$(this).find('input').val();
				var linew=ind/$(this).parent().find('.star').length*100;
			setrating($(this),linew);
		});
		function setrating(th,val) {
			th.find('.rating__activeline').css({width:val+'%'});
		}
		//QUANTITY
		$('.quantity__btn').click(function(event) {
				var n=parseInt($(this).parent().find('.quantity__input').val());
			if($(this).hasClass('dwn')){
				n=n-1;
				if(n<1){n=1;}
			}else{
				n=n+1;
			}
				$(this).parent().find('.quantity__input').val(n);
			return false;
		});
		//RANGE
		if($("#range" ).length>0){
			$("#range" ).slider({
				range: true,
				min: 0,
				max: 5000,
				values: [0, 5000],
				slide: function( event, ui ){
					$('#rangefrom').val(ui.values[0]);
					$('#rangeto').val(ui.values[1]);
					$(this).find('.ui-slider-handle').eq(0).html('<span>'+ui.values[0]+'</span>');
					$(this).find('.ui-slider-handle').eq(1).html('<span>'+ui.values[1]+'</span>');
				},
				change: function( event, ui ){
					if(ui.values[0]!=$( "#range" ).slider( "option","min") || ui.values[1]!=$( "#range" ).slider( "option","max")){
						$('#range').addClass('act');
					}else{
						$('#range').removeClass('act');
					}
				}
			});
			$('#rangefrom').val($( "#range" ).slider( "values", 0 ));
			$('#rangeto').val($( "#range" ).slider( "values", 1 ));

			$("#range" ).find('.ui-slider-handle').eq(0).html('<span>'+$( "#range" ).slider( "option","min")+'</span>');
			$("#range" ).find('.ui-slider-handle').eq(1).html('<span>'+$( "#range" ).slider( "option","max")+'</span>');
			
			$( "#rangefrom" ).bind("change", function(){
				if($(this).val()*1>$( "#range" ).slider( "option","max")*1){
					$(this).val($( "#range" ).slider( "option","max"));
				}
				if($(this).val()*1<$( "#range" ).slider( "option","min")*1){
					$(this).val($( "#range" ).slider( "option","min"));
				}
				$("#range" ).slider( "values",0,$(this).val());
			});
			$( "#rangeto" ).bind("change", function(){
				if($(this).val()*1>$( "#range" ).slider( "option","max")*1){
					$(this).val($( "#range" ).slider( "option","max"));
				}
				if($(this).val()*1<$( "#range" ).slider( "option","min")*1){
					$(this).val($( "#range" ).slider( "option","min"));
				}
				$("#range" ).slider( "values",1,$(this).val());
			});
			$("#range" ).find('.ui-slider-handle').eq(0).addClass('left');
			$("#range" ).find('.ui-slider-handle').eq(1).addClass('right');
		}
	}
	forms();

	let email = null;
	let name = null;

	//VALIDATE FORMS
	$('form button[type=submit]').click(function(){
			var er=0;
			var form=$(this).parents('form');
		$.each(form.find('.req'), function(index, val) {
			if($(this).attr('name')=='email' || $(this).hasClass('email')){
				if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test($(this).val()))){
						er++;
						$(this).addClass('err');
						$(this).parent().addClass('err');
					if($(this).data('error') && $(this).data('error')!='' && $(this).parent().find('.form__error').length==0){
						$(this).parent().append('<div class="form__error animated">'+$(this).data('error')+'</div>');
					}
				}else{
					$(this).removeClass('err');
					$(this).parent().removeClass('err');
					$(this).parent().find('.form__error animated').remove();
					email = $(this).val();
				}
			}else{
				if($(this).val()=='' || $(this).val()==$(this).attr('data-value')){
					er++;
					if($(this).parents('.select-block').length>0){
						$(this).parents('.select-block').addClass('err');
					}else{
							$(this).addClass('err');
							$(this).parent().addClass('err');
						if($(this).data('error') && $(this).data('error')!='' && $(this).parent().find('.form__error').length==0){
							$(this).parent().append('<div class="form__error animated">'+$(this).data('error')+'</div>');
						}
					}
				}else{
					if($(this).parents('.select-block').length>0){
						$(this).parents('.select-block').removeClass('err');
					}else{
						$(this).removeClass('err');
						$(this).parent().removeClass('err');
						$(this).parent().find('.form__error animated').remove();
					}
					
				}
			}
			if($(this).attr('type')=='checkbox'){
				if($(this).prop('checked') == true){
					$(this).removeClass('err').parent().removeClass('err');
				}else{
					er++;
					$(this).addClass('err').parent().addClass('err');
				}
			}
			if($(this).attr('name')=='name' || $(this).hasClass('name')) {
				name = $(this).val();
			}
		});

		if(form.find('.pass').eq(0).val()!=form.find('.pass').eq(1).val()){
			er++;
			form.find('.pass').addClass('err');
		}else{
			form.find('.pass').removeClass('err');
		}
		if(er==0){
			if($('.popup-message').length>0 && form.hasClass('hm')){
				$('.popup').hide();
				$('.popup.'+form.data('m')).addClass('active').fadeIn(300);
				$.each(form.find('.input'), function(index, val) {
						$(this).removeClass('focus').val($(this).data('value'));
					if($(this).hasClass('phone')){
						maskclear($(this));
					}
				});
				if(form.hasClass('subform')){
					$.ajax({
						type: "POST",
						url: "/local/ajax/subscribe.php",
						data: {
							name: name,
							email: email,
						},
					success: function(response){

						console.log(JSON.parse(response));
					}
				});
				}
				return false;
			}
		}else{
			return false;
		}
	});
	function maskclear(n){
		if(n.val()==""){
			n.inputmask('remove');
			n.val(n.attr('data-value'));
			n.removeClass('focus');
			n.parent().removeClass('focus');
		}
	}
});